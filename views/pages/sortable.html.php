<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
  <style>
  /*
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; }
  html>body #sortable li { height: 1.5em; line-height: 1.2em; }
  .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
  */
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 40%; }
  #sortable li { margin: 0 5px 5px 5px; padding: 15px;max-height:180px;overflow:hidden}
  #sortable li img{max-width:480px;max-height:180px}
  #sortable li.title{font-weight:bold;font-size:1.8em}
  </style>
  <script>
  $(function() {
    $( "#sortable" ).sortable({
	  helper: "clone",
	  update: function() {
		var serial = $("#sortable").sortable('toArray');
		$("#log").html(serial.toString());
	  }
    });
    $( "#sortable" ).disableSelection();
  });
  </script>
 
<ul id="sortable">
  <li class="ui-state-default title" id="sort_1">Donec convallis lacus quis est ullamcorper</li>
  <li class="ui-state-default" id="sort_2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
  <li class="ui-state-default" id="sort_3">Sed iaculis accumsan massa a vulputate. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris ut tellus vel urna lobortis lacinia et et elit. Etiam ligula velit, faucibus vitae dictum eu, vulputate eu quam. Sed quis congue neque. Etiam ut malesuada justo. Donec nec mattis lorem. Fusce tempor, erat aliquet gravida pulvinar, ante nisi placerat tortor, eu sagittis est elit eu ipsum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</li>
  <li class="ui-state-default title" id="sort_4">Aliquam vel arcu eget dui pulvinar</li>
  <li class="ui-state-default" id="sort_5">Nunc a arcu lectus. Donec eget pellentesque quam. Nunc eu arcu mauris, at iaculis mi. Sed ut ipsum sed lorem semper porttitor. Maecenas lobortis orci ut augue tempus sed lobortis magna tempus. Nullam non adipiscing elit. Aliquam sollicitudin, dolor id sodales fermentum, turpis massa pretium diam, in euismod ante lorem nec mauris. Nullam tempus imperdiet dolor, eget eleifend libero varius ultrices. Proin sollicitudin neque quis tortor molestie eu dignissim ante pharetra. Proin sollicitudin iaculis turpis, vitae accumsan tortor ornare vitae. In luctus placerat feugiat. Aenean in nibh quis leo facilisis lacinia vitae ac augue.</li>
  <li class="ui-state-default" id="sort_6"><img src="http://i2.ytimg.com/vi/queUHPuFna0/mqdefault.jpg"></li>
  <li class="ui-state-default" id="sort_7"><a href="http://media.maginea.com/ld/products/00/00/73/43/LD0000734393_2.jpg" target="_blank"><img src="http://media.maginea.com/ld/products/00/00/73/43/LD0000734393_2.jpg" style="max-width:480px;max-height:180px"/></a></li>
</ul>

<div id="log"></div>