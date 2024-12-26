class ImageUploader {
    constructor(options) {
        this.cropper = null;
        this.currentFile = null;
        this.currentLabel = null;
        this.options = options;
        this.eventListeners = [];

        this.initialize();
    }

    initialize() {
        document.querySelectorAll(".picture__input").forEach((inputFile) => {
            inputFile.setAttribute(
                "accept",
                "image/png, image/jpeg, image/jpg, image/svg+xml, image/webp, image/bmp, image/gif, image/tiff, image/x-icon"
            );

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

            const changeListener = (e) =>
                this.handleFileChange(e, inputFile, label);
            inputFile.addEventListener("change", changeListener);
            this.eventListeners.push({
                element: inputFile,
                type: "change",
                listener: changeListener,
            });

            const deleteBtn = label.querySelector(".delete-btn");
            const deleteListener = () =>
                this.resetImageDisplay(
                    pictureImage,
                    pictureText,
                    pictureButtons,
                    inputFile
                );
            deleteBtn.addEventListener("click", deleteListener);
            this.eventListeners.push({
                element: deleteBtn,
                type: "click",
                listener: deleteListener,
            });

            const cropBtn = label.querySelector(".crop-btn");
            const cropListener = () => {
                const imgElement = pictureImage.querySelector("img");
                if (imgElement) {
                    $(".modal.fade.show").hide();
                    $("#cropModal").modal("show");
                    $(".modal-crop-canvas").attr("src", imgElement.src);
                    this.currentFile = inputFile.files[0];
                    this.currentLabel = label;
                }
            };
            cropBtn.addEventListener("click", cropListener);
            this.eventListeners.push({
                element: cropBtn,
                type: "click",
                listener: cropListener,
            });

            // const modalCloseBtn = document
            //     .querySelector("#cropModal")
            //     .querySelector('[data-bs-dismiss="modal"]');
            // const modalCloseListener = () =>
            //     this.resetImageDisplay(
            //         pictureImage,
            //         pictureText,
            //         pictureButtons,
            //         inputFile
            //     );
            // modalCloseBtn.addEventListener("click", modalCloseListener);
            // this.eventListeners.push({
            //     element: modalCloseBtn,
            //     type: "click",
            //     listener: modalCloseListener,
            // });
        });

        this.setupModal();

        // Tambahkan event listener untuk reset form
        document.querySelectorAll("form").forEach((form) => {
            const resetListener = () => this.resetAllInputs(form);
            form.addEventListener("reset", resetListener);
            this.eventListeners.push({
                element: form,
                type: "reset",
                listener: resetListener,
            });
        });
    }

    destroy() {
        // Hapus semua event listener yang ditambahkan
        this.eventListeners.forEach(({ element, type, listener }) => {
            element.removeEventListener(type, listener);
        });
        this.eventListeners = [];

        // Hancurkan cropper jika ada
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }

        // Reset semua input gambar
        document.querySelectorAll(".picture__input").forEach((inputFile) => {
            const label = inputFile.previousElementSibling;
            const pictureImage = label.querySelector(".picture__image");
            const pictureText = label.querySelector(".picture__text");
            const pictureButtons = label.querySelector(".picture__buttons");
            this.resetImageDisplay(
                pictureImage,
                pictureText,
                pictureButtons,
                inputFile
            );
        });

        // console.log("ImageUploader instance destroyed.");
    }

    resetAllInputs(form) {
        form.querySelectorAll(".picture__input").forEach((inputFile) => {
            const label = inputFile.previousElementSibling;
            const pictureImage = label.querySelector(".picture__image");
            const pictureText = label.querySelector(".picture__text");
            const pictureButtons = label.querySelector(".picture__buttons");
            this.resetImageDisplay(
                pictureImage,
                pictureText,
                pictureButtons,
                inputFile
            );
        });
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
        $("#cropModal").on("show.bs.modal", function () {
            $(".modal.fade.show").hide();

            $(this).find(".modal-body").hide();
            $(this).find(".modal-footer").hide();
        });

        $("#cropModal").on("shown.bs.modal", () => {
            if (this.cropper) this.cropper.destroy();

            $("#cropModal")
                .find(".modal-body")
                .show(600, () => {
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
            $("#cropModal").find(".modal-footer").show(600);
        });

        $("#cropModal").on("hidden.bs.modal", () => {
            $(".modal.fade.show").show();
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
