
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><strong>{$domain}</strong> SSH Control</h3>
  </div>
  <div class="panel-body">


  {if $output}

  {$output}

  {else}

    <div class="well">
      Please note:<br /><br />
      <ul>
        <li>This tool only works for Web Hosting.</li>
	<li>If you upgrade your account to a new package then SSH will get disabled. If that happens, you can use this tool to re-enable it.</li>
      </ul>
    </div>

    <form class="form-horizontal" method="post">
      <div class="form-group"> 
        <div class="col-sm-offset-2 col-sm-10">
		Clicking the button below will enable SSH access for the cPanel account with the primary domain {$domain}.<br /><br />
          <button name="submit" type="submit" class="btn btn-default">Enable SSH</button>
        </div>
      </div>
    </form>

  {/if}

  </div>
</div>
