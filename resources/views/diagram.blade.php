<!DOCTYPE html>
<html>
<head>
<title>Flowchart</title>
<meta name="description" content="Interactive flowchart diagram implemented by GoJS in JavaScript for HTML." />
<!-- Copyright 1998-2015 by Northwoods Software Corporation. -->
<meta charset="UTF-8">
<script type="text/javascript" src="{{url('bower_components/jquery/dist/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{url('bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{url('gojs/go-debug.js')}}"></script>
<script src="{{url('app/diagram.js')}}"></script>

<link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}" />
</head>
<body onload="init()">
<div id="container">
  <div style="display: inline-block; width: 25%; height: 600px" id="myPalette"></div>

  <div style="display: inline-block; width: 70%; height: 600px" id="myDiagram"></div>

  <span>
    <textarea id="nodeDataArray" style="display: block;">[]</textarea>
    <textarea id="linkDataArray" style="display: block;">[]</textarea>
  </span>
</div>
</body>
</html>
