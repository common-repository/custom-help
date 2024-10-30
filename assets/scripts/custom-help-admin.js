
document.addEventListener('DOMContentLoaded', () => {
   
  document.querySelectorAll('.custom-help-edit-documentation').forEach(element => {
    element.addEventListener('click', (e) => {
      e.preventDefault();
      editDocumentation();
    });  
  });

  document.querySelectorAll('.custom-help-show-documentation').forEach(element => {
    element.addEventListener('click', (e) => {
      e.preventDefault();
      showDocumentation();
    });  
  });

  document.querySelector('#custom-help-form-submit').addEventListener('click', (e) => {
    e.preventDefault();
    submitForm();
  });

});

const editDocumentation = () => {
  document.querySelector('#custom-help').setAttribute('data-show', 'form');
}

const showDocumentation = () => {
  document.querySelector('#custom-help').setAttribute('data-show', 'content');
}

const submitForm = () => {
  const form = new FormData();
  form.append('action', 'customhelp_save');
  form.append('id', document.querySelector('#custom-help form [name="id"]').value);
  form.append('filename', document.querySelector('#custom-help form [name="filename"]').value);
  form.append('page', document.querySelector('#custom-help form [name="page"]').value);
  form.append('post_type', document.querySelector('#custom-help form [name="post_type"]').value);
  form.append('is_markdown', document.querySelector('#custom-help form [name="is_markdown"]').value);
  form.append('content', document.querySelector('#custom-help form [name="content"]').value);
  document.querySelector('#custom-help-form').setAttribute('data-loading', 'true');
  fetch(customhelp.ajaxurl, {
    method: 'post',
    body: new URLSearchParams(form),
    headers: {
      'X-WP-Nonce': customhelp.nonce,
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cache-Control': 'no-cache'
    }
  }).then((response) => {
    return response.json();
  }).then((response) => {
    document.querySelector('#custom-help-form').setAttribute('data-loading', 'false');
    document.querySelector('#custom-help-content').innerHTML = response.data.content;
    document.querySelector('#custom-help-form [name="id"]').value = response.data.id;
    document.querySelector('#custom-help-content-last-edition').setAttribute('data-hidden', 'false');
    document.querySelector('#custom-help-content-last-edition span:first-of-type').innerHTML = response.data.editor;
    document.querySelector('#custom-help-content-last-edition span:last-of-type').innerHTML = response.data.date_updated;
  }).catch((error) => {
    document.querySelector('#custom-help-form').setAttribute('data-loading', 'false');
  });
}
