document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting
    
    
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var message = document.getElementById('message').value;
    
    
    if (name.trim() === '' || email.trim() === '' || message.trim() === '') {
      document.getElementById('status-message').innerHTML = 'Please fill in all fields.';
      return;
    }
    
    
    setTimeout(function() {
      document.getElementById('status-message').innerHTML = 'Message sent successfully!';
      document.getElementById('contact-form').reset();
    }, 2000);
  });
  