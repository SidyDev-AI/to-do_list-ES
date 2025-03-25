$(document).ready(function() {
  $('.edit-btn').on('click', function() {
    var $task = $(this).closest('.task');
    var taskId = $task.data('id'); // Pegando o ID da tarefa
    var $description = $task.find('.task-description');
    var currentText = $description.text().trim();
    
    var $input = $('<input type="text" class="edit-input">').val(currentText);
    $description.replaceWith($input);
    $input.focus();
    
    $input.on('blur keydown', function(e) {
      if (e.type === 'blur' || e.key === 'Enter') {
        var newText = $input.val().trim();
        if (newText.length > 0) {
          // Atualiza no banco de dados via AJAX
          $.post('update_task.php', { id: taskId, description: newText }, function(response) {
            if (response.success) {
              $input.replaceWith('<span class="task-description">' + newText + '</span>');
            } else {
              alert('Erro ao atualizar a tarefa!');
            }
          }, 'json').fail(function() {
            alert('Erro ao conectar com o servidor!');
          });
        } else {
          $input.replaceWith($description);
        }
      }
    });
  });

  $('.progress').on('click', function() {
    if($(this).is(':checked')) {
      $(this).addClass('done')
    } else {
      $(this).removeClass('done')
    }
  })

  $('.to-do_form').on('submit', function(event) {
    event.preventDefault();
    
    var taskDescription = $(this).find('input[name="description"]').val().trim();
    if (taskDescription.length === 0) return;
    
    $.post('index.php', { description: taskDescription }, function(response) {
      console.log(response); // Verifique a resposta no console do navegador

      if (response && response.success === true) { 
        location.reload(); // Recarrega a página para exibir a nova task
      } else {
        alert('Erro ao adicionar a tarefa: ' + (response.error || 'Desconhecido'));
      }
    }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
      console.error("Erro AJAX:", textStatus, errorThrown);
      alert('Erro ao conectar com o servidor!');
    });
  });
})