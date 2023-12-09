<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style>
      /* Add your custom CSS styles here */
    </style>
  </head>
  <body>
    <h2>New Answer Notification</h2>

    <p>Dear {{ $question->author }},</p>

    <p>A new answer has been posted for your question:</p>

    <h3>{{ $question->content }}</h3>

    <p>Answer:</p>

    <p>{{ $answer->content }}</p>

    <p>You can view the answer on the product page's Q&A section.</p>

    <a href="{{ "https://$shop->shop/products/$product->handle#qa-section-iframe" }}">Go to Q&A Section</a>

    <p>Thank you for using our Q&A platform!</p>

    <p>Sincerely,</p>
    <p>Q&A Team</p>
  </body>
</html>
