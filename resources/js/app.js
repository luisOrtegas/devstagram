import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";

Dropzone.autoDiscover = false;

const dropzoneElement = document.querySelector('#dropzone');
const imageInput = document.querySelector('[name="imagen"]');

if (dropzoneElement && imageInput) {
    const dropzone = new Dropzone(dropzoneElement, {
        dictDefaultMessage: 'Sube aquí tu imagen',
        acceptedFiles: '.png, .jpg, .jpeg, .gif',
        addRemoveLinks: true,
        dictRemoveFile: 'Borrar archivo',
        maxFiles: 1,
        uploadMultiple: false,

        init: function() {
            if (imageInput.value.trim()) {
                const imagenPublicada = {
                    name: imageInput.value,
                    size: 0,
                };

                this.options.addedfile.call(this, imagenPublicada);
                this.options.thumbnail.call(this, imagenPublicada, `/uploads/${imagenPublicada.name}`);

                imagenPublicada.previewElement.classList.add('dz-success', 'dz-complete');
            }
        },
    });


    dropzone.on('success', function(file, response) {
        imageInput.value = response.imagen;
    });

    dropzone.on('removedfile', function() {
        imageInput.value = '';
    });
}
