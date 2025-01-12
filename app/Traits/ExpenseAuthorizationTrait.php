<?php

namespace App\Traits;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

trait ExpenseAuthorizationTrait
{
  /**
   * Cek apakah pengguna memiliki izin untuk memperbarui pengeluaran.
   * Jika tidak, langsung hentikan eksekusi dengan abort().
   *
   * @param Expense $expense
   * @return void
   */
  public function canUpdateExpense(Expense $expense): bool
  {
    if (Auth::user()->id === $expense->user_id || Auth::user()->hasRole('superadmin')) {
      if ($expense->status == "canceled" || $expense->updated_at->diffInHours(now()) > 12) {
        abort(403, 'Unauthorized to update this expense.');
      }
      return true;
    } else {
      abort(403, 'Unauthorized access.');
    }
  }
}
