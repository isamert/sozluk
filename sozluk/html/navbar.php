<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE; ?></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!--<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="#">Link</a></li>-->
      </ul>
      <form class="navbar-form navbar-left" role="search">
		<fieldset>
			<div class="form-group">
				<input type="text" class="form-control typeahead" name="query" id="query" placeholder="başlık ara...">              
			</div>
			<button type="submit" class="btn btn-primary">git</button>
		</fieldset>
      </form>
      <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">temalar şeysi <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#" class="theme-select">black</a></li>
            <li><a href="#" class="theme-select">cyborg</a></li>
            <li><a href="#" class="theme-select">darkly</a></li>
            <li><a href="#" class="theme-select">flat</a></li>
			<li><a href="#" class="theme-select">sandstone</a></li>
			<li><a href="#" class="theme-select">ubuntu</a></li>
          </ul>
        </li>
		<?php if(!Member::is_signed()): ?>
        <li><a href="index.php?signup">kayıt ol</a></li>
		<?php endif; ?>
      </ul>
    </div>
  </div>
</nav>