class ImageUploader {
    constructor(options) {
        this.cropper = null;
        this.currentFile = null;
        this.currentLabel = null;
        this.options = options;

        this.initialize();
    }

    initialize() {
        document.querySelectorAll(".picture__input").forEach((inputFile) => {
            const label = inputFile.previousElementSibling;
            const pictureImage = label.querySelector(".picture__image");
            const pictureText = label.querySelector(".picture__text");
            const pictureButtons = label.querySelector(".picture__buttons");

            if (inputFile.dataset.imageSrc) {
                this.displayImage(
                    inputFile.dataset.imageSrc,
                    pictureImage,
                    pictureText,
                    pictureButtons
                );
            }

            inputFile.addEventListener("change", (e) =>
                this.handleFileChange(e, inputFile, label)
            );

            label
                .querySelector(".delete-btn")
                .addEventListener("click", () =>
                    this.resetImageDisplay(
                        pictureImage,
                        pictureText,
                        pictureButtons,
                        inputFile
                    )
                );

            label.querySelector(".crop-btn").addEventListener("click", () => {
                const imgElement = pictureImage.querySelector("img");
                if (imgElement) {
                    $("#cropModal").modal("show");
                    $(".modal-crop-canvas").attr("src", imgElement.src);
                    this.currentFile = inputFile.files[0];
                    this.currentLabel = label;
                }
            });
        });

        this.setupModal();
    }

    handleFileChange(event, inputFile, label) {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const imgSrc = e.target.result;
                this.showCropModal(imgSrc, inputFile, label);
            };
            reader.readAsDataURL(file);
        } else {
            const pictureImage = label.querySelector(".picture__image");
            const pictureText = label.querySelector(".picture__text");
            const pictureButtons = label.querySelector(".picture__buttons");
            this.resetImageDisplay(pictureImage, pictureText, pictureButtons);
        }
    }

    displayImage(src, pictureImage, pictureText, pictureButtons) {
        const img = document.createElement("img");
        img.src = src;
        img.classList.add("picture__img");

        pictureImage.innerHTML = "";
        pictureImage.appendChild(img);
        pictureText.style.display = "none";
        pictureButtons.style.display = "flex";
    }

    resetImageDisplay(pictureImage, pictureText, pictureButtons, inputFile) {
        pictureImage.innerHTML = "";
        pictureText.style.display = "block";
        pictureButtons.style.display = "none";
        inputFile.value = ""; // Clear input file

        if (inputFile.dataset.imageId) {
            let hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = `delete_image[]`;
            hiddenInput.value = inputFile.dataset.imageId;
            inputFile.parentElement.appendChild(hiddenInput);
        }
    }

    showCropModal(imgSrc, inputFile, label) {
        $("#cropModal").modal("show");
        $(".modal-crop-canvas").attr("src", imgSrc);
        this.currentFile = inputFile.files[0];
        this.currentLabel = label;
    }

    setupModal() {
        $("#cropModal").on("shown.bs.modal", () => {
            if (this.cropper) this.cropper.destroy();

            let aspectRatio = this.options.cropRatio
                ? eval(this.options.cropRatio)
                : 1;

            this.cropper = new Cropper(
                document.querySelector(".modal-crop-canvas"),
                {
                    aspectRatio: aspectRatio,
                    dragMode: "move", // Memungkinkan gambar untuk bergerak
                    autoCropArea: 1,
                    cropBoxMovable: false, // Area crop tetap diam
                    cropBoxResizable: false, // Area crop tidak dapat diubah ukurannya
                    movable: true, // Memungkinkan gambar untuk bergerak
                    checkOrientation: false,
                    viewMode: 1,
                }
            );
        });

        $("#rotateImageModal").on("click", () => {
            if (this.cropper) {
                this.cropper.rotate(90);
            }
        });

        $("#cropImageModal").on("click", () => {
            const canvas = this.cropper.getCroppedCanvas({
                width: this.options.imageWidth,
                height: this.options.imageHeight,
            });

            canvas.toBlob((blob) => {
                this.updateCroppedImage(blob);
                $("#cropModal").modal("hide");
            });
        });
    }

    updateCroppedImage(blob) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(blob);
        img.classList.add("picture__img");

        const pictureImage = this.currentLabel.querySelector(".picture__image");
        const pictureText = this.currentLabel.querySelector(".picture__text");
        const pictureButtons =
            this.currentLabel.querySelector(".picture__buttons");

        pictureImage.innerHTML = "";
        pictureImage.appendChild(img);

        pictureText.style.display = "none";
        pictureButtons.style.display = "flex";

        const fileInput = this.currentLabel.nextElementSibling;
        const dataTransfer = new DataTransfer();
        const fileName = this.currentFile
            ? this.currentFile.name
            : `new ${Date.now()}`;

        const croppedFile = new File([blob], `${fileName}_cropped.jpg`, {
            type: blob.type,
            lastModified: Date.now(),
        });

        dataTransfer.items.add(croppedFile);
        fileInput.files = dataTransfer.files;

        if (fileInput.dataset.imageId) {
            console.log("update image");
            let hiddenInput = fileInput.parentElement.querySelector(
                `input[name="delete_image[]"]`
            );
            if (hiddenInput) {
                hiddenInput.remove();
            }
        }
    }
}
