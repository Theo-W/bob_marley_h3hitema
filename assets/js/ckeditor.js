import ClassicEditor from '@ckeditor/ckeditor5-build-classic';


ClassicEditor
    .create(document.querySelector('#news_content'), {
        toolbar: {
            items: [ 'heading', '|', 'bold', 'italic', '|', 'undo', 'redo', '|', 'numberedList', 'bulletedList' ]
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Titre 1', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Titre 2', class: 'ck-heading_heading3' }
            ]
        }
    })
    .catch(error => {
        console.error(error);
    });