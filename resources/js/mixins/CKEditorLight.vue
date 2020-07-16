<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  name: 'CKEditorLight',
  data() {
    return {
      editor: ClassicEditor,
      editorConfig: {
        toolbar: [
          //'heading',
          'bold',
          'italic',
          '|',
          'link',
          'bulletedList',
          'numberedList',
        ],
        // heading: {
        //   options: [
        //     {
        //       model: 'paragraph',
        //       title: 'Paragraph',
        //       class: 'ck-heading_paragraph',
        //     },
        //     {
        //       model: 'heading2',
        //       view: 'h2',
        //       title: 'Heading 2',
        //       class: 'ck-heading_heading2',
        //     },
        //     {
        //       model: 'heading3',
        //       view: 'h3',
        //       title: 'Heading 3',
        //       class: 'ck-heading_heading3',
        //     },
        //   ],
        // },
      },
    }
  },
}
</script>
