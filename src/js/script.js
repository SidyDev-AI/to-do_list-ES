$(document).ready(function() {
  $('.edit-btn').on('click', function() {
    var $task = $(this).closest('.task')
    $task.find('.progress').addClass('hidden')
    $task.find('.task-description').addClass('hidden')
    $task.find('.task-actions').addClass('hidden')
    $task.find('.edit-task').removeClass('hidden')
  })

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