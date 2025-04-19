document.addEventListener('DOMContentLoaded', () => {
  const themeOptions = document.body.classList.contains('theme-dark')
    ? {
        skin: 'oxide-dark',
        content_css: 'dark',
      }
    : {
        skin: 'oxide',
        content_css: 'default',
      };

  tinymce.init({
    selector: '.tinymce',
    plugins: [
      // Core editing features
      'anchor',
      'autolink',
      'charmap',
      'codesample',
      'emoticons',
      'image',
      'link',
      'lists',
      'media',
      'searchreplace',
      'table',
      'visualblocks',
      'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until May 2, 2025:
      'checklist',
      'mediaembed',
      'casechange',
      'formatpainter',
      'pageembed',
      'a11ychecker',
      'tinymcespellchecker',
      'permanentpen',
      'powerpaste',
      'advtable',
      'advcode',
      'editimage',
      'advtemplate',
      'ai',
      'mentions',
      'tinycomments',
      'tableofcontents',
      'footnotes',
      'mergetags',
      'autocorrect',
      'typography',
      'inlinecss',
      'markdown',
      'importword',
      'exportword',
      'exportpdf',
    ],
    toolbar:
      'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      {
        value: 'First.Name',
        title: 'First Name',
      },
      {
        value: 'Email',
        title: 'Email',
      },
    ],
    ai_request: (request, respondWith) =>
      respondWith.string(() =>
        Promise.reject('See docs to implement AI Assistant')
      ),
  });
});
