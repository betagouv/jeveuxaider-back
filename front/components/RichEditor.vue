<template>
  <ckeditor
    :editor="editor"
    :value="value"
    :config="editorConfig"
    @input="(ev) => $emit('input', ev)"
  />
</template>

<script>
let ClassicEditor
let CKEditor

if (process.client) {
  ClassicEditor = require('@ckeditor/ckeditor5-build-classic')
  CKEditor = require('@ckeditor/ckeditor5-vue2')
} else {
  CKEditor = { component: { template: '<div></div>' } }
}

export default {
  name: 'RichEditor',
  components: {
    ckeditor: CKEditor.component,
  },
  props: {
    value: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      editor: ClassicEditor,
      editorConfig: {
        mediaEmbed: {
          isEnabled: false,
          removeProviders: [
            'youtube',
            'dailymotion',
            'vimeo',
            'spotify',
            'twitter',
            'googleMaps',
            'flickr',
            'facebook',
          ],
        },
        toolbar: [
          // 'heading',
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
