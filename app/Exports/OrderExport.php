<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class OrderExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $outlet;
    protected $startDate;
    protected $endDate;

    public function __construct($outlet, $startDate, $endDate)
    {
        $this->outlet = $outlet;
        $this->startDate = Carbon::createFromFormat('d M Y', $startDate)->startOfDay();
        $this->endDate = Carbon::createFromFormat('d M Y', $endDate)->endOfDay();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $orders = Order::where('outlet_id', $this->outlet->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with('items', 'user')
            ->get();

        if ($orders->isEmpty()) {
            throw new \Exception('Tidak ada data yang ditemukan untuk periode yang dipilih.');
        }

        return $orders;
    }

    public function headings(): array
    {
        return [
            [getCompanyInfo()->name ?? ''], // Baris 1: Nama perusahaan
            [$this->outlet->name ?? '-'], // Baris 2: Nama outlet
            [($this->outlet->address ?? '-') . " " . ($this->outlet->phone_number ?? '-')], // Baris 3: Alamat dan No HP
            ["Laporan Pendapatan: " . $this->startDate->format('d F Y') . " s/d " . $this->endDate->format('d F Y')],
            [], // Baris kosong sebelum header tabel
            ['#', 'ID Pesanan', 'Keterangan', 'Oleh', 'Item', '', '', '', '', '', 'Tgl Pengeluaran', 'Total'],
            ['', '', '', '', 'No', 'Name', 'Keterangan', 'Qty', 'Harga', 'Subtotal', '', '']
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function map($orders): array
    {
        $rows = [];
        $isFirstRow = true; // Flag untuk baris pertama pengeluaran
        static $numbering = 0;

        foreach ($orders->items as $itemIndex => $item) {
            $row = [];

            if ($isFirstRow) {
                // Baris pertama pengeluaran
                $row[] = ++$numbering; // #
                $row[] = $orders->id; // Nama
                $row[] = strip_tags($orders->description) ?: '-'; // Deskripsi
                $row[] = $orders->user->name; // Oleh
            } else {
                // Baris tambahan: Kolom informasi pengeluaran dikosongkan
                $row[] = ''; // #
                $row[] = ''; // Nama
                $row[] = ''; // Deskripsi
                $row[] = ''; // Oleh
            }

            // Informasi item
            $row[] = $itemIndex + 1; // No
            $row[] = $item->menu->name; // Name
            $row[] = strip_tags($item->note) ?: '-'; // Keterangan
            $row[] = $item->quantity; // Qty
            $row[] = $item->price; // Harga
            $row[] = $item->quantity * $item->price; // Subtotal

            if ($isFirstRow) {
                // Tanggal dan total hanya di baris pertama
                $row[] = Carbon::parse($orders->date_out)->format('d F Y H:i'); // Tgl Pengeluaran
                $row[] = $orders->total; // Total
                $isFirstRow = false;
            } else {
                $row[] = ''; // Kosong untuk Tgl Pengeluaran
                $row[] = ''; // Kosong untuk Total
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge cells for additional information
                $sheet->mergeCells('A1:L1'); // Nama Perusahaan
                $sheet->mergeCells('A2:L2'); // Nama Outlet
                $sheet->mergeCells('A3:L3'); // Alamat dan Nomor HP
                $sheet->mergeCells('A4:L4');

                // Merge cells for headings
                $sheet->mergeCells('A6:A7'); // #
                $sheet->mergeCells('B6:B7'); // Pengeluaran Nama
                $sheet->mergeCells('C6:C7'); // Pengeluaran Deskripsi
                $sheet->mergeCells('D6:D7'); // Oleh
                $sheet->mergeCells('E6:J6'); // Item columns header (baris pertama)
                $sheet->mergeCells('K6:K7'); // Tgl Pengeluaran
                $sheet->mergeCells('L6:L7'); // Total

                // Apply styling
                $sheet->getStyle('A1:L4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A6:L7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Auto size columns
                foreach (range('A', 'L') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Apply border to each expense
                $expenses = $this->collection();
                $startRow = 8; // Starting row for data
                $totalAmount = 0; // Variable for total

                foreach ($expenses as $expense) {
                    $totalAmount += $expense->total; // Sum the total column
                    $endRow = $startRow + count($expense->items) - 1;
                    $sheet->getStyle("A{$startRow}:L{$endRow}")->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                    $startRow = $endRow + 1;
                }

                // Add total row
                $sheet->setCellValue("K{$startRow}", 'Total:');
                $sheet->setCellValue("L{$startRow}", $totalAmount);

                // Style for total row
                $sheet->getStyle("K{$startRow}:L{$startRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
