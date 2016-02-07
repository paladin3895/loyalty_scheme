<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Bootstrap 101 Template</title>

  <!-- Bootstrap -->
  <script type="text/javascript" src="{{url('bower_components/jquery/dist/jquery.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{url('bower_components/moment/min/moment.min.js')}}"></script> -->
  <!-- <script type="text/javascript" src="{{url('bower_components/moment/min/moment-with-locales.js')}}"></script> -->

  <script type="text/javascript" src="{{url('bootstrap/js/bootstrap.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{url('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script> -->

  <script src="{{url('react-0.14.0/build/react.js')}}"></script>
  <script src="{{url('react-0.14.0/build/react-dom.js')}}"></script>
  <script src="{{url('react-bootstrap/react-bootstrap.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>

  <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}" />
  <!-- <link rel="stylesheet" href="{{url('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" /> -->

  <link href="{{url('font-awesome-4.5.0/css/font-awesome.css')}}" rel="stylesheet">
  <link href="{{url('css/customization.css')}}" rel="stylesheet">

</head>
<body>
  <div id="container"></div>
  <script type="text/babel" src="{{url('app/app.jsx')}}"></script>

  <!-- <script type="text/javascript">
  $(function () {
    var locale = {locale: 'vi'};
    $('#date-from').datetimepicker(locale);
    $('#date-to').datetimepicker(locale);
  });
  </script> -->

</body>
</html>
