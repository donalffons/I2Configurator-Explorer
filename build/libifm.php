<?php
/* =======================================================================
 * Improved File Manager
 * ---------------------
 * License: This project is provided under the terms of the MIT LICENSE
 * http://github.com/misterunknown/ifm/blob/master/LICENSE
 * =======================================================================
 *
 * main
*/

error_reporting( E_ALL );
ini_set( 'display_errors', 'OFF' );

class IFM {
	private $defaultconfig = array(
		// general config
		"auth" => 0,
		"auth_source" => 'inline;admin:$2y$10$0Bnm5L4wKFHRxJgNq.oZv.v7yXhkJZQvinJYR2p6X1zPvzyDRUVRC',
		"root_dir" => "WebGL Models/",
		"tmp_dir" => "",
		"defaulttimezone" => "Europe/Berlin",
		"forbiddenChars" => array(),
		"language" => "en",
		"selfoverwrite" => 0,

		// api controls
		"ajaxrequest" => 0,
		"chmod" => 1,
		"copymove" => 1,
		"createdir" => 1,
		"createfile" => 0,
		"edit" => 1,
		"delete" => 1,
		"download" => 1,
		"extract" => 0,
		"upload" => 1,
		"remoteupload" => 0,
		"rename" => 1,
		"zipnload" => 0,
		"createarchive" => 0,

		// gui controls
		"showlastmodified" => 0,
		"showfilesize" => 1,
		"showowner" => 0,
		"showgroup" => 0,
		"showpermissions" => 0,
		"showhtdocs" => 0,
		"showhiddenfiles" => 0,
		"showpath" => 0,
		"contextmenu" => 1,
		"disable_mime_detection" => 0
	);

	private $config = array();
	private $templates = array();
	private $i18n = array();
	public $mode = "";

	public function __construct( $config=array() ) {

		// load the default config
		$this->config = $this->defaultconfig;

		// load config from environment variables
		$this->config['auth'] =  getenv('IFM_AUTH') !== false ? intval( getenv('IFM_AUTH') ) : $this->config['auth'] ;
		$this->config['auth_source'] =  getenv('IFM_AUTH_SOURCE') !== false ? getenv('IFM_AUTH_SOURCE') : $this->config['auth_source'] ;
		$this->config['root_dir'] =  getenv('IFM_ROOT_DIR') !== false ? getenv('IFM_ROOT_DIR') : $this->config['root_dir'] ;
		$this->config['tmp_dir'] =  getenv('IFM_TMP_DIR') !== false ? getenv('IFM_TMP_DIR') : $this->config['tmp_dir'] ;
		$this->config['defaulttimezone'] =  getenv('IFM_DEFAULTTIMEZONE') !== false ? getenv('IFM_DEFAULTTIMEZONE') : $this->config['defaulttimezone'] ;
		$this->config['forbiddenChars'] =  getenv('IFM_FORBIDDENCHARS') !== false ? str_split( getenv('IFM_FORBIDDENCHARS') ) : $this->config['forbiddenChars'] ;
		$this->config['language'] =  getenv('IFM_LANGUAGE') !== false ? getenv('IFM_LANGUAGE') : $this->config['language'] ;
		$this->config['selfoverwrite'] =  getenv('IFM_SELFOVERWRITE') !== false ? getenv('IFM_SELFOVERWRITE') : $this->config['selfoverwrite'] ;
		$this->config['ajaxrequest'] =  getenv('IFM_API_AJAXREQUEST') !== false ? intval( getenv('IFM_API_AJAXREQUEST') ) : $this->config['ajaxrequest'] ;
		$this->config['chmod'] =  getenv('IFM_API_CHMOD') !== false ? intval( getenv('IFM_API_CHMOD') ) : $this->config['chmod'] ;
		$this->config['copymove'] =  getenv('IFM_API_COPYMOVE') !== false ? intval( getenv('IFM_API_COPYMOVE') ) : $this->config['copymove'] ;
		$this->config['createdir'] =  getenv('IFM_API_CREATEDIR') !== false ? intval( getenv('IFM_API_CREATEDIR') ) : $this->config['createdir'] ;
		$this->config['createfile'] =  getenv('IFM_API_CREATEFILE') !== false ? intval( getenv('IFM_API_CREATEFILE') ) : $this->config['createfile'] ;
		$this->config['edit'] =  getenv('IFM_API_EDIT') !== false ? intval( getenv('IFM_API_EDIT') ) : $this->config['edit'] ;
		$this->config['delete'] =  getenv('IFM_API_DELETE') !== false ? intval( getenv('IFM_API_DELETE') ) : $this->config['delete'] ;
		$this->config['download'] =  getenv('IFM_API_DOWNLOAD') !== false ? intval( getenv('IFM_API_DOWNLOAD') ) : $this->config['download'] ;
		$this->config['extract'] =  getenv('IFM_API_EXTRACT') !== false ? intval( getenv('IFM_API_EXTRACT') ) : $this->config['extract'] ;
		$this->config['upload'] =  getenv('IFM_API_UPLOAD') !== false ? intval( getenv('IFM_API_UPLOAD') ) : $this->config['upload'] ;
		$this->config['remoteupload'] =  getenv('IFM_API_REMOTEUPLOAD') !== false ? intval( getenv('IFM_API_REMOTEUPLOAD') ) : $this->config['remoteupload'] ;
		$this->config['rename'] =  getenv('IFM_API_RENAME') !== false ? intval( getenv('IFM_API_RENAME') ) : $this->config['rename'] ;
		$this->config['zipnload'] =  getenv('IFM_API_ZIPNLOAD') !== false ? intval( getenv('IFM_API_ZIPNLOAD') ) : $this->config['zipnload'] ;
		$this->config['createarchive'] =  getenv('IFM_API_CREATEARCHIVE') !== false ? intval( getenv('IFM_API_CREATEARCHIVE') ) : $this->config['createarchive'] ;
		$this->config['showlastmodified'] =  getenv('IFM_GUI_SHOWLASTMODIFIED') !== false ? intval( getenv('IFM_GUI_SHOWLASTMODIFIED') ) : $this->config['showlastmodified'] ;
		$this->config['showfilesize'] =  getenv('IFM_GUI_SHOWFILESIZE') !== false ? intval( getenv('IFM_GUI_SHOWFILESIZE') ) : $this->config['showfilesize'] ;
		$this->config['showowner'] =  getenv('IFM_GUI_SHOWOWNER') !== false ? intval( getenv('IFM_GUI_SHOWOWNER') ) : $this->config['showowner'] ;
		$this->config['showgroup'] =  getenv('IFM_GUI_SHOWGROUP') !== false ? intval( getenv('IFM_GUI_SHOWGROUP') ) : $this->config['showgroup'] ;
		$this->config['showpermissions'] =  getenv('IFM_GUI_SHOWPERMISSIONS') !== false ? intval( getenv('IFM_GUI_SHOWPERMISSIONS') ) : $this->config['showpermissions'] ;
		$this->config['showhtdocs'] =  getenv('IFM_GUI_SHOWHTDOCS') !== false ? intval( getenv('IFM_GUI_SHOWHTDOCS') ) : $this->config['showhtdocs'] ;
		$this->config['showhiddenfiles'] =  getenv('IFM_GUI_SHOWHIDDENFILES') !== false ? intval( getenv('IFM_GUI_SHOWHIDDENFILES') ) : $this->config['showhiddenfiles'] ;
		$this->config['showpath'] =  getenv('IFM_GUI_SHOWPATH') !== false ? intval( getenv('IFM_GUI_SHOWPATH') ) : $this->config['showpath'] ;
		$this->config['contextmenu'] =  getenv('IFM_GUI_CONTEXTMENU') !== false ? intval( getenv('IFM_GUI_CONTEXTMENU') ) : $this->config['contextmenu'] ;

		// optional settings
		if( getenv('IFM_SESSION_LIFETIME') !== false )
			$this->config['session_lifetime'] = getenv('IFM_SESSION_LIFETIME');
		if( getenv('IFM_FORCE_SESSION_LIFETIME') !== false )
			$this->config['session_lifetime'] = getenv('IFM_FORCE_SESSION_LIFETIME');

		// load config from passed array
		$this->config = array_merge( $this->config, $config );

		// get list of ace includes
		$this->config['ace_includes'] = <<<'f00bar'

f00bar;

		// templates
		$templates = array();
		$templates['app'] = <<<'f00bar'
		<nav class="navbar navbar-inverse
			{{^config.inline}}
			navbar-fixed-top
			{{/config.inline}}
		">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand">I2 Configurator</a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar">
						<span class="sr-only">{{i18n.toggle_nav}}</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar">
					<form class="navbar-form navbar-left">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon" id="currentDirLabel">{{i18n.path_content}} <span id="docroot">{{showpath}}</span></span>
								<input class="form-control" id="currentDir" aria-describedby="currentDirLabel" type="text">
							</div>
						</div>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<li><a id="refresh"><span title="{{i18n.refresh}}" class="icon icon-arrows-cw"></span> <span class="visible-xs">{{i18n.refresh}}</span></a></li>
						<li><a id="search"><span title="{{i18n.search}}" class="icon icon-search"></span> <span class="visible-xs">{{i18n.search}}</span></a></li>
						{{#config.upload}}
						<li><a id="upload"><span title="{{i18n.upload}}" class="icon icon-upload"></span> <span class="visible-xs">{{i18n.upload}}</span></a></li>
						{{/config.upload}}
						{{#config.createfile}}
						<li><a id="createFile"><span title="{{i18n.file_new}}" class="icon icon-doc-inv"></span> <span class="visible-xs">{{i18n.file_new}}</span></a></li>
						{{/config.createfile}}
						<!--{{#config.createdir}}
						<li><a id="createDir"><span title="{{i18n.folder_new}}" class="icon icon-folder"></span> <span class="visible-xs">{{i18n.folder_new}}</span></a></li>
						{{/config.createdir}}-->
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="icon icon-down-open"></span></a>
							<ul class="dropdown-menu" role="menu">
								{{#config.remoteupload}}
								<li><a id="buttonRemoteUpload"><span class="icon icon-upload-cloud"></span> {{i18n.upload_remote}}</a></li>
								{{/config.remoteupload}}
								{{#config.ajaxrequest}}
								<li><a id="buttonAjaxRequest"><span class="icon icon-link-ext"></span> {{i18n.ajax_request}}</a></li>
								{{/config.ajaxrequest}}
								{{#config.auth}}
								<li><a id="buttonLogout" href="?api=logout"><span class="icon icon-logout"></span> {{i18n.logout}}</a></li>
								{{/config.auth}}
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div id="filedropoverlay">
			<h1>{{i18n.upload_drop}}</h1>
		</div>
		<div class="container">
			<table id="filetable" class="table">
				<thead>
					<tr>
						<th class="th-meta hidden" data-visible="false"></th>
						<th class="th-filename">{{i18n.filename}}</th>
						{{#config.download}}
						<th class="th-download"><!-- column for download link --></th>
						{{/config.download}}
						{{#config.showlastmodified}}
						<th class="th-lastmod">{{i18n.last_modified}}</th>
						{{/config.showlastmodified}}
						{{#config.showfilesize}}
						<th class="th-size">{{i18n.size}}</th>
						{{/config.showfilesize}}
						{{#config.showpermissions}}
						<th class="th-permissions hidden-xs">{{i18n.permissions}}</th>
						{{/config.showpermissions}}
						{{#config.showowner}}
						<th class="th-owner hidden-xs hidden-sm">{{i18n.owner}}</th>
						{{/config.showowner}}
						{{#config.showgroup}}
						<th class="th-group hidden-xs hidden-sm hidden-md">{{i18n.group}}</th>
						{{/config.showgroup}}
						<th class="th-buttons buttons"><!-- column for buttons --></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="container" id="modeltoolscontainer">
				<button type="button" class="btn btn-default" id="buttonNewModel">
					<span class="icon icon-plus"></span>{{i18n.create_new_model}}
				</button>
			</div>
		</div>
		<div class="container" id="variantcontainer" style="display:none">
			<table id="varianttable" class="table">
				<thead>
					<tr>
						<th class="th-meta hidden" data-visible="false"></th>
						<th class="th-variantname">{{i18n.variantname}}</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<button type="button" class="btn btn-default" id="buttonNewVariant">
				<span class="icon icon-plus"></span>{{i18n.create_new_variant}}
			</button>
		</div>
		<hr />
		<div class="container">
			<div class="panel panel-default ifminfo"><div class="panel-body">I2 Configurator Explorer</div></div>
		</div>

f00bar;
		$templates['login'] = <<<'f00bar'
<style type="text/css">
body {
	padding-top: 40px;
	padding-bottom: 40px;
	background-color: #eee;
}

.form-signin {
	max-width: 330px;
	padding: 15px;
	margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
	margin-bottom: 10px;
}
.form-signin .checkbox {
	font-weight: normal;
}
.form-signin .form-control {
	position: relative;
	height: auto;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 10px;
	font-size: 16px;
}
.form-signin .form-control:focus {
	z-index: 2;
}
.form-signin input[type="email"] {
	margin-bottom: -1px;
	border-bottom-right-radius: 0;
	border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
	margin-bottom: 10px;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
}
</style>

<div class="container">
	<form action="" method="post" class="form-signin">
		<h2 class="form-signin-heading text-center">IFM {{i18n.login}}</h2>
		{{error}}
		<label for="inputEmail" class="sr-only">{{i18n.username}}</label>
		<input type="text" id="user" name="user" class="form-control" placeholder="{{i18n.username}}" required autofocus>
		<label for="inputPassword" class="sr-only">{{i18n.password}}</label>
		<input type="password" id="pass" name="pass" class="form-control" placeholder="{{i18n.password}}" required>
		<button class="btn btn-lg btn-primary btn-block" type="submit">{{i18n.login}}</button>
	</form>
</div> <!-- /container -->

f00bar;
		$templates['filetable'] = <<<'f00bar'
{{#items}}
<tr class="clickable-file-row {{rowclasses}}" {{{dragdrop}}} data-id="{{guid}}" data-filename="{{name}}" data-eaction="{{eaction}}">
	{{#fixtop}}
	<td data-order="{{fixtop}}"></td>
	{{/fixtop}}
	{{^fixtop}}
	<td data-order="0"></td>
	{{/fixtop}}
	<td>
		<a {{{href}}} tabindex="0" id="{{guid}}" class="ifmitem" {{{tooltip}}} data-type="{{type}}">
			<span class="{{icon}}"></span>
			{{linkname}}
		</a>
	</td>
	{{#config.download}}
	<td>
		<form id="d_{{guid}}" action="{{api}}" method="post">
			<input type="hidden" name="dir" value="{{download.currentDir}}">
			<input type="hidden" name="filename" value="{{download.name}}">
			<input type="hidden" name="api" value="{{download.action}}">
		</form>
		<a tabindex="0" name="start_download" data-guid="{{guid}}">
			<span class="{{download.icon}}"></span>
		</a>
	</td>
	{{/config.download}}
	{{#config.showlastmodified}}
	<td>{{lastmodified}}</td>
	{{/config.showlastmodified}}
	{{#config.showfilesize}}
	<td data-order="{{size_raw}}">{{size}}</td>
	{{/config.showfilesize}}
	{{#config.showpermissions}}
	<td class="hidden-xs td-permissions">
		<input type="text" size="11" name="newpermissions" class="form-control {{filepermmode}}" value="{{fileperms}}" data-filename="{{name}}" {{readonly}}>
	</td>
	{{/config.showpermissions}}
	{{#config.showowner}}
	<td class="hidden-xs hidden-sm">
		{{owner}}
	</td>
	{{/config.showowner}}
	{{#config.showgroup}}
	<td class="hidden-xs hidden-sm hidden-md">
		{{group}}
	</td>
	{{/config.showgroup}}
	<td>
		{{#button}}
		<a tabindex="0" name="do-{{action}}" data-id="{{guid}}">
			<span class="{{icon}}" title="{{title}}"</span>
		</a>
		{{/button}}
	</td>
</tr>
{{/items}}

f00bar;
		$templates['footer'] = <<<'f00bar'
<footer class="footer">
<div class="container">
	<div class="row">
		<div class="col-xs-1">
			<a name="showAll">{{i18n.tasks}} <span class="badge" name="taskCount">1</span></a>
		</div>
		<div id="waitqueue" class="col-xs-11">
		</div>
	</div> <!-- row -->
</div> <!-- container -->
</footer>

f00bar;
		$templates['task'] = <<<'f00bar'
<div id="{{id}}" class="panel panel-default">
<div class="panel-body">
	<div class="progress">
		<div class="progress-bar progress-bar-{{type}} progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemax="100" style="width:100%"></div>
		<span class="progbarlabel">{{name}}</span>
	</div>
</div>
</div>

f00bar;
		$templates['ajaxrequest'] = <<<'f00bar'
<form id="formAjaxRequest">
<div class="modal-body">
	<fieldset>
		<label>URL</label><br>
		<input class="form-control" type="text" id="ajaxurl" required><br>
		<label>{{i18n.data}}</label><br>
		<textarea class="form-control" id="ajaxdata"></textarea><br>
		<label>{{i18n.method}}</label><br>
		<input type="radio" name="arMethod" value="GET">GET</input><input type="radio" name="arMethod" value="POST" checked="checked">POST</input><br>
		<button type="button" class="btn btn-success" id="buttonRequest">{{i18n.request}}</button>
		<button type="button" class="btn btn-default" id="buttonClose">{{i18n.cancel}}</button><br>
		<label>{{i18n.response}}</label><br>
		<textarea class="form-control" id="ajaxresponse"></textarea>
	</fieldset>
</form>
</div>

f00bar;
		$templates['copymove'] = <<<'f00bar'
<form id="formCopyMove">
<fieldset>
	<div class="modal-body">
		<label>{{i18n.select_destination}}:</label>
		<div id="copyMoveTree"><div class="text-center"><span class="icon icon-spin5 animate-spin"></span></div></div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" id="copyButton">{{i18n.copy}}</button>
		<button type="button" class="btn btn-default" id="moveButton">{{i18n.move}}</button>
		<button type="button" class="btn btn-default" id="cancelButton">{{i18n.cancel}}</button>
	</div>
</fieldset>
</form>

f00bar;
		$templates['createdir'] = <<<'f00bar'
<form id="formCreateDir">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.directoryname}}:</label>
		<input class="form-control" type="text" name="dirname" value="" />
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonSave">{{i18n.save}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
		$templates['createarchive'] = <<<'f00bar'
<form id="formCreateArchive">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.archivename}}:</label>
		<input class="form-control" type="text" name="archivename" value="" />
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonSave">{{i18n.save}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
		$templates['deletefile'] = <<<'f00bar'
<form id="formDeleteFiles">
<div class="modal-body">
	{{#multiple}}
	<label>{{i18n.file_delete_confirm}} <code>{{count}}</code>?</label>
	{{/multiple}}
	{{^multiple}}
	<label>{{i18n.file_delete_confirm}} <code>{{filename}}</code>?</label>
	{{/multiple}}
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-danger" id="buttonYes">{{i18n.delete}}</button>
	<button type="button" class="btn btn-default" id="buttonNo">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
$templates['deletevariant'] = <<<'f00bar'
<form id="formDeleteVariants">
<div class="modal-body">
	{{#multiple}}
	<label>{{i18n.variant_delete_confirm}} <code>{{count}}</code>?</label>
	{{/multiple}}
	{{^multiple}}
	<label>{{i18n.variant_delete_confirm}} <code>{{variantname}}</code>?</label>
	{{/multiple}}
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-danger" id="buttonYes">{{i18n.delete}}</button>
	<button type="button" class="btn btn-default" id="buttonNo">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
		$templates['extractfile'] = <<<'f00bar'
<form id="formExtractFile">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.extract_filename}} {{filename}}:</label>
		<div class="input-group">
			<span class="input-group-addon"><input type="radio" name="extractTargetLocation" value="./" checked="checked"></span>
			<span class="form-control">./</span>
		</div>
		<div class="input-group">
			<span class="input-group-addon"><input type="radio" name="extractTargetLocation" value="./{{destination}}"></span>
			<span class="form-control">./{{destination}}</span>
		</div>
		<div class="input-group">
			<span class="input-group-addon"><input type="radio" name="extractTargetLocation" value="custom"></span>
			<input id="extractCustomLocation" type="text" class="form-control" placeholder="custom location" value="">
		</div>
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonExtract">{{i18n.extract}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
		$templates['file'] = <<<'f00bar'
<form id="formFile">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.filename}}:</label>
		<input type="text" class="form-control" name="filename" value="{{filename}}"><br>
		<div id="content" name="content"></div><br>
		<button type="button" class="btn btn-default" id="editoroptions">{{i18n.editor_options}}</button>
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" id="buttonSave" class="btn btn-default">{{i18n.save}}</button>
	<button type="button" id="buttonSaveNotClose" class="btn btn-default">{{i18n.save_wo_close}}</button>
	<button type="button" id="buttonClose" class="btn btn-default">{{i18n.close}}</button>
</div>
</form>

f00bar;
		$templates['file_editoroptions'] = <<<'f00bar'
<input type="checkbox" id="editor-wordwrap"
	{{#wordwrap}}
	checked="checked"
 	{{/wordwrap}}
> {{i18n.word_wrap}}</input><br>
<input type="checkbox" id="editor-softtabs"
	{{#softtabs}}
	checked="checked"
 	{{/softtabs}}
> {{i18n.soft_tabs}}</input>
<div class="input-group"><span class="input-group-addon">{{i18n.tab_size}}</span><input class="form-control" type="text" size="2" id="editor-tabsize" title="{{i18n.tab_size}}" value="{{tabsize}}"></div>
{{#ace_includes}}
<select class="form-control selectpicker" data-toggle="dropdown" data-live-search="true" data-size="15" id="editor-syntax">
	{{#modes}}
	<option value="ace/mode/{{.}}" {{{ace_mode_selected}}}>{{.}}</option>
	{{/modes}}
</select>
{{/ace_includes}}

f00bar;
		$templates['remoteupload'] = <<<'f00bar'
<form id="formRemoteUpload">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.upload_remote_url}}</label><br>
		<input class="form-control" type="text" id="url" name="url" required><br>
		<label>{{i18n.filename}}</label>
		<input class="form-control" type="text" id="filename" name="filename" required><br>
		<label>{{i18n.method}}</label>
		<input type="radio" name="method" value="curl" checked="checked">cURL<input type="radio" name="method" value="file">file</input><br>
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonUpload">{{i18n.upload}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
		$templates['renamefile'] = <<<'f00bar'
<form id="formRenameFile">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.rename_filename}} {{filename}}:</label>
		<input class="form-control" type="text" name="newname" value="" /><br>
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonRename">{{i18n.file_rename}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}} </button>
</div>
</form>

f00bar;
$templates['renamevariant'] = <<<'f00bar'
<form id="formRenameVariant">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.rename_variant}} {{variantname}}:</label>
		<input class="form-control" type="text" name="newname" value="{{variantname}}" /><br>
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonRename">{{i18n.file_rename}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}} </button>
</div>
</form>

f00bar;
$templates['newvariant'] = <<<'f00bar'
<form id="formNewVariant">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.new_variant_name}}:</label>
		<input class="form-control" type="text" name="newname" /><br>
	</fieldset>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="buttonRename">{{i18n.new}}</button>
	<button type="button" class="btn btn-default" id="buttonCancel">{{i18n.cancel}} </button>
</div>
</form>

f00bar;
		$templates['search'] = <<<'f00bar'
<form id="searchForm">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.search_pattern}}:</label>
		<input type="text" class="form-control" id="searchPattern" name="pattern" autocomplete="off" value="{{lastSearch}}"><br>
		<table id="searchResults" class="table">
		</table>
	</fieldset>
</div>
</form>

f00bar;
		$templates['searchresults'] = <<<'f00bar'
{{#items}}
<tr class="{{rowclasses}}" data-filename="{{name}}">
	<td>
		<a tabindex="0" id="{{guid}}" class="searchitem" {{{tooltip}}} data-type="{{type}}" data-folder="{{folder}}">
			<span class="{{icon}}"></span> {{linkname}} <span style="color:#999">({{folder}})</span>
		</a>
	</td>
</tr>
{{/items}}
{{^items}}
<tr>
	<td>
		No results found.
	</td>
</tr>
{{/items}}

f00bar;
		$templates['uploadfile'] = <<<'f00bar'
<form id="formUploadFile">
<div class="modal-body">
	<fieldset>
		<label>{{i18n.upload_file}}</label><br>
		<input class="file" type="file" name="files" multiple><br>
		<label>{{i18n.filename_new}}</label>
		<input class="form-control" type="text" name="newfilename"><br>
	</fieldset>
</div>
<div class="modal-footer">
	<button class="btn btn-default" id="buttonUpload">{{i18n.upload}}</button>
	<button class="btn btn-default" id="buttonCancel">{{i18n.cancel}}</button>
</div>
</form>

f00bar;
		$templates['varianttable'] = <<<'f00bar'
{{#items}}
<tr class="clickable-variant-row {{rowclasses}}" {{{dragdrop}}} data-id="{{guid}}" data-variantname="{{data.name}}" data-eaction="{{eaction}}">
	{{#fixtop}}
	<td data-order="{{fixtop}}"></td>
	{{/fixtop}}
	{{^fixtop}}
	<td data-order="0"></td>
	{{/fixtop}}
	<td>
		<a {{{href}}} tabindex="0" id="{{guid}}" class="ifmitem" {{{tooltip}}} data-type="{{type}}">
			<span class="{{icon}}"></span>
			{{data.name}}
		</a>
	</td>
</tr>
{{/items}}

f00bar;
		$this->templates = $templates;

		$i18n = array();
		$i18n["en"] = <<<'f00bar'
{
    "ajax_request": "AJAX request",
    "archivename": "Name of the archive",
    "archive_create_success": "Archive successfully created.",
    "archive_create_error": "Could not create archive.",
    "archive_invalid_format": "Invalid archive format given.",
    "cancel": "Cancel",
    "close": "Close",
    "copy": "Copy",
    "copy_error": "The following files could not be copied:",
    "copy_success": "File(s) copied successfully.",
    "create_archive": "Create archive",
    "data": "Data",
    "delete": "Delete",
    "directoryname": "Directory Name",
    "download": "Download",
    "edit": "Edit",
    "editor_options": "Editor Options",
    "error": "Error:",
    "extract": "extract",
    "extract_error": "Could not extract archive.",
    "extract_filename": "Extract file - ",
    "extract_success": "Archive extracted successfully.",
    "file_copy_to": "to",
    "file_delete_confirm": "Do you really want to delete the following file -",
    "file_delete_error": "File(s) could not be deleted.",
    "file_delete_success": "File(s) successfully deleted.",
    "file_display_error": "This file can not be displayed or edited.",
    "file_new": "New File",
    "file_load_error": "Content could not be loaded.",
    "file_open_error": "Could not open the file.",
    "file_new": "New file",
    "file_no_permission": "No permission to edit/create file.",
    "file_not_found": "File was not found or could not be opened.",
    "file_rename": "Rename File",
    "file_rename_error": "File could not be renamed: ",
    "file_rename_success": "File successfully renamed.",
    "file_save_error": "File could not be saved.",
    "file_save_success": "File was saved successfully.",
    "file_save_confirm": "Do you want to save the following file -",
    "file_save_error": "File could not be edited or created: ",
    "file_upload_error": "File could not be uploaded.",
    "file_upload_success": "File successfully uploaded.",
    "filename": "Filename",
    "filename_new": "New Filename",
    "filename_slashes": "The filename must not contain slashes.",
    "filter": "Filter",
    "folder_create_error": "Directory could not be created: ",
    "folder_create_success": "Directory sucessfully created.",
    "folder_new": "New Folder",
    "folder_not_found": "The directory could not be found.",
    "folder_tree_load_error": "Error while fetching the folder tree.",
    "footer": "IFM - improved file manager | ifm.php hidden |",
    "general_error": "General error occured: No or broken response.",
    "github": "Visit the project on GitHub",
    "group": "Group",
    "invalid_action": "Invalid action given.",
    "invalid_archive_format": "Invalid archive format given. Possible formats are zip, tar, tar.gz or tar.bz2.",
    "invalid_data": "Invalid data from server.",
    "invalid_dir": "Invalid directory given.",
    "invalid_filename": "Invalid filename given.",
    "invalid_params": "Invalid parameter given.",
    "invalid_url": "Invalid URL given.",
    "json_encode_error": "Could not format the response as JSON:",
    "last_modified": "Last Modified",
    "load_config_error": "Could not load configuration.",
    "load_template_error": "Could not load templates.",
    "load_text_error": "Could not load texts.",
    "login": "Login",
    "login_failed": "Login failed.",
    "logout": "Log Off",
    "method": "Method",
    "move": "Move",
    "move_error": "The following files could not be moved:",
    "move_success": "File(s) successfully moved.",
    "nopermissions": "You don't have the permission to do that.",
    "options": "Options",
    "owner": "Owner",
    "password": "Password",
    "path_content": "Content of",
    "pattern_error_slashes": "Pattern must not contain slashes.",
    "permission_change_error": "Permissions could not be changed: ",
    "permission_change_success": "Permissions successfully changed.",
    "permission_parse_error": "Permissions could not be parsed correctly.",
    "permissions": "Permissions",
    "refresh": "Refresh",
    "rename": "Rename",
    "rename_filename": "Rename file -",
    "request": "Request",
    "response": "Response",
    "save": "Save",
    "save_wo_close": "Save w/o Close",
    "search": "Search",
    "search_pattern": "Pattern",
    "select_destination": "Select Destination",
    "size": "Size",
    "soft_tabs": "Soft Tabs",
    "tab_size": "Tab Size",
    "tasks": "Tasks",
	"remaining_tasks": "There are remaining tasks. Do you really want to reload?",
    "toggle_nav": "Toggle navigation",
    "upload": "Upload",
    "upload_drop": "Drop files to upload",
    "upload_file": "Upload File",
    "upload_remote": "Remote Upload",
    "upload_remote_url": "Remote Upload URL",
    "username": "username",
    "word_wrap": "Word Wrap",
    "variantname": "Variant Name",
    "duplicate": "duplicate",
    "view": "view",
    "rename_variant": "Rename Variant",
    "variant_rename": "Rename Variant",
    "new": "New",
    "new_variant_name": "New Variant Name",
    "variant_delete_confirm": "Do you really want to delete the following variant -",
    "duplicate_variant": "Duplicate Variant",
    "copy_of": "Copy of",
    "create_new_variant": "Create New Variant",
    "create_new_model": "Create New Model",
    "filetype_not_allowed": "File type not allowed",
    "model_delete_success": "Successfully deleted model"
}

f00bar;
$i18n["en"] = json_decode( $i18n["en"], true );

		$this->i18n = $i18n;
		
		if( in_array( $this->config['language'], array_keys( $this->i18n ) ) )
			$this->l = $this->i18n[$this->config['language']];
		else
			$this->l = $this->i18n[0];
	}

	/**
	 * This function contains the client-side application
	 */
	public function getApplication() {
		$this->getHTMLHeader();
		print '<div id="ifm"></div>';
		$this->getJS();
		print '<script>var ifm = new IFM(); ifm.init( "ifm" );</script>';
		$this->getHTMLFooter();
	}

	public function getInlineApplication() {
		$this->getCSS();
		print '<div id="ifm"></div>';
		$this->getJS();
	}

	public function getCSS() {
		print '
			<style type="text/css">';?> /*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 *//*! normalize.css v3.0.3 | MIT License | github.com/necolas/normalize.css */html{font-family:sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background-color:transparent}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:700}dfn{font-style:italic}h1{margin:.67em 0;font-size:2em}mark{color:#000;background:#ff0}small{font-size:80%}sub,sup{position:relative;font-size:75%;line-height:0;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{height:0;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}button,input,optgroup,select,textarea{margin:0;font:inherit;color:inherit}button{overflow:visible}button,select{text-transform:none}button,html input[type=button],input[type=reset],input[type=submit]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{padding:0;border:0}input{line-height:normal}input[type=checkbox],input[type=radio]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:0}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{height:auto}input[type=search]{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;-webkit-appearance:textfield}input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration{-webkit-appearance:none}fieldset{padding:.35em .625em .75em;margin:0 2px;border:1px solid silver}legend{padding:0;border:0}textarea{overflow:auto}optgroup{font-weight:700}table{border-spacing:0;border-collapse:collapse}td,th{padding:0}/*! Source: https://github.com/h5bp/html5-boilerplate/blob/master/src/css/main.css */@media print{*,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}a[href^="javascript:"]:after,a[href^="#"]:after{content:""}blockquote,pre{border:1px solid #999;page-break-inside:avoid}thead{display:table-header-group}img,tr{page-break-inside:avoid}img{max-width:100%!important}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}.navbar{display:none}.btn>.caret,.dropup>.btn>.caret{border-top-color:#000!important}.label{border:1px solid #000}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}.table-bordered td,.table-bordered th{border:1px solid #ddd!important}}@font-face{font-family:'Glyphicons Halflings';src:url(../fonts/glyphicons-halflings-regular.eot);src:url(../fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'),url(../fonts/glyphicons-halflings-regular.woff2) format('woff2'),url(../fonts/glyphicons-halflings-regular.woff) format('woff'),url(../fonts/glyphicons-halflings-regular.ttf) format('truetype'),url(../fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format('svg')}.glyphicon{position:relative;top:1px;display:inline-block;font-family:'Glyphicons Halflings';font-style:normal;font-weight:400;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.glyphicon-asterisk:before{content:"\002a"}.glyphicon-plus:before{content:"\002b"}.glyphicon-eur:before,.glyphicon-euro:before{content:"\20ac"}.glyphicon-minus:before{content:"\2212"}.glyphicon-cloud:before{content:"\2601"}.glyphicon-envelope:before{content:"\2709"}.glyphicon-pencil:before{content:"\270f"}.glyphicon-glass:before{content:"\e001"}.glyphicon-music:before{content:"\e002"}.glyphicon-search:before{content:"\e003"}.glyphicon-heart:before{content:"\e005"}.glyphicon-star:before{content:"\e006"}.glyphicon-star-empty:before{content:"\e007"}.glyphicon-user:before{content:"\e008"}.glyphicon-film:before{content:"\e009"}.glyphicon-th-large:before{content:"\e010"}.glyphicon-th:before{content:"\e011"}.glyphicon-th-list:before{content:"\e012"}.glyphicon-ok:before{content:"\e013"}.glyphicon-remove:before{content:"\e014"}.glyphicon-zoom-in:before{content:"\e015"}.glyphicon-zoom-out:before{content:"\e016"}.glyphicon-off:before{content:"\e017"}.glyphicon-signal:before{content:"\e018"}.glyphicon-cog:before{content:"\e019"}.glyphicon-trash:before{content:"\e020"}.glyphicon-home:before{content:"\e021"}.glyphicon-file:before{content:"\e022"}.glyphicon-time:before{content:"\e023"}.glyphicon-road:before{content:"\e024"}.glyphicon-download-alt:before{content:"\e025"}.glyphicon-download:before{content:"\e026"}.glyphicon-upload:before{content:"\e027"}.glyphicon-inbox:before{content:"\e028"}.glyphicon-play-circle:before{content:"\e029"}.glyphicon-repeat:before{content:"\e030"}.glyphicon-refresh:before{content:"\e031"}.glyphicon-list-alt:before{content:"\e032"}.glyphicon-lock:before{content:"\e033"}.glyphicon-flag:before{content:"\e034"}.glyphicon-headphones:before{content:"\e035"}.glyphicon-volume-off:before{content:"\e036"}.glyphicon-volume-down:before{content:"\e037"}.glyphicon-volume-up:before{content:"\e038"}.glyphicon-qrcode:before{content:"\e039"}.glyphicon-barcode:before{content:"\e040"}.glyphicon-tag:before{content:"\e041"}.glyphicon-tags:before{content:"\e042"}.glyphicon-book:before{content:"\e043"}.glyphicon-bookmark:before{content:"\e044"}.glyphicon-print:before{content:"\e045"}.glyphicon-camera:before{content:"\e046"}.glyphicon-font:before{content:"\e047"}.glyphicon-bold:before{content:"\e048"}.glyphicon-italic:before{content:"\e049"}.glyphicon-text-height:before{content:"\e050"}.glyphicon-text-width:before{content:"\e051"}.glyphicon-align-left:before{content:"\e052"}.glyphicon-align-center:before{content:"\e053"}.glyphicon-align-right:before{content:"\e054"}.glyphicon-align-justify:before{content:"\e055"}.glyphicon-list:before{content:"\e056"}.glyphicon-indent-left:before{content:"\e057"}.glyphicon-indent-right:before{content:"\e058"}.glyphicon-facetime-video:before{content:"\e059"}.glyphicon-picture:before{content:"\e060"}.glyphicon-map-marker:before{content:"\e062"}.glyphicon-adjust:before{content:"\e063"}.glyphicon-tint:before{content:"\e064"}.glyphicon-edit:before{content:"\e065"}.glyphicon-share:before{content:"\e066"}.glyphicon-check:before{content:"\e067"}.glyphicon-move:before{content:"\e068"}.glyphicon-step-backward:before{content:"\e069"}.glyphicon-fast-backward:before{content:"\e070"}.glyphicon-backward:before{content:"\e071"}.glyphicon-play:before{content:"\e072"}.glyphicon-pause:before{content:"\e073"}.glyphicon-stop:before{content:"\e074"}.glyphicon-forward:before{content:"\e075"}.glyphicon-fast-forward:before{content:"\e076"}.glyphicon-step-forward:before{content:"\e077"}.glyphicon-eject:before{content:"\e078"}.glyphicon-chevron-left:before{content:"\e079"}.glyphicon-chevron-right:before{content:"\e080"}.glyphicon-plus-sign:before{content:"\e081"}.glyphicon-minus-sign:before{content:"\e082"}.glyphicon-remove-sign:before{content:"\e083"}.glyphicon-ok-sign:before{content:"\e084"}.glyphicon-question-sign:before{content:"\e085"}.glyphicon-info-sign:before{content:"\e086"}.glyphicon-screenshot:before{content:"\e087"}.glyphicon-remove-circle:before{content:"\e088"}.glyphicon-ok-circle:before{content:"\e089"}.glyphicon-ban-circle:before{content:"\e090"}.glyphicon-arrow-left:before{content:"\e091"}.glyphicon-arrow-right:before{content:"\e092"}.glyphicon-arrow-up:before{content:"\e093"}.glyphicon-arrow-down:before{content:"\e094"}.glyphicon-share-alt:before{content:"\e095"}.glyphicon-resize-full:before{content:"\e096"}.glyphicon-resize-small:before{content:"\e097"}.glyphicon-exclamation-sign:before{content:"\e101"}.glyphicon-gift:before{content:"\e102"}.glyphicon-leaf:before{content:"\e103"}.glyphicon-fire:before{content:"\e104"}.glyphicon-eye-open:before{content:"\e105"}.glyphicon-eye-close:before{content:"\e106"}.glyphicon-warning-sign:before{content:"\e107"}.glyphicon-plane:before{content:"\e108"}.glyphicon-calendar:before{content:"\e109"}.glyphicon-random:before{content:"\e110"}.glyphicon-comment:before{content:"\e111"}.glyphicon-magnet:before{content:"\e112"}.glyphicon-chevron-up:before{content:"\e113"}.glyphicon-chevron-down:before{content:"\e114"}.glyphicon-retweet:before{content:"\e115"}.glyphicon-shopping-cart:before{content:"\e116"}.glyphicon-folder-close:before{content:"\e117"}.glyphicon-folder-open:before{content:"\e118"}.glyphicon-resize-vertical:before{content:"\e119"}.glyphicon-resize-horizontal:before{content:"\e120"}.glyphicon-hdd:before{content:"\e121"}.glyphicon-bullhorn:before{content:"\e122"}.glyphicon-bell:before{content:"\e123"}.glyphicon-certificate:before{content:"\e124"}.glyphicon-thumbs-up:before{content:"\e125"}.glyphicon-thumbs-down:before{content:"\e126"}.glyphicon-hand-right:before{content:"\e127"}.glyphicon-hand-left:before{content:"\e128"}.glyphicon-hand-up:before{content:"\e129"}.glyphicon-hand-down:before{content:"\e130"}.glyphicon-circle-arrow-right:before{content:"\e131"}.glyphicon-circle-arrow-left:before{content:"\e132"}.glyphicon-circle-arrow-up:before{content:"\e133"}.glyphicon-circle-arrow-down:before{content:"\e134"}.glyphicon-globe:before{content:"\e135"}.glyphicon-wrench:before{content:"\e136"}.glyphicon-tasks:before{content:"\e137"}.glyphicon-filter:before{content:"\e138"}.glyphicon-briefcase:before{content:"\e139"}.glyphicon-fullscreen:before{content:"\e140"}.glyphicon-dashboard:before{content:"\e141"}.glyphicon-paperclip:before{content:"\e142"}.glyphicon-heart-empty:before{content:"\e143"}.glyphicon-link:before{content:"\e144"}.glyphicon-phone:before{content:"\e145"}.glyphicon-pushpin:before{content:"\e146"}.glyphicon-usd:before{content:"\e148"}.glyphicon-gbp:before{content:"\e149"}.glyphicon-sort:before{content:"\e150"}.glyphicon-sort-by-alphabet:before{content:"\e151"}.glyphicon-sort-by-alphabet-alt:before{content:"\e152"}.glyphicon-sort-by-order:before{content:"\e153"}.glyphicon-sort-by-order-alt:before{content:"\e154"}.glyphicon-sort-by-attributes:before{content:"\e155"}.glyphicon-sort-by-attributes-alt:before{content:"\e156"}.glyphicon-unchecked:before{content:"\e157"}.glyphicon-expand:before{content:"\e158"}.glyphicon-collapse-down:before{content:"\e159"}.glyphicon-collapse-up:before{content:"\e160"}.glyphicon-log-in:before{content:"\e161"}.glyphicon-flash:before{content:"\e162"}.glyphicon-log-out:before{content:"\e163"}.glyphicon-new-window:before{content:"\e164"}.glyphicon-record:before{content:"\e165"}.glyphicon-save:before{content:"\e166"}.glyphicon-open:before{content:"\e167"}.glyphicon-saved:before{content:"\e168"}.glyphicon-import:before{content:"\e169"}.glyphicon-export:before{content:"\e170"}.glyphicon-send:before{content:"\e171"}.glyphicon-floppy-disk:before{content:"\e172"}.glyphicon-floppy-saved:before{content:"\e173"}.glyphicon-floppy-remove:before{content:"\e174"}.glyphicon-floppy-save:before{content:"\e175"}.glyphicon-floppy-open:before{content:"\e176"}.glyphicon-credit-card:before{content:"\e177"}.glyphicon-transfer:before{content:"\e178"}.glyphicon-cutlery:before{content:"\e179"}.glyphicon-header:before{content:"\e180"}.glyphicon-compressed:before{content:"\e181"}.glyphicon-earphone:before{content:"\e182"}.glyphicon-phone-alt:before{content:"\e183"}.glyphicon-tower:before{content:"\e184"}.glyphicon-stats:before{content:"\e185"}.glyphicon-sd-video:before{content:"\e186"}.glyphicon-hd-video:before{content:"\e187"}.glyphicon-subtitles:before{content:"\e188"}.glyphicon-sound-stereo:before{content:"\e189"}.glyphicon-sound-dolby:before{content:"\e190"}.glyphicon-sound-5-1:before{content:"\e191"}.glyphicon-sound-6-1:before{content:"\e192"}.glyphicon-sound-7-1:before{content:"\e193"}.glyphicon-copyright-mark:before{content:"\e194"}.glyphicon-registration-mark:before{content:"\e195"}.glyphicon-cloud-download:before{content:"\e197"}.glyphicon-cloud-upload:before{content:"\e198"}.glyphicon-tree-conifer:before{content:"\e199"}.glyphicon-tree-deciduous:before{content:"\e200"}.glyphicon-cd:before{content:"\e201"}.glyphicon-save-file:before{content:"\e202"}.glyphicon-open-file:before{content:"\e203"}.glyphicon-level-up:before{content:"\e204"}.glyphicon-copy:before{content:"\e205"}.glyphicon-paste:before{content:"\e206"}.glyphicon-alert:before{content:"\e209"}.glyphicon-equalizer:before{content:"\e210"}.glyphicon-king:before{content:"\e211"}.glyphicon-queen:before{content:"\e212"}.glyphicon-pawn:before{content:"\e213"}.glyphicon-bishop:before{content:"\e214"}.glyphicon-knight:before{content:"\e215"}.glyphicon-baby-formula:before{content:"\e216"}.glyphicon-tent:before{content:"\26fa"}.glyphicon-blackboard:before{content:"\e218"}.glyphicon-bed:before{content:"\e219"}.glyphicon-apple:before{content:"\f8ff"}.glyphicon-erase:before{content:"\e221"}.glyphicon-hourglass:before{content:"\231b"}.glyphicon-lamp:before{content:"\e223"}.glyphicon-duplicate:before{content:"\e224"}.glyphicon-piggy-bank:before{content:"\e225"}.glyphicon-scissors:before{content:"\e226"}.glyphicon-bitcoin:before{content:"\e227"}.glyphicon-btc:before{content:"\e227"}.glyphicon-xbt:before{content:"\e227"}.glyphicon-yen:before{content:"\00a5"}.glyphicon-jpy:before{content:"\00a5"}.glyphicon-ruble:before{content:"\20bd"}.glyphicon-rub:before{content:"\20bd"}.glyphicon-scale:before{content:"\e230"}.glyphicon-ice-lolly:before{content:"\e231"}.glyphicon-ice-lolly-tasted:before{content:"\e232"}.glyphicon-education:before{content:"\e233"}.glyphicon-option-horizontal:before{content:"\e234"}.glyphicon-option-vertical:before{content:"\e235"}.glyphicon-menu-hamburger:before{content:"\e236"}.glyphicon-modal-window:before{content:"\e237"}.glyphicon-oil:before{content:"\e238"}.glyphicon-grain:before{content:"\e239"}.glyphicon-sunglasses:before{content:"\e240"}.glyphicon-text-size:before{content:"\e241"}.glyphicon-text-color:before{content:"\e242"}.glyphicon-text-background:before{content:"\e243"}.glyphicon-object-align-top:before{content:"\e244"}.glyphicon-object-align-bottom:before{content:"\e245"}.glyphicon-object-align-horizontal:before{content:"\e246"}.glyphicon-object-align-left:before{content:"\e247"}.glyphicon-object-align-vertical:before{content:"\e248"}.glyphicon-object-align-right:before{content:"\e249"}.glyphicon-triangle-right:before{content:"\e250"}.glyphicon-triangle-left:before{content:"\e251"}.glyphicon-triangle-bottom:before{content:"\e252"}.glyphicon-triangle-top:before{content:"\e253"}.glyphicon-console:before{content:"\e254"}.glyphicon-superscript:before{content:"\e255"}.glyphicon-subscript:before{content:"\e256"}.glyphicon-menu-left:before{content:"\e257"}.glyphicon-menu-right:before{content:"\e258"}.glyphicon-menu-down:before{content:"\e259"}.glyphicon-menu-up:before{content:"\e260"}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{font-size:10px;-webkit-tap-highlight-color:rgba(0,0,0,0)}body{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;line-height:1.42857143;color:#333;background-color:#fff}button,input,select,textarea{font-family:inherit;font-size:inherit;line-height:inherit}a{color:#337ab7;text-decoration:none}a:focus,a:hover{color:#23527c;text-decoration:underline}a:focus{outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}figure{margin:0}img{vertical-align:middle}.carousel-inner>.item>a>img,.carousel-inner>.item>img,.img-responsive,.thumbnail a>img,.thumbnail>img{display:block;max-width:100%;height:auto}.img-rounded{border-radius:6px}.img-thumbnail{display:inline-block;max-width:100%;height:auto;padding:4px;line-height:1.42857143;background-color:#fff;border:1px solid #ddd;border-radius:4px;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out}.img-circle{border-radius:50%}hr{margin-top:20px;margin-bottom:20px;border:0;border-top:1px solid #eee}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0}.sr-only-focusable:active,.sr-only-focusable:focus{position:static;width:auto;height:auto;margin:0;overflow:visible;clip:auto}[role=button]{cursor:pointer}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{font-family:inherit;font-weight:500;line-height:1.1;color:inherit}.h1 .small,.h1 small,.h2 .small,.h2 small,.h3 .small,.h3 small,.h4 .small,.h4 small,.h5 .small,.h5 small,.h6 .small,.h6 small,h1 .small,h1 small,h2 .small,h2 small,h3 .small,h3 small,h4 .small,h4 small,h5 .small,h5 small,h6 .small,h6 small{font-weight:400;line-height:1;color:#777}.h1,.h2,.h3,h1,h2,h3{margin-top:20px;margin-bottom:10px}.h1 .small,.h1 small,.h2 .small,.h2 small,.h3 .small,.h3 small,h1 .small,h1 small,h2 .small,h2 small,h3 .small,h3 small{font-size:65%}.h4,.h5,.h6,h4,h5,h6{margin-top:10px;margin-bottom:10px}.h4 .small,.h4 small,.h5 .small,.h5 small,.h6 .small,.h6 small,h4 .small,h4 small,h5 .small,h5 small,h6 .small,h6 small{font-size:75%}.h1,h1{font-size:36px}.h2,h2{font-size:30px}.h3,h3{font-size:24px}.h4,h4{font-size:18px}.h5,h5{font-size:14px}.h6,h6{font-size:12px}p{margin:0 0 10px}.lead{margin-bottom:20px;font-size:16px;font-weight:300;line-height:1.4}@media (min-width:768px){.lead{font-size:21px}}.small,small{font-size:85%}.mark,mark{padding:.2em;background-color:#fcf8e3}.text-left{text-align:left}.text-right{text-align:right}.text-center{text-align:center}.text-justify{text-align:justify}.text-nowrap{white-space:nowrap}.text-lowercase{text-transform:lowercase}.text-uppercase{text-transform:uppercase}.text-capitalize{text-transform:capitalize}.text-muted{color:#777}.text-primary{color:#337ab7}a.text-primary:focus,a.text-primary:hover{color:#286090}.text-success{color:#3c763d}a.text-success:focus,a.text-success:hover{color:#2b542c}.text-info{color:#31708f}a.text-info:focus,a.text-info:hover{color:#245269}.text-warning{color:#8a6d3b}a.text-warning:focus,a.text-warning:hover{color:#66512c}.text-danger{color:#a94442}a.text-danger:focus,a.text-danger:hover{color:#843534}.bg-primary{color:#fff;background-color:#337ab7}a.bg-primary:focus,a.bg-primary:hover{background-color:#286090}.bg-success{background-color:#dff0d8}a.bg-success:focus,a.bg-success:hover{background-color:#c1e2b3}.bg-info{background-color:#d9edf7}a.bg-info:focus,a.bg-info:hover{background-color:#afd9ee}.bg-warning{background-color:#fcf8e3}a.bg-warning:focus,a.bg-warning:hover{background-color:#f7ecb5}.bg-danger{background-color:#f2dede}a.bg-danger:focus,a.bg-danger:hover{background-color:#e4b9b9}.page-header{padding-bottom:9px;margin:40px 0 20px;border-bottom:1px solid #eee}ol,ul{margin-top:0;margin-bottom:10px}ol ol,ol ul,ul ol,ul ul{margin-bottom:0}.list-unstyled{padding-left:0;list-style:none}.list-inline{padding-left:0;margin-left:-5px;list-style:none}.list-inline>li{display:inline-block;padding-right:5px;padding-left:5px}dl{margin-top:0;margin-bottom:20px}dd,dt{line-height:1.42857143}dt{font-weight:700}dd{margin-left:0}@media (min-width:768px){.dl-horizontal dt{float:left;width:160px;overflow:hidden;clear:left;text-align:right;text-overflow:ellipsis;white-space:nowrap}.dl-horizontal dd{margin-left:180px}}abbr[data-original-title],abbr[title]{cursor:help;border-bottom:1px dotted #777}.initialism{font-size:90%;text-transform:uppercase}blockquote{padding:10px 20px;margin:0 0 20px;font-size:17.5px;border-left:5px solid #eee}blockquote ol:last-child,blockquote p:last-child,blockquote ul:last-child{margin-bottom:0}blockquote .small,blockquote footer,blockquote small{display:block;font-size:80%;line-height:1.42857143;color:#777}blockquote .small:before,blockquote footer:before,blockquote small:before{content:'\2014 \00A0'}.blockquote-reverse,blockquote.pull-right{padding-right:15px;padding-left:0;text-align:right;border-right:5px solid #eee;border-left:0}.blockquote-reverse .small:before,.blockquote-reverse footer:before,.blockquote-reverse small:before,blockquote.pull-right .small:before,blockquote.pull-right footer:before,blockquote.pull-right small:before{content:''}.blockquote-reverse .small:after,.blockquote-reverse footer:after,.blockquote-reverse small:after,blockquote.pull-right .small:after,blockquote.pull-right footer:after,blockquote.pull-right small:after{content:'\00A0 \2014'}address{margin-bottom:20px;font-style:normal;line-height:1.42857143}code,kbd,pre,samp{font-family:Menlo,Monaco,Consolas,"Courier New",monospace}code{padding:2px 4px;font-size:90%;color:#c7254e;background-color:#f9f2f4;border-radius:4px}kbd{padding:2px 4px;font-size:90%;color:#fff;background-color:#333;border-radius:3px;-webkit-box-shadow:inset 0 -1px 0 rgba(0,0,0,.25);box-shadow:inset 0 -1px 0 rgba(0,0,0,.25)}kbd kbd{padding:0;font-size:100%;font-weight:700;-webkit-box-shadow:none;box-shadow:none}pre{display:block;padding:9.5px;margin:0 0 10px;font-size:13px;line-height:1.42857143;color:#333;word-break:break-all;word-wrap:break-word;background-color:#f5f5f5;border:1px solid #ccc;border-radius:4px}pre code{padding:0;font-size:inherit;color:inherit;white-space:pre-wrap;background-color:transparent;border-radius:0}.pre-scrollable{max-height:340px;overflow-y:scroll}.container{padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}@media (min-width:768px){.container{width:750px}}@media (min-width:992px){.container{width:970px}}@media (min-width:1200px){.container{width:1170px}}.container-fluid{padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}.row{margin-right:-15px;margin-left:-15px}.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{float:left}.col-xs-12{width:100%}.col-xs-11{width:91.66666667%}.col-xs-10{width:83.33333333%}.col-xs-9{width:75%}.col-xs-8{width:66.66666667%}.col-xs-7{width:58.33333333%}.col-xs-6{width:50%}.col-xs-5{width:41.66666667%}.col-xs-4{width:33.33333333%}.col-xs-3{width:25%}.col-xs-2{width:16.66666667%}.col-xs-1{width:8.33333333%}.col-xs-pull-12{right:100%}.col-xs-pull-11{right:91.66666667%}.col-xs-pull-10{right:83.33333333%}.col-xs-pull-9{right:75%}.col-xs-pull-8{right:66.66666667%}.col-xs-pull-7{right:58.33333333%}.col-xs-pull-6{right:50%}.col-xs-pull-5{right:41.66666667%}.col-xs-pull-4{right:33.33333333%}.col-xs-pull-3{right:25%}.col-xs-pull-2{right:16.66666667%}.col-xs-pull-1{right:8.33333333%}.col-xs-pull-0{right:auto}.col-xs-push-12{left:100%}.col-xs-push-11{left:91.66666667%}.col-xs-push-10{left:83.33333333%}.col-xs-push-9{left:75%}.col-xs-push-8{left:66.66666667%}.col-xs-push-7{left:58.33333333%}.col-xs-push-6{left:50%}.col-xs-push-5{left:41.66666667%}.col-xs-push-4{left:33.33333333%}.col-xs-push-3{left:25%}.col-xs-push-2{left:16.66666667%}.col-xs-push-1{left:8.33333333%}.col-xs-push-0{left:auto}.col-xs-offset-12{margin-left:100%}.col-xs-offset-11{margin-left:91.66666667%}.col-xs-offset-10{margin-left:83.33333333%}.col-xs-offset-9{margin-left:75%}.col-xs-offset-8{margin-left:66.66666667%}.col-xs-offset-7{margin-left:58.33333333%}.col-xs-offset-6{margin-left:50%}.col-xs-offset-5{margin-left:41.66666667%}.col-xs-offset-4{margin-left:33.33333333%}.col-xs-offset-3{margin-left:25%}.col-xs-offset-2{margin-left:16.66666667%}.col-xs-offset-1{margin-left:8.33333333%}.col-xs-offset-0{margin-left:0}@media (min-width:768px){.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9{float:left}.col-sm-12{width:100%}.col-sm-11{width:91.66666667%}.col-sm-10{width:83.33333333%}.col-sm-9{width:75%}.col-sm-8{width:66.66666667%}.col-sm-7{width:58.33333333%}.col-sm-6{width:50%}.col-sm-5{width:41.66666667%}.col-sm-4{width:33.33333333%}.col-sm-3{width:25%}.col-sm-2{width:16.66666667%}.col-sm-1{width:8.33333333%}.col-sm-pull-12{right:100%}.col-sm-pull-11{right:91.66666667%}.col-sm-pull-10{right:83.33333333%}.col-sm-pull-9{right:75%}.col-sm-pull-8{right:66.66666667%}.col-sm-pull-7{right:58.33333333%}.col-sm-pull-6{right:50%}.col-sm-pull-5{right:41.66666667%}.col-sm-pull-4{right:33.33333333%}.col-sm-pull-3{right:25%}.col-sm-pull-2{right:16.66666667%}.col-sm-pull-1{right:8.33333333%}.col-sm-pull-0{right:auto}.col-sm-push-12{left:100%}.col-sm-push-11{left:91.66666667%}.col-sm-push-10{left:83.33333333%}.col-sm-push-9{left:75%}.col-sm-push-8{left:66.66666667%}.col-sm-push-7{left:58.33333333%}.col-sm-push-6{left:50%}.col-sm-push-5{left:41.66666667%}.col-sm-push-4{left:33.33333333%}.col-sm-push-3{left:25%}.col-sm-push-2{left:16.66666667%}.col-sm-push-1{left:8.33333333%}.col-sm-push-0{left:auto}.col-sm-offset-12{margin-left:100%}.col-sm-offset-11{margin-left:91.66666667%}.col-sm-offset-10{margin-left:83.33333333%}.col-sm-offset-9{margin-left:75%}.col-sm-offset-8{margin-left:66.66666667%}.col-sm-offset-7{margin-left:58.33333333%}.col-sm-offset-6{margin-left:50%}.col-sm-offset-5{margin-left:41.66666667%}.col-sm-offset-4{margin-left:33.33333333%}.col-sm-offset-3{margin-left:25%}.col-sm-offset-2{margin-left:16.66666667%}.col-sm-offset-1{margin-left:8.33333333%}.col-sm-offset-0{margin-left:0}}@media (min-width:992px){.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9{float:left}.col-md-12{width:100%}.col-md-11{width:91.66666667%}.col-md-10{width:83.33333333%}.col-md-9{width:75%}.col-md-8{width:66.66666667%}.col-md-7{width:58.33333333%}.col-md-6{width:50%}.col-md-5{width:41.66666667%}.col-md-4{width:33.33333333%}.col-md-3{width:25%}.col-md-2{width:16.66666667%}.col-md-1{width:8.33333333%}.col-md-pull-12{right:100%}.col-md-pull-11{right:91.66666667%}.col-md-pull-10{right:83.33333333%}.col-md-pull-9{right:75%}.col-md-pull-8{right:66.66666667%}.col-md-pull-7{right:58.33333333%}.col-md-pull-6{right:50%}.col-md-pull-5{right:41.66666667%}.col-md-pull-4{right:33.33333333%}.col-md-pull-3{right:25%}.col-md-pull-2{right:16.66666667%}.col-md-pull-1{right:8.33333333%}.col-md-pull-0{right:auto}.col-md-push-12{left:100%}.col-md-push-11{left:91.66666667%}.col-md-push-10{left:83.33333333%}.col-md-push-9{left:75%}.col-md-push-8{left:66.66666667%}.col-md-push-7{left:58.33333333%}.col-md-push-6{left:50%}.col-md-push-5{left:41.66666667%}.col-md-push-4{left:33.33333333%}.col-md-push-3{left:25%}.col-md-push-2{left:16.66666667%}.col-md-push-1{left:8.33333333%}.col-md-push-0{left:auto}.col-md-offset-12{margin-left:100%}.col-md-offset-11{margin-left:91.66666667%}.col-md-offset-10{margin-left:83.33333333%}.col-md-offset-9{margin-left:75%}.col-md-offset-8{margin-left:66.66666667%}.col-md-offset-7{margin-left:58.33333333%}.col-md-offset-6{margin-left:50%}.col-md-offset-5{margin-left:41.66666667%}.col-md-offset-4{margin-left:33.33333333%}.col-md-offset-3{margin-left:25%}.col-md-offset-2{margin-left:16.66666667%}.col-md-offset-1{margin-left:8.33333333%}.col-md-offset-0{margin-left:0}}@media (min-width:1200px){.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9{float:left}.col-lg-12{width:100%}.col-lg-11{width:91.66666667%}.col-lg-10{width:83.33333333%}.col-lg-9{width:75%}.col-lg-8{width:66.66666667%}.col-lg-7{width:58.33333333%}.col-lg-6{width:50%}.col-lg-5{width:41.66666667%}.col-lg-4{width:33.33333333%}.col-lg-3{width:25%}.col-lg-2{width:16.66666667%}.col-lg-1{width:8.33333333%}.col-lg-pull-12{right:100%}.col-lg-pull-11{right:91.66666667%}.col-lg-pull-10{right:83.33333333%}.col-lg-pull-9{right:75%}.col-lg-pull-8{right:66.66666667%}.col-lg-pull-7{right:58.33333333%}.col-lg-pull-6{right:50%}.col-lg-pull-5{right:41.66666667%}.col-lg-pull-4{right:33.33333333%}.col-lg-pull-3{right:25%}.col-lg-pull-2{right:16.66666667%}.col-lg-pull-1{right:8.33333333%}.col-lg-pull-0{right:auto}.col-lg-push-12{left:100%}.col-lg-push-11{left:91.66666667%}.col-lg-push-10{left:83.33333333%}.col-lg-push-9{left:75%}.col-lg-push-8{left:66.66666667%}.col-lg-push-7{left:58.33333333%}.col-lg-push-6{left:50%}.col-lg-push-5{left:41.66666667%}.col-lg-push-4{left:33.33333333%}.col-lg-push-3{left:25%}.col-lg-push-2{left:16.66666667%}.col-lg-push-1{left:8.33333333%}.col-lg-push-0{left:auto}.col-lg-offset-12{margin-left:100%}.col-lg-offset-11{margin-left:91.66666667%}.col-lg-offset-10{margin-left:83.33333333%}.col-lg-offset-9{margin-left:75%}.col-lg-offset-8{margin-left:66.66666667%}.col-lg-offset-7{margin-left:58.33333333%}.col-lg-offset-6{margin-left:50%}.col-lg-offset-5{margin-left:41.66666667%}.col-lg-offset-4{margin-left:33.33333333%}.col-lg-offset-3{margin-left:25%}.col-lg-offset-2{margin-left:16.66666667%}.col-lg-offset-1{margin-left:8.33333333%}.col-lg-offset-0{margin-left:0}}table{background-color:transparent}caption{padding-top:8px;padding-bottom:8px;color:#777;text-align:left}th{text-align:left}.table{width:100%;max-width:100%;margin-bottom:20px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}.table>thead>tr>th{vertical-align:bottom;border-bottom:2px solid #ddd}.table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th{border-top:0}.table>tbody+tbody{border-top:2px solid #ddd}.table .table{background-color:#fff}.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th{padding:5px}.table-bordered{border:1px solid #ddd}.table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{border:1px solid #ddd}.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{border-bottom-width:2px}.table-striped>tbody>tr:nth-of-type(odd){background-color:#f9f9f9}.table-hover>tbody>tr:hover{background-color:#f5f5f5}table col[class*=col-]{position:static;display:table-column;float:none}table td[class*=col-],table th[class*=col-]{position:static;display:table-cell;float:none}.table>tbody>tr.active>td,.table>tbody>tr.active>th,.table>tbody>tr>td.active,.table>tbody>tr>th.active,.table>tfoot>tr.active>td,.table>tfoot>tr.active>th,.table>tfoot>tr>td.active,.table>tfoot>tr>th.active,.table>thead>tr.active>td,.table>thead>tr.active>th,.table>thead>tr>td.active,.table>thead>tr>th.active{background-color:#f5f5f5}.table-hover>tbody>tr.active:hover>td,.table-hover>tbody>tr.active:hover>th,.table-hover>tbody>tr:hover>.active,.table-hover>tbody>tr>td.active:hover,.table-hover>tbody>tr>th.active:hover{background-color:#e8e8e8}.table>tbody>tr.success>td,.table>tbody>tr.success>th,.table>tbody>tr>td.success,.table>tbody>tr>th.success,.table>tfoot>tr.success>td,.table>tfoot>tr.success>th,.table>tfoot>tr>td.success,.table>tfoot>tr>th.success,.table>thead>tr.success>td,.table>thead>tr.success>th,.table>thead>tr>td.success,.table>thead>tr>th.success{background-color:#dff0d8}.table-hover>tbody>tr.success:hover>td,.table-hover>tbody>tr.success:hover>th,.table-hover>tbody>tr:hover>.success,.table-hover>tbody>tr>td.success:hover,.table-hover>tbody>tr>th.success:hover{background-color:#d0e9c6}.table>tbody>tr.info>td,.table>tbody>tr.info>th,.table>tbody>tr>td.info,.table>tbody>tr>th.info,.table>tfoot>tr.info>td,.table>tfoot>tr.info>th,.table>tfoot>tr>td.info,.table>tfoot>tr>th.info,.table>thead>tr.info>td,.table>thead>tr.info>th,.table>thead>tr>td.info,.table>thead>tr>th.info{background-color:#d9edf7}.table-hover>tbody>tr.info:hover>td,.table-hover>tbody>tr.info:hover>th,.table-hover>tbody>tr:hover>.info,.table-hover>tbody>tr>td.info:hover,.table-hover>tbody>tr>th.info:hover{background-color:#c4e3f3}.table>tbody>tr.warning>td,.table>tbody>tr.warning>th,.table>tbody>tr>td.warning,.table>tbody>tr>th.warning,.table>tfoot>tr.warning>td,.table>tfoot>tr.warning>th,.table>tfoot>tr>td.warning,.table>tfoot>tr>th.warning,.table>thead>tr.warning>td,.table>thead>tr.warning>th,.table>thead>tr>td.warning,.table>thead>tr>th.warning{background-color:#fcf8e3}.table-hover>tbody>tr.warning:hover>td,.table-hover>tbody>tr.warning:hover>th,.table-hover>tbody>tr:hover>.warning,.table-hover>tbody>tr>td.warning:hover,.table-hover>tbody>tr>th.warning:hover{background-color:#faf2cc}.table>tbody>tr.danger>td,.table>tbody>tr.danger>th,.table>tbody>tr>td.danger,.table>tbody>tr>th.danger,.table>tfoot>tr.danger>td,.table>tfoot>tr.danger>th,.table>tfoot>tr>td.danger,.table>tfoot>tr>th.danger,.table>thead>tr.danger>td,.table>thead>tr.danger>th,.table>thead>tr>td.danger,.table>thead>tr>th.danger{background-color:#f2dede}.table-hover>tbody>tr.danger:hover>td,.table-hover>tbody>tr.danger:hover>th,.table-hover>tbody>tr:hover>.danger,.table-hover>tbody>tr>td.danger:hover,.table-hover>tbody>tr>th.danger:hover{background-color:#ebcccc}.table-responsive{min-height:.01%;overflow-x:auto}@media screen and (max-width:767px){.table-responsive{width:100%;margin-bottom:15px;overflow-y:hidden;-ms-overflow-style:-ms-autohiding-scrollbar;border:1px solid #ddd}.table-responsive>.table{margin-bottom:0}.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th{white-space:nowrap}.table-responsive>.table-bordered{border:0}.table-responsive>.table-bordered>tbody>tr>td:first-child,.table-responsive>.table-bordered>tbody>tr>th:first-child,.table-responsive>.table-bordered>tfoot>tr>td:first-child,.table-responsive>.table-bordered>tfoot>tr>th:first-child,.table-responsive>.table-bordered>thead>tr>td:first-child,.table-responsive>.table-bordered>thead>tr>th:first-child{border-left:0}.table-responsive>.table-bordered>tbody>tr>td:last-child,.table-responsive>.table-bordered>tbody>tr>th:last-child,.table-responsive>.table-bordered>tfoot>tr>td:last-child,.table-responsive>.table-bordered>tfoot>tr>th:last-child,.table-responsive>.table-bordered>thead>tr>td:last-child,.table-responsive>.table-bordered>thead>tr>th:last-child{border-right:0}.table-responsive>.table-bordered>tbody>tr:last-child>td,.table-responsive>.table-bordered>tbody>tr:last-child>th,.table-responsive>.table-bordered>tfoot>tr:last-child>td,.table-responsive>.table-bordered>tfoot>tr:last-child>th{border-bottom:0}}fieldset{min-width:0;padding:0;margin:0;border:0}legend{display:block;width:100%;padding:0;margin-bottom:20px;font-size:21px;line-height:inherit;color:#333;border:0;border-bottom:1px solid #e5e5e5}label{display:inline-block;max-width:100%;margin-bottom:5px;font-weight:700}input[type=search]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}input[type=checkbox],input[type=radio]{margin:4px 0 0;margin-top:1px\9;line-height:normal}input[type=file]{display:block}input[type=range]{display:block;width:100%}select[multiple],select[size]{height:auto}input[type=file]:focus,input[type=checkbox]:focus,input[type=radio]:focus{outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}output{display:block;padding-top:7px;font-size:14px;line-height:1.42857143;color:#555}.form-control{display:block;width:100%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s}.form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)}.form-control::-moz-placeholder{color:#999;opacity:1}.form-control:-ms-input-placeholder{color:#999}.form-control::-webkit-input-placeholder{color:#999}.form-control::-ms-expand{background-color:transparent;border:0}.form-control[disabled],.form-control[readonly],fieldset[disabled] .form-control{background-color:#eee;opacity:1}.form-control[disabled],fieldset[disabled] .form-control{cursor:not-allowed}textarea.form-control{height:auto}input[type=search]{-webkit-appearance:none}@media screen and (-webkit-min-device-pixel-ratio:0){input[type=date].form-control,input[type=time].form-control,input[type=datetime-local].form-control,input[type=month].form-control{line-height:34px}.input-group-sm input[type=date],.input-group-sm input[type=time],.input-group-sm input[type=datetime-local],.input-group-sm input[type=month],input[type=date].input-sm,input[type=time].input-sm,input[type=datetime-local].input-sm,input[type=month].input-sm{line-height:30px}.input-group-lg input[type=date],.input-group-lg input[type=time],.input-group-lg input[type=datetime-local],.input-group-lg input[type=month],input[type=date].input-lg,input[type=time].input-lg,input[type=datetime-local].input-lg,input[type=month].input-lg{line-height:46px}}.form-group{margin-bottom:15px}.checkbox,.radio{position:relative;display:block;margin-top:10px;margin-bottom:10px}.checkbox label,.radio label{min-height:20px;padding-left:20px;margin-bottom:0;font-weight:400;cursor:pointer}.checkbox input[type=checkbox],.checkbox-inline input[type=checkbox],.radio input[type=radio],.radio-inline input[type=radio]{position:absolute;margin-top:4px\9;margin-left:-20px}.checkbox+.checkbox,.radio+.radio{margin-top:-5px}.checkbox-inline,.radio-inline{position:relative;display:inline-block;padding-left:20px;margin-bottom:0;font-weight:400;vertical-align:middle;cursor:pointer}.checkbox-inline+.checkbox-inline,.radio-inline+.radio-inline{margin-top:0;margin-left:10px}fieldset[disabled] input[type=checkbox],fieldset[disabled] input[type=radio],input[type=checkbox].disabled,input[type=checkbox][disabled],input[type=radio].disabled,input[type=radio][disabled]{cursor:not-allowed}.checkbox-inline.disabled,.radio-inline.disabled,fieldset[disabled] .checkbox-inline,fieldset[disabled] .radio-inline{cursor:not-allowed}.checkbox.disabled label,.radio.disabled label,fieldset[disabled] .checkbox label,fieldset[disabled] .radio label{cursor:not-allowed}.form-control-static{min-height:34px;padding-top:7px;padding-bottom:7px;margin-bottom:0}.form-control-static.input-lg,.form-control-static.input-sm{padding-right:0;padding-left:0}.input-sm{height:30px;padding:5px 10px;font-size:12px;line-height:1.5;border-radius:3px}select.input-sm{height:30px;line-height:30px}select[multiple].input-sm,textarea.input-sm{height:auto}.form-group-sm .form-control{height:30px;padding:5px 10px;font-size:12px;line-height:1.5;border-radius:3px}.form-group-sm select.form-control{height:30px;line-height:30px}.form-group-sm select[multiple].form-control,.form-group-sm textarea.form-control{height:auto}.form-group-sm .form-control-static{height:30px;min-height:32px;padding:6px 10px;font-size:12px;line-height:1.5}.input-lg{height:46px;padding:10px 16px;font-size:18px;line-height:1.3333333;border-radius:6px}select.input-lg{height:46px;line-height:46px}select[multiple].input-lg,textarea.input-lg{height:auto}.form-group-lg .form-control{height:46px;padding:10px 16px;font-size:18px;line-height:1.3333333;border-radius:6px}.form-group-lg select.form-control{height:46px;line-height:46px}.form-group-lg select[multiple].form-control,.form-group-lg textarea.form-control{height:auto}.form-group-lg .form-control-static{height:46px;min-height:38px;padding:11px 16px;font-size:18px;line-height:1.3333333}.has-feedback{position:relative}.has-feedback .form-control{padding-right:42.5px}.form-control-feedback{position:absolute;top:0;right:0;z-index:2;display:block;width:34px;height:34px;line-height:34px;text-align:center;pointer-events:none}.form-group-lg .form-control+.form-control-feedback,.input-group-lg+.form-control-feedback,.input-lg+.form-control-feedback{width:46px;height:46px;line-height:46px}.form-group-sm .form-control+.form-control-feedback,.input-group-sm+.form-control-feedback,.input-sm+.form-control-feedback{width:30px;height:30px;line-height:30px}.has-success .checkbox,.has-success .checkbox-inline,.has-success .control-label,.has-success .help-block,.has-success .radio,.has-success .radio-inline,.has-success.checkbox label,.has-success.checkbox-inline label,.has-success.radio label,.has-success.radio-inline label{color:#3c763d}.has-success .form-control{border-color:#3c763d;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075)}.has-success .form-control:focus{border-color:#2b542c;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #67b168;box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #67b168}.has-success .input-group-addon{color:#3c763d;background-color:#dff0d8;border-color:#3c763d}.has-success .form-control-feedback{color:#3c763d}.has-warning .checkbox,.has-warning .checkbox-inline,.has-warning .control-label,.has-warning .help-block,.has-warning .radio,.has-warning .radio-inline,.has-warning.checkbox label,.has-warning.checkbox-inline label,.has-warning.radio label,.has-warning.radio-inline label{color:#8a6d3b}.has-warning .form-control{border-color:#8a6d3b;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075)}.has-warning .form-control:focus{border-color:#66512c;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #c0a16b;box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #c0a16b}.has-warning .input-group-addon{color:#8a6d3b;background-color:#fcf8e3;border-color:#8a6d3b}.has-warning .form-control-feedback{color:#8a6d3b}.has-error .checkbox,.has-error .checkbox-inline,.has-error .control-label,.has-error .help-block,.has-error .radio,.has-error .radio-inline,.has-error.checkbox label,.has-error.checkbox-inline label,.has-error.radio label,.has-error.radio-inline label{color:#a94442}.has-error .form-control{border-color:#a94442;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075)}.has-error .form-control:focus{border-color:#843534;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #ce8483;box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #ce8483}.has-error .input-group-addon{color:#a94442;background-color:#f2dede;border-color:#a94442}.has-error .form-control-feedback{color:#a94442}.has-feedback label~.form-control-feedback{top:25px}.has-feedback label.sr-only~.form-control-feedback{top:0}.help-block{display:block;margin-top:5px;margin-bottom:10px;color:#737373}@media (min-width:768px){.form-inline .form-group{display:inline-block;margin-bottom:0;vertical-align:middle}.form-inline .form-control{display:inline-block;width:auto;vertical-align:middle}.form-inline .form-control-static{display:inline-block}.form-inline .input-group{display:inline-table;vertical-align:middle}.form-inline .input-group .form-control,.form-inline .input-group .input-group-addon,.form-inline .input-group .input-group-btn{width:auto}.form-inline .input-group>.form-control{width:100%}.form-inline .control-label{margin-bottom:0;vertical-align:middle}.form-inline .checkbox,.form-inline .radio{display:inline-block;margin-top:0;margin-bottom:0;vertical-align:middle}.form-inline .checkbox label,.form-inline .radio label{padding-left:0}.form-inline .checkbox input[type=checkbox],.form-inline .radio input[type=radio]{position:relative;margin-left:0}.form-inline .has-feedback .form-control-feedback{top:0}}.form-horizontal .checkbox,.form-horizontal .checkbox-inline,.form-horizontal .radio,.form-horizontal .radio-inline{padding-top:7px;margin-top:0;margin-bottom:0}.form-horizontal .checkbox,.form-horizontal .radio{min-height:27px}.form-horizontal .form-group{margin-right:-15px;margin-left:-15px}@media (min-width:768px){.form-horizontal .control-label{padding-top:7px;margin-bottom:0;text-align:right}}.form-horizontal .has-feedback .form-control-feedback{right:15px}@media (min-width:768px){.form-horizontal .form-group-lg .control-label{padding-top:11px;font-size:18px}}@media (min-width:768px){.form-horizontal .form-group-sm .control-label{padding-top:6px;font-size:12px}}.btn{display:inline-block;padding:6px 12px;margin-bottom:0;font-size:14px;font-weight:400;line-height:1.42857143;text-align:center;white-space:nowrap;vertical-align:middle;-ms-touch-action:manipulation;touch-action:manipulation;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-image:none;border:1px solid transparent;border-radius:4px}.btn.active.focus,.btn.active:focus,.btn.focus,.btn:active.focus,.btn:active:focus,.btn:focus{outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}.btn.focus,.btn:focus,.btn:hover{color:#333;text-decoration:none}.btn.active,.btn:active{background-image:none;outline:0;-webkit-box-shadow:inset 0 3px 5px rgba(0,0,0,.125);box-shadow:inset 0 3px 5px rgba(0,0,0,.125)}.btn.disabled,.btn[disabled],fieldset[disabled] .btn{cursor:not-allowed;filter:alpha(opacity=65);-webkit-box-shadow:none;box-shadow:none;opacity:.65}a.btn.disabled,fieldset[disabled] a.btn{pointer-events:none}.btn-default{color:#333;background-color:#fff;border-color:#ccc}.btn-default.focus,.btn-default:focus{color:#333;background-color:#e6e6e6;border-color:#8c8c8c}.btn-default:hover{color:#333;background-color:#e6e6e6;border-color:#adadad}.btn-default.active,.btn-default:active,.open>.dropdown-toggle.btn-default{color:#333;background-color:#e6e6e6;border-color:#adadad}.btn-default.active.focus,.btn-default.active:focus,.btn-default.active:hover,.btn-default:active.focus,.btn-default:active:focus,.btn-default:active:hover,.open>.dropdown-toggle.btn-default.focus,.open>.dropdown-toggle.btn-default:focus,.open>.dropdown-toggle.btn-default:hover{color:#333;background-color:#d4d4d4;border-color:#8c8c8c}.btn-default.active,.btn-default:active,.open>.dropdown-toggle.btn-default{background-image:none}.btn-default.disabled.focus,.btn-default.disabled:focus,.btn-default.disabled:hover,.btn-default[disabled].focus,.btn-default[disabled]:focus,.btn-default[disabled]:hover,fieldset[disabled] .btn-default.focus,fieldset[disabled] .btn-default:focus,fieldset[disabled] .btn-default:hover{background-color:#fff;border-color:#ccc}.btn-default .badge{color:#fff;background-color:#333}.btn-primary{color:#fff;background-color:#337ab7;border-color:#2e6da4}.btn-primary.focus,.btn-primary:focus{color:#fff;background-color:#286090;border-color:#122b40}.btn-primary:hover{color:#fff;background-color:#286090;border-color:#204d74}.btn-primary.active,.btn-primary:active,.open>.dropdown-toggle.btn-primary{color:#fff;background-color:#286090;border-color:#204d74}.btn-primary.active.focus,.btn-primary.active:focus,.btn-primary.active:hover,.btn-primary:active.focus,.btn-primary:active:focus,.btn-primary:active:hover,.open>.dropdown-toggle.btn-primary.focus,.open>.dropdown-toggle.btn-primary:focus,.open>.dropdown-toggle.btn-primary:hover{color:#fff;background-color:#204d74;border-color:#122b40}.btn-primary.active,.btn-primary:active,.open>.dropdown-toggle.btn-primary{background-image:none}.btn-primary.disabled.focus,.btn-primary.disabled:focus,.btn-primary.disabled:hover,.btn-primary[disabled].focus,.btn-primary[disabled]:focus,.btn-primary[disabled]:hover,fieldset[disabled] .btn-primary.focus,fieldset[disabled] .btn-primary:focus,fieldset[disabled] .btn-primary:hover{background-color:#337ab7;border-color:#2e6da4}.btn-primary .badge{color:#337ab7;background-color:#fff}.btn-success{color:#fff;background-color:#5cb85c;border-color:#4cae4c}.btn-success.focus,.btn-success:focus{color:#fff;background-color:#449d44;border-color:#255625}.btn-success:hover{color:#fff;background-color:#449d44;border-color:#398439}.btn-success.active,.btn-success:active,.open>.dropdown-toggle.btn-success{color:#fff;background-color:#449d44;border-color:#398439}.btn-success.active.focus,.btn-success.active:focus,.btn-success.active:hover,.btn-success:active.focus,.btn-success:active:focus,.btn-success:active:hover,.open>.dropdown-toggle.btn-success.focus,.open>.dropdown-toggle.btn-success:focus,.open>.dropdown-toggle.btn-success:hover{color:#fff;background-color:#398439;border-color:#255625}.btn-success.active,.btn-success:active,.open>.dropdown-toggle.btn-success{background-image:none}.btn-success.disabled.focus,.btn-success.disabled:focus,.btn-success.disabled:hover,.btn-success[disabled].focus,.btn-success[disabled]:focus,.btn-success[disabled]:hover,fieldset[disabled] .btn-success.focus,fieldset[disabled] .btn-success:focus,fieldset[disabled] .btn-success:hover{background-color:#5cb85c;border-color:#4cae4c}.btn-success .badge{color:#5cb85c;background-color:#fff}.btn-info{color:#fff;background-color:#5bc0de;border-color:#46b8da}.btn-info.focus,.btn-info:focus{color:#fff;background-color:#31b0d5;border-color:#1b6d85}.btn-info:hover{color:#fff;background-color:#31b0d5;border-color:#269abc}.btn-info.active,.btn-info:active,.open>.dropdown-toggle.btn-info{color:#fff;background-color:#31b0d5;border-color:#269abc}.btn-info.active.focus,.btn-info.active:focus,.btn-info.active:hover,.btn-info:active.focus,.btn-info:active:focus,.btn-info:active:hover,.open>.dropdown-toggle.btn-info.focus,.open>.dropdown-toggle.btn-info:focus,.open>.dropdown-toggle.btn-info:hover{color:#fff;background-color:#269abc;border-color:#1b6d85}.btn-info.active,.btn-info:active,.open>.dropdown-toggle.btn-info{background-image:none}.btn-info.disabled.focus,.btn-info.disabled:focus,.btn-info.disabled:hover,.btn-info[disabled].focus,.btn-info[disabled]:focus,.btn-info[disabled]:hover,fieldset[disabled] .btn-info.focus,fieldset[disabled] .btn-info:focus,fieldset[disabled] .btn-info:hover{background-color:#5bc0de;border-color:#46b8da}.btn-info .badge{color:#5bc0de;background-color:#fff}.btn-warning{color:#fff;background-color:#f0ad4e;border-color:#eea236}.btn-warning.focus,.btn-warning:focus{color:#fff;background-color:#ec971f;border-color:#985f0d}.btn-warning:hover{color:#fff;background-color:#ec971f;border-color:#d58512}.btn-warning.active,.btn-warning:active,.open>.dropdown-toggle.btn-warning{color:#fff;background-color:#ec971f;border-color:#d58512}.btn-warning.active.focus,.btn-warning.active:focus,.btn-warning.active:hover,.btn-warning:active.focus,.btn-warning:active:focus,.btn-warning:active:hover,.open>.dropdown-toggle.btn-warning.focus,.open>.dropdown-toggle.btn-warning:focus,.open>.dropdown-toggle.btn-warning:hover{color:#fff;background-color:#d58512;border-color:#985f0d}.btn-warning.active,.btn-warning:active,.open>.dropdown-toggle.btn-warning{background-image:none}.btn-warning.disabled.focus,.btn-warning.disabled:focus,.btn-warning.disabled:hover,.btn-warning[disabled].focus,.btn-warning[disabled]:focus,.btn-warning[disabled]:hover,fieldset[disabled] .btn-warning.focus,fieldset[disabled] .btn-warning:focus,fieldset[disabled] .btn-warning:hover{background-color:#f0ad4e;border-color:#eea236}.btn-warning .badge{color:#f0ad4e;background-color:#fff}.btn-danger{color:#fff;background-color:#d9534f;border-color:#d43f3a}.btn-danger.focus,.btn-danger:focus{color:#fff;background-color:#c9302c;border-color:#761c19}.btn-danger:hover{color:#fff;background-color:#c9302c;border-color:#ac2925}.btn-danger.active,.btn-danger:active,.open>.dropdown-toggle.btn-danger{color:#fff;background-color:#c9302c;border-color:#ac2925}.btn-danger.active.focus,.btn-danger.active:focus,.btn-danger.active:hover,.btn-danger:active.focus,.btn-danger:active:focus,.btn-danger:active:hover,.open>.dropdown-toggle.btn-danger.focus,.open>.dropdown-toggle.btn-danger:focus,.open>.dropdown-toggle.btn-danger:hover{color:#fff;background-color:#ac2925;border-color:#761c19}.btn-danger.active,.btn-danger:active,.open>.dropdown-toggle.btn-danger{background-image:none}.btn-danger.disabled.focus,.btn-danger.disabled:focus,.btn-danger.disabled:hover,.btn-danger[disabled].focus,.btn-danger[disabled]:focus,.btn-danger[disabled]:hover,fieldset[disabled] .btn-danger.focus,fieldset[disabled] .btn-danger:focus,fieldset[disabled] .btn-danger:hover{background-color:#d9534f;border-color:#d43f3a}.btn-danger .badge{color:#d9534f;background-color:#fff}.btn-link{font-weight:400;color:#337ab7;border-radius:0}.btn-link,.btn-link.active,.btn-link:active,.btn-link[disabled],fieldset[disabled] .btn-link{background-color:transparent;-webkit-box-shadow:none;box-shadow:none}.btn-link,.btn-link:active,.btn-link:focus,.btn-link:hover{border-color:transparent}.btn-link:focus,.btn-link:hover{color:#23527c;text-decoration:underline;background-color:transparent}.btn-link[disabled]:focus,.btn-link[disabled]:hover,fieldset[disabled] .btn-link:focus,fieldset[disabled] .btn-link:hover{color:#777;text-decoration:none}.btn-group-lg>.btn,.btn-lg{padding:10px 16px;font-size:18px;line-height:1.3333333;border-radius:6px}.btn-group-sm>.btn,.btn-sm{padding:5px 10px;font-size:12px;line-height:1.5;border-radius:3px}.btn-group-xs>.btn,.btn-xs{padding:1px 5px;font-size:12px;line-height:1.5;border-radius:3px}.btn-block{display:block;width:100%}.btn-block+.btn-block{margin-top:5px}input[type=button].btn-block,input[type=reset].btn-block,input[type=submit].btn-block{width:100%}.fade{opacity:0;-webkit-transition:opacity .15s linear;-o-transition:opacity .15s linear;transition:opacity .15s linear}.fade.in{opacity:1}.collapse{display:none}.collapse.in{display:block}tr.collapse.in{display:table-row}tbody.collapse.in{display:table-row-group}.collapsing{position:relative;height:0;overflow:hidden;-webkit-transition-timing-function:ease;-o-transition-timing-function:ease;transition-timing-function:ease;-webkit-transition-duration:.35s;-o-transition-duration:.35s;transition-duration:.35s;-webkit-transition-property:height,visibility;-o-transition-property:height,visibility;transition-property:height,visibility}.caret{display:inline-block;width:0;height:0;margin-left:2px;vertical-align:middle;border-top:4px dashed;border-top:4px solid\9;border-right:4px solid transparent;border-left:4px solid transparent}.dropdown,.dropup{position:relative}.dropdown-toggle:focus{outline:0}.dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:160px;padding:5px 0;margin:2px 0 0;font-size:14px;text-align:left;list-style:none;background-color:#fff;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #ccc;border:1px solid rgba(0,0,0,.15);border-radius:4px;-webkit-box-shadow:0 6px 12px rgba(0,0,0,.175);box-shadow:0 6px 12px rgba(0,0,0,.175)}.dropdown-menu.pull-right{right:0;left:auto}.dropdown-menu .divider{height:1px;margin:9px 0;overflow:hidden;background-color:#e5e5e5}.dropdown-menu>li>a{display:block;padding:3px 20px;clear:both;font-weight:400;line-height:1.42857143;color:#333;white-space:nowrap}.dropdown-menu>li>a:focus,.dropdown-menu>li>a:hover{color:#262626;text-decoration:none;background-color:#f5f5f5}.dropdown-menu>.active>a,.dropdown-menu>.active>a:focus,.dropdown-menu>.active>a:hover{color:#fff;text-decoration:none;background-color:#337ab7;outline:0}.dropdown-menu>.disabled>a,.dropdown-menu>.disabled>a:focus,.dropdown-menu>.disabled>a:hover{color:#777}.dropdown-menu>.disabled>a:focus,.dropdown-menu>.disabled>a:hover{text-decoration:none;cursor:not-allowed;background-color:transparent;background-image:none;filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.open>.dropdown-menu{display:block}.open>a{outline:0}.dropdown-menu-right{right:0;left:auto}.dropdown-menu-left{right:auto;left:0}.dropdown-header{display:block;padding:3px 20px;font-size:12px;line-height:1.42857143;color:#777;white-space:nowrap}.dropdown-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:990}.pull-right>.dropdown-menu{right:0;left:auto}.dropup .caret,.navbar-fixed-bottom .dropdown .caret{content:"";border-top:0;border-bottom:4px dashed;border-bottom:4px solid\9}.dropup .dropdown-menu,.navbar-fixed-bottom .dropdown .dropdown-menu{top:auto;bottom:100%;margin-bottom:2px}@media (min-width:768px){.navbar-right .dropdown-menu{right:0;left:auto}.navbar-right .dropdown-menu-left{right:auto;left:0}}.btn-group,.btn-group-vertical{position:relative;display:inline-block;vertical-align:middle}.btn-group-vertical>.btn,.btn-group>.btn{position:relative;float:left}.btn-group-vertical>.btn.active,.btn-group-vertical>.btn:active,.btn-group-vertical>.btn:focus,.btn-group-vertical>.btn:hover,.btn-group>.btn.active,.btn-group>.btn:active,.btn-group>.btn:focus,.btn-group>.btn:hover{z-index:2}.btn-group .btn+.btn,.btn-group .btn+.btn-group,.btn-group .btn-group+.btn,.btn-group .btn-group+.btn-group{margin-left:-1px}.btn-toolbar{margin-left:-5px}.btn-toolbar .btn,.btn-toolbar .btn-group,.btn-toolbar .input-group{float:left}.btn-toolbar>.btn,.btn-toolbar>.btn-group,.btn-toolbar>.input-group{margin-left:5px}.btn-group>.btn:not(:first-child):not(:last-child):not(.dropdown-toggle){border-radius:0}.btn-group>.btn:first-child{margin-left:0}.btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle){border-top-right-radius:0;border-bottom-right-radius:0}.btn-group>.btn:last-child:not(:first-child),.btn-group>.dropdown-toggle:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0}.btn-group>.btn-group{float:left}.btn-group>.btn-group:not(:first-child):not(:last-child)>.btn{border-radius:0}.btn-group>.btn-group:first-child:not(:last-child)>.btn:last-child,.btn-group>.btn-group:first-child:not(:last-child)>.dropdown-toggle{border-top-right-radius:0;border-bottom-right-radius:0}.btn-group>.btn-group:last-child:not(:first-child)>.btn:first-child{border-top-left-radius:0;border-bottom-left-radius:0}.btn-group .dropdown-toggle:active,.btn-group.open .dropdown-toggle{outline:0}.btn-group>.btn+.dropdown-toggle{padding-right:8px;padding-left:8px}.btn-group>.btn-lg+.dropdown-toggle{padding-right:12px;padding-left:12px}.btn-group.open .dropdown-toggle{-webkit-box-shadow:inset 0 3px 5px rgba(0,0,0,.125);box-shadow:inset 0 3px 5px rgba(0,0,0,.125)}.btn-group.open .dropdown-toggle.btn-link{-webkit-box-shadow:none;box-shadow:none}.btn .caret{margin-left:0}.btn-lg .caret{border-width:5px 5px 0;border-bottom-width:0}.dropup .btn-lg .caret{border-width:0 5px 5px}.btn-group-vertical>.btn,.btn-group-vertical>.btn-group,.btn-group-vertical>.btn-group>.btn{display:block;float:none;width:100%;max-width:100%}.btn-group-vertical>.btn-group>.btn{float:none}.btn-group-vertical>.btn+.btn,.btn-group-vertical>.btn+.btn-group,.btn-group-vertical>.btn-group+.btn,.btn-group-vertical>.btn-group+.btn-group{margin-top:-1px;margin-left:0}.btn-group-vertical>.btn:not(:first-child):not(:last-child){border-radius:0}.btn-group-vertical>.btn:first-child:not(:last-child){border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-right-radius:0;border-bottom-left-radius:0}.btn-group-vertical>.btn:last-child:not(:first-child){border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:4px;border-bottom-left-radius:4px}.btn-group-vertical>.btn-group:not(:first-child):not(:last-child)>.btn{border-radius:0}.btn-group-vertical>.btn-group:first-child:not(:last-child)>.btn:last-child,.btn-group-vertical>.btn-group:first-child:not(:last-child)>.dropdown-toggle{border-bottom-right-radius:0;border-bottom-left-radius:0}.btn-group-vertical>.btn-group:last-child:not(:first-child)>.btn:first-child{border-top-left-radius:0;border-top-right-radius:0}.btn-group-justified{display:table;width:100%;table-layout:fixed;border-collapse:separate}.btn-group-justified>.btn,.btn-group-justified>.btn-group{display:table-cell;float:none;width:1%}.btn-group-justified>.btn-group .btn{width:100%}.btn-group-justified>.btn-group .dropdown-menu{left:auto}[data-toggle=buttons]>.btn input[type=checkbox],[data-toggle=buttons]>.btn input[type=radio],[data-toggle=buttons]>.btn-group>.btn input[type=checkbox],[data-toggle=buttons]>.btn-group>.btn input[type=radio]{position:absolute;clip:rect(0,0,0,0);pointer-events:none}.input-group{position:relative;display:table;border-collapse:separate}.input-group[class*=col-]{float:none;padding-right:0;padding-left:0}.input-group .form-control{position:relative;z-index:2;float:left;width:100%;margin-bottom:0}.input-group .form-control:focus{z-index:3}.input-group-lg>.form-control,.input-group-lg>.input-group-addon,.input-group-lg>.input-group-btn>.btn{height:46px;padding:10px 16px;font-size:18px;line-height:1.3333333;border-radius:6px}select.input-group-lg>.form-control,select.input-group-lg>.input-group-addon,select.input-group-lg>.input-group-btn>.btn{height:46px;line-height:46px}select[multiple].input-group-lg>.form-control,select[multiple].input-group-lg>.input-group-addon,select[multiple].input-group-lg>.input-group-btn>.btn,textarea.input-group-lg>.form-control,textarea.input-group-lg>.input-group-addon,textarea.input-group-lg>.input-group-btn>.btn{height:auto}.input-group-sm>.form-control,.input-group-sm>.input-group-addon,.input-group-sm>.input-group-btn>.btn{height:30px;padding:5px 10px;font-size:12px;line-height:1.5;border-radius:3px}select.input-group-sm>.form-control,select.input-group-sm>.input-group-addon,select.input-group-sm>.input-group-btn>.btn{height:30px;line-height:30px}select[multiple].input-group-sm>.form-control,select[multiple].input-group-sm>.input-group-addon,select[multiple].input-group-sm>.input-group-btn>.btn,textarea.input-group-sm>.form-control,textarea.input-group-sm>.input-group-addon,textarea.input-group-sm>.input-group-btn>.btn{height:auto}.input-group .form-control,.input-group-addon,.input-group-btn{display:table-cell}.input-group .form-control:not(:first-child):not(:last-child),.input-group-addon:not(:first-child):not(:last-child),.input-group-btn:not(:first-child):not(:last-child){border-radius:0}.input-group-addon,.input-group-btn{width:1%;white-space:nowrap;vertical-align:middle}.input-group-addon{padding:6px 12px;font-size:14px;font-weight:400;line-height:1;color:#555;text-align:center;background-color:#eee;border:1px solid #ccc;border-radius:4px}.input-group-addon.input-sm{padding:5px 10px;font-size:12px;border-radius:3px}.input-group-addon.input-lg{padding:10px 16px;font-size:18px;border-radius:6px}.input-group-addon input[type=checkbox],.input-group-addon input[type=radio]{margin-top:0}.input-group .form-control:first-child,.input-group-addon:first-child,.input-group-btn:first-child>.btn,.input-group-btn:first-child>.btn-group>.btn,.input-group-btn:first-child>.dropdown-toggle,.input-group-btn:last-child>.btn-group:not(:last-child)>.btn,.input-group-btn:last-child>.btn:not(:last-child):not(.dropdown-toggle){border-top-right-radius:0;border-bottom-right-radius:0}.input-group-addon:first-child{border-right:0}.input-group .form-control:last-child,.input-group-addon:last-child,.input-group-btn:first-child>.btn-group:not(:first-child)>.btn,.input-group-btn:first-child>.btn:not(:first-child),.input-group-btn:last-child>.btn,.input-group-btn:last-child>.btn-group>.btn,.input-group-btn:last-child>.dropdown-toggle{border-top-left-radius:0;border-bottom-left-radius:0}.input-group-addon:last-child{border-left:0}.input-group-btn{position:relative;font-size:0;white-space:nowrap}.input-group-btn>.btn{position:relative}.input-group-btn>.btn+.btn{margin-left:-1px}.input-group-btn>.btn:active,.input-group-btn>.btn:focus,.input-group-btn>.btn:hover{z-index:2}.input-group-btn:first-child>.btn,.input-group-btn:first-child>.btn-group{margin-right:-1px}.input-group-btn:last-child>.btn,.input-group-btn:last-child>.btn-group{z-index:2;margin-left:-1px}.nav{padding-left:0;margin-bottom:0;list-style:none}.nav>li{position:relative;display:block}.nav>li>a{position:relative;display:block;padding:10px 15px}.nav>li>a:focus,.nav>li>a:hover{text-decoration:none;background-color:#eee}.nav>li.disabled>a{color:#777}.nav>li.disabled>a:focus,.nav>li.disabled>a:hover{color:#777;text-decoration:none;cursor:not-allowed;background-color:transparent}.nav .open>a,.nav .open>a:focus,.nav .open>a:hover{background-color:#eee;border-color:#337ab7}.nav .nav-divider{height:1px;margin:9px 0;overflow:hidden;background-color:#e5e5e5}.nav>li>a>img{max-width:none}.nav-tabs{border-bottom:1px solid #ddd}.nav-tabs>li{float:left;margin-bottom:-1px}.nav-tabs>li>a{margin-right:2px;line-height:1.42857143;border:1px solid transparent;border-radius:4px 4px 0 0}.nav-tabs>li>a:hover{border-color:#eee #eee #ddd}.nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{color:#555;cursor:default;background-color:#fff;border:1px solid #ddd;border-bottom-color:transparent}.nav-tabs.nav-justified{width:100%;border-bottom:0}.nav-tabs.nav-justified>li{float:none}.nav-tabs.nav-justified>li>a{margin-bottom:5px;text-align:center}.nav-tabs.nav-justified>.dropdown .dropdown-menu{top:auto;left:auto}@media (min-width:768px){.nav-tabs.nav-justified>li{display:table-cell;width:1%}.nav-tabs.nav-justified>li>a{margin-bottom:0}}.nav-tabs.nav-justified>li>a{margin-right:0;border-radius:4px}.nav-tabs.nav-justified>.active>a,.nav-tabs.nav-justified>.active>a:focus,.nav-tabs.nav-justified>.active>a:hover{border:1px solid #ddd}@media (min-width:768px){.nav-tabs.nav-justified>li>a{border-bottom:1px solid #ddd;border-radius:4px 4px 0 0}.nav-tabs.nav-justified>.active>a,.nav-tabs.nav-justified>.active>a:focus,.nav-tabs.nav-justified>.active>a:hover{border-bottom-color:#fff}}.nav-pills>li{float:left}.nav-pills>li>a{border-radius:4px}.nav-pills>li+li{margin-left:2px}.nav-pills>li.active>a,.nav-pills>li.active>a:focus,.nav-pills>li.active>a:hover{color:#fff;background-color:#337ab7}.nav-stacked>li{float:none}.nav-stacked>li+li{margin-top:2px;margin-left:0}.nav-justified{width:100%}.nav-justified>li{float:none}.nav-justified>li>a{margin-bottom:5px;text-align:center}.nav-justified>.dropdown .dropdown-menu{top:auto;left:auto}@media (min-width:768px){.nav-justified>li{display:table-cell;width:1%}.nav-justified>li>a{margin-bottom:0}}.nav-tabs-justified{border-bottom:0}.nav-tabs-justified>li>a{margin-right:0;border-radius:4px}.nav-tabs-justified>.active>a,.nav-tabs-justified>.active>a:focus,.nav-tabs-justified>.active>a:hover{border:1px solid #ddd}@media (min-width:768px){.nav-tabs-justified>li>a{border-bottom:1px solid #ddd;border-radius:4px 4px 0 0}.nav-tabs-justified>.active>a,.nav-tabs-justified>.active>a:focus,.nav-tabs-justified>.active>a:hover{border-bottom-color:#fff}}.tab-content>.tab-pane{display:none}.tab-content>.active{display:block}.nav-tabs .dropdown-menu{margin-top:-1px;border-top-left-radius:0;border-top-right-radius:0}.navbar{position:relative;min-height:50px;margin-bottom:20px;border:1px solid transparent}@media (min-width:768px){.navbar{border-radius:4px}}@media (min-width:768px){.navbar-header{float:left}}.navbar-collapse{padding-right:15px;padding-left:15px;overflow-x:visible;-webkit-overflow-scrolling:touch;border-top:1px solid transparent;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,.1);box-shadow:inset 0 1px 0 rgba(255,255,255,.1)}.navbar-collapse.in{overflow-y:auto}@media (min-width:768px){.navbar-collapse{width:auto;border-top:0;-webkit-box-shadow:none;box-shadow:none}.navbar-collapse.collapse{display:block!important;height:auto!important;padding-bottom:0;overflow:visible!important}.navbar-collapse.in{overflow-y:visible}.navbar-fixed-bottom .navbar-collapse,.navbar-fixed-top .navbar-collapse,.navbar-static-top .navbar-collapse{padding-right:0;padding-left:0}}.navbar-fixed-bottom .navbar-collapse,.navbar-fixed-top .navbar-collapse{max-height:340px}@media (max-device-width:480px) and (orientation:landscape){.navbar-fixed-bottom .navbar-collapse,.navbar-fixed-top .navbar-collapse{max-height:200px}}.container-fluid>.navbar-collapse,.container-fluid>.navbar-header,.container>.navbar-collapse,.container>.navbar-header{margin-right:-15px;margin-left:-15px}@media (min-width:768px){.container-fluid>.navbar-collapse,.container-fluid>.navbar-header,.container>.navbar-collapse,.container>.navbar-header{margin-right:0;margin-left:0}}.navbar-static-top{z-index:1000;border-width:0 0 1px}@media (min-width:768px){.navbar-static-top{border-radius:0}}.navbar-fixed-bottom,.navbar-fixed-top{position:fixed;right:0;left:0;z-index:1030}@media (min-width:768px){.navbar-fixed-bottom,.navbar-fixed-top{border-radius:0}}.navbar-fixed-top{top:0;border-width:0 0 1px}.navbar-fixed-bottom{bottom:0;margin-bottom:0;border-width:1px 0 0}.navbar-brand{float:left;height:50px;padding:15px 15px;font-size:18px;line-height:20px}.navbar-brand:focus,.navbar-brand:hover{text-decoration:none}.navbar-brand>img{display:block}@media (min-width:768px){.navbar>.container .navbar-brand,.navbar>.container-fluid .navbar-brand{margin-left:-15px}}.navbar-toggle{position:relative;float:right;padding:9px 10px;margin-top:8px;margin-right:15px;margin-bottom:8px;background-color:transparent;background-image:none;border:1px solid transparent;border-radius:4px}.navbar-toggle:focus{outline:0}.navbar-toggle .icon-bar{display:block;width:22px;height:2px;border-radius:1px}.navbar-toggle .icon-bar+.icon-bar{margin-top:4px}@media (min-width:768px){.navbar-toggle{display:none}}.navbar-nav{margin:7.5px -15px}.navbar-nav>li>a{padding-top:10px;padding-bottom:10px;line-height:20px}@media (max-width:767px){.navbar-nav .open .dropdown-menu{position:static;float:none;width:auto;margin-top:0;background-color:transparent;border:0;-webkit-box-shadow:none;box-shadow:none}.navbar-nav .open .dropdown-menu .dropdown-header,.navbar-nav .open .dropdown-menu>li>a{padding:5px 15px 5px 25px}.navbar-nav .open .dropdown-menu>li>a{line-height:20px}.navbar-nav .open .dropdown-menu>li>a:focus,.navbar-nav .open .dropdown-menu>li>a:hover{background-image:none}}@media (min-width:768px){.navbar-nav{float:left;margin:0}.navbar-nav>li{float:left}.navbar-nav>li>a{padding-top:15px;padding-bottom:15px}}.navbar-form{padding:10px 15px;margin-top:8px;margin-right:-15px;margin-bottom:8px;margin-left:-15px;border-top:1px solid transparent;border-bottom:1px solid transparent;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(255,255,255,.1);box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(255,255,255,.1)}@media (min-width:768px){.navbar-form .form-group{display:inline-block;margin-bottom:0;vertical-align:middle}.navbar-form .form-control{display:inline-block;width:auto;vertical-align:middle}.navbar-form .form-control-static{display:inline-block}.navbar-form .input-group{display:inline-table;vertical-align:middle}.navbar-form .input-group .form-control,.navbar-form .input-group .input-group-addon,.navbar-form .input-group .input-group-btn{width:auto}.navbar-form .input-group>.form-control{width:100%}.navbar-form .control-label{margin-bottom:0;vertical-align:middle}.navbar-form .checkbox,.navbar-form .radio{display:inline-block;margin-top:0;margin-bottom:0;vertical-align:middle}.navbar-form .checkbox label,.navbar-form .radio label{padding-left:0}.navbar-form .checkbox input[type=checkbox],.navbar-form .radio input[type=radio]{position:relative;margin-left:0}.navbar-form .has-feedback .form-control-feedback{top:0}}@media (max-width:767px){.navbar-form .form-group{margin-bottom:5px}.navbar-form .form-group:last-child{margin-bottom:0}}@media (min-width:768px){.navbar-form{width:auto;padding-top:0;padding-bottom:0;margin-right:0;margin-left:0;border:0;-webkit-box-shadow:none;box-shadow:none}}.navbar-nav>li>.dropdown-menu{margin-top:0;border-top-left-radius:0;border-top-right-radius:0}.navbar-fixed-bottom .navbar-nav>li>.dropdown-menu{margin-bottom:0;border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-right-radius:0;border-bottom-left-radius:0}.navbar-btn{margin-top:8px;margin-bottom:8px}.navbar-btn.btn-sm{margin-top:10px;margin-bottom:10px}.navbar-btn.btn-xs{margin-top:14px;margin-bottom:14px}.navbar-text{margin-top:15px;margin-bottom:15px}@media (min-width:768px){.navbar-text{float:left;margin-right:15px;margin-left:15px}}@media (min-width:768px){.navbar-left{float:left!important}.navbar-right{float:right!important;margin-right:-15px}.navbar-right~.navbar-right{margin-right:0}}.navbar-default{background-color:#f8f8f8;border-color:#e7e7e7}.navbar-default .navbar-brand{color:#777}.navbar-default .navbar-brand:focus,.navbar-default .navbar-brand:hover{color:#5e5e5e;background-color:transparent}.navbar-default .navbar-text{color:#777}.navbar-default .navbar-nav>li>a{color:#777}.navbar-default .navbar-nav>li>a:focus,.navbar-default .navbar-nav>li>a:hover{color:#333;background-color:transparent}.navbar-default .navbar-nav>.active>a,.navbar-default .navbar-nav>.active>a:focus,.navbar-default .navbar-nav>.active>a:hover{color:#555;background-color:#e7e7e7}.navbar-default .navbar-nav>.disabled>a,.navbar-default .navbar-nav>.disabled>a:focus,.navbar-default .navbar-nav>.disabled>a:hover{color:#ccc;background-color:transparent}.navbar-default .navbar-toggle{border-color:#ddd}.navbar-default .navbar-toggle:focus,.navbar-default .navbar-toggle:hover{background-color:#ddd}.navbar-default .navbar-toggle .icon-bar{background-color:#888}.navbar-default .navbar-collapse,.navbar-default .navbar-form{border-color:#e7e7e7}.navbar-default .navbar-nav>.open>a,.navbar-default .navbar-nav>.open>a:focus,.navbar-default .navbar-nav>.open>a:hover{color:#555;background-color:#e7e7e7}@media (max-width:767px){.navbar-default .navbar-nav .open .dropdown-menu>li>a{color:#777}.navbar-default .navbar-nav .open .dropdown-menu>li>a:focus,.navbar-default .navbar-nav .open .dropdown-menu>li>a:hover{color:#333;background-color:transparent}.navbar-default .navbar-nav .open .dropdown-menu>.active>a,.navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus,.navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover{color:#555;background-color:#e7e7e7}.navbar-default .navbar-nav .open .dropdown-menu>.disabled>a,.navbar-default .navbar-nav .open .dropdown-menu>.disabled>a:focus,.navbar-default .navbar-nav .open .dropdown-menu>.disabled>a:hover{color:#ccc;background-color:transparent}}.navbar-default .navbar-link{color:#777}.navbar-default .navbar-link:hover{color:#333}.navbar-default .btn-link{color:#777}.navbar-default .btn-link:focus,.navbar-default .btn-link:hover{color:#333}.navbar-default .btn-link[disabled]:focus,.navbar-default .btn-link[disabled]:hover,fieldset[disabled] .navbar-default .btn-link:focus,fieldset[disabled] .navbar-default .btn-link:hover{color:#ccc}.navbar-inverse{background-color:#222;border-color:#080808}.navbar-inverse .navbar-brand{color:#9d9d9d}.navbar-inverse .navbar-brand:focus,.navbar-inverse .navbar-brand:hover{color:#fff;background-color:transparent}.navbar-inverse .navbar-text{color:#9d9d9d}.navbar-inverse .navbar-nav>li>a{color:#9d9d9d}.navbar-inverse .navbar-nav>li>a:focus,.navbar-inverse .navbar-nav>li>a:hover{color:#fff;background-color:transparent}.navbar-inverse .navbar-nav>.active>a,.navbar-inverse .navbar-nav>.active>a:focus,.navbar-inverse .navbar-nav>.active>a:hover{color:#fff;background-color:#080808}.navbar-inverse .navbar-nav>.disabled>a,.navbar-inverse .navbar-nav>.disabled>a:focus,.navbar-inverse .navbar-nav>.disabled>a:hover{color:#444;background-color:transparent}.navbar-inverse .navbar-toggle{border-color:#333}.navbar-inverse .navbar-toggle:focus,.navbar-inverse .navbar-toggle:hover{background-color:#333}.navbar-inverse .navbar-toggle .icon-bar{background-color:#fff}.navbar-inverse .navbar-collapse,.navbar-inverse .navbar-form{border-color:#101010}.navbar-inverse .navbar-nav>.open>a,.navbar-inverse .navbar-nav>.open>a:focus,.navbar-inverse .navbar-nav>.open>a:hover{color:#fff;background-color:#080808}@media (max-width:767px){.navbar-inverse .navbar-nav .open .dropdown-menu>.dropdown-header{border-color:#080808}.navbar-inverse .navbar-nav .open .dropdown-menu .divider{background-color:#080808}.navbar-inverse .navbar-nav .open .dropdown-menu>li>a{color:#9d9d9d}.navbar-inverse .navbar-nav .open .dropdown-menu>li>a:focus,.navbar-inverse .navbar-nav .open .dropdown-menu>li>a:hover{color:#fff;background-color:transparent}.navbar-inverse .navbar-nav .open .dropdown-menu>.active>a,.navbar-inverse .navbar-nav .open .dropdown-menu>.active>a:focus,.navbar-inverse .navbar-nav .open .dropdown-menu>.active>a:hover{color:#fff;background-color:#080808}.navbar-inverse .navbar-nav .open .dropdown-menu>.disabled>a,.navbar-inverse .navbar-nav .open .dropdown-menu>.disabled>a:focus,.navbar-inverse .navbar-nav .open .dropdown-menu>.disabled>a:hover{color:#444;background-color:transparent}}.navbar-inverse .navbar-link{color:#9d9d9d}.navbar-inverse .navbar-link:hover{color:#fff}.navbar-inverse .btn-link{color:#9d9d9d}.navbar-inverse .btn-link:focus,.navbar-inverse .btn-link:hover{color:#fff}.navbar-inverse .btn-link[disabled]:focus,.navbar-inverse .btn-link[disabled]:hover,fieldset[disabled] .navbar-inverse .btn-link:focus,fieldset[disabled] .navbar-inverse .btn-link:hover{color:#444}.breadcrumb{padding:8px 15px;margin-bottom:20px;list-style:none;background-color:#f5f5f5;border-radius:4px}.breadcrumb>li{display:inline-block}.breadcrumb>li+li:before{padding:0 5px;color:#ccc;content:"/\00a0"}.breadcrumb>.active{color:#777}.pagination{display:inline-block;padding-left:0;margin:20px 0;border-radius:4px}.pagination>li{display:inline}.pagination>li>a,.pagination>li>span{position:relative;float:left;padding:6px 12px;margin-left:-1px;line-height:1.42857143;color:#337ab7;text-decoration:none;background-color:#fff;border:1px solid #ddd}.pagination>li:first-child>a,.pagination>li:first-child>span{margin-left:0;border-top-left-radius:4px;border-bottom-left-radius:4px}.pagination>li:last-child>a,.pagination>li:last-child>span{border-top-right-radius:4px;border-bottom-right-radius:4px}.pagination>li>a:focus,.pagination>li>a:hover,.pagination>li>span:focus,.pagination>li>span:hover{z-index:2;color:#23527c;background-color:#eee;border-color:#ddd}.pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover{z-index:3;color:#fff;cursor:default;background-color:#337ab7;border-color:#337ab7}.pagination>.disabled>a,.pagination>.disabled>a:focus,.pagination>.disabled>a:hover,.pagination>.disabled>span,.pagination>.disabled>span:focus,.pagination>.disabled>span:hover{color:#777;cursor:not-allowed;background-color:#fff;border-color:#ddd}.pagination-lg>li>a,.pagination-lg>li>span{padding:10px 16px;font-size:18px;line-height:1.3333333}.pagination-lg>li:first-child>a,.pagination-lg>li:first-child>span{border-top-left-radius:6px;border-bottom-left-radius:6px}.pagination-lg>li:last-child>a,.pagination-lg>li:last-child>span{border-top-right-radius:6px;border-bottom-right-radius:6px}.pagination-sm>li>a,.pagination-sm>li>span{padding:5px 10px;font-size:12px;line-height:1.5}.pagination-sm>li:first-child>a,.pagination-sm>li:first-child>span{border-top-left-radius:3px;border-bottom-left-radius:3px}.pagination-sm>li:last-child>a,.pagination-sm>li:last-child>span{border-top-right-radius:3px;border-bottom-right-radius:3px}.pager{padding-left:0;margin:20px 0;text-align:center;list-style:none}.pager li{display:inline}.pager li>a,.pager li>span{display:inline-block;padding:5px 14px;background-color:#fff;border:1px solid #ddd;border-radius:15px}.pager li>a:focus,.pager li>a:hover{text-decoration:none;background-color:#eee}.pager .next>a,.pager .next>span{float:right}.pager .previous>a,.pager .previous>span{float:left}.pager .disabled>a,.pager .disabled>a:focus,.pager .disabled>a:hover,.pager .disabled>span{color:#777;cursor:not-allowed;background-color:#fff}.label{display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em}a.label:focus,a.label:hover{color:#fff;text-decoration:none;cursor:pointer}.label:empty{display:none}.btn .label{position:relative;top:-1px}.label-default{background-color:#777}.label-default[href]:focus,.label-default[href]:hover{background-color:#5e5e5e}.label-primary{background-color:#337ab7}.label-primary[href]:focus,.label-primary[href]:hover{background-color:#286090}.label-success{background-color:#5cb85c}.label-success[href]:focus,.label-success[href]:hover{background-color:#449d44}.label-info{background-color:#5bc0de}.label-info[href]:focus,.label-info[href]:hover{background-color:#31b0d5}.label-warning{background-color:#f0ad4e}.label-warning[href]:focus,.label-warning[href]:hover{background-color:#ec971f}.label-danger{background-color:#d9534f}.label-danger[href]:focus,.label-danger[href]:hover{background-color:#c9302c}.badge{display:inline-block;min-width:10px;padding:3px 7px;font-size:12px;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:middle;background-color:#777;border-radius:10px}.badge:empty{display:none}.btn .badge{position:relative;top:-1px}.btn-group-xs>.btn .badge,.btn-xs .badge{top:0;padding:1px 5px}a.badge:focus,a.badge:hover{color:#fff;text-decoration:none;cursor:pointer}.list-group-item.active>.badge,.nav-pills>.active>a>.badge{color:#337ab7;background-color:#fff}.list-group-item>.badge{float:right}.list-group-item>.badge+.badge{margin-right:5px}.nav-pills>li>a>.badge{margin-left:3px}.jumbotron{padding-top:30px;padding-bottom:30px;margin-bottom:30px;color:inherit;background-color:#eee}.jumbotron .h1,.jumbotron h1{color:inherit}.jumbotron p{margin-bottom:15px;font-size:21px;font-weight:200}.jumbotron>hr{border-top-color:#d5d5d5}.container .jumbotron,.container-fluid .jumbotron{padding-right:15px;padding-left:15px;border-radius:6px}.jumbotron .container{max-width:100%}@media screen and (min-width:768px){.jumbotron{padding-top:48px;padding-bottom:48px}.container .jumbotron,.container-fluid .jumbotron{padding-right:60px;padding-left:60px}.jumbotron .h1,.jumbotron h1{font-size:63px}}.thumbnail{display:block;padding:4px;margin-bottom:20px;line-height:1.42857143;background-color:#fff;border:1px solid #ddd;border-radius:4px;-webkit-transition:border .2s ease-in-out;-o-transition:border .2s ease-in-out;transition:border .2s ease-in-out}.thumbnail a>img,.thumbnail>img{margin-right:auto;margin-left:auto}a.thumbnail.active,a.thumbnail:focus,a.thumbnail:hover{border-color:#337ab7}.thumbnail .caption{padding:9px;color:#333}.alert{padding:15px;margin-bottom:20px;border:1px solid transparent;border-radius:4px}.alert h4{margin-top:0;color:inherit}.alert .alert-link{font-weight:700}.alert>p,.alert>ul{margin-bottom:0}.alert>p+p{margin-top:5px}.alert-dismissable,.alert-dismissible{padding-right:35px}.alert-dismissable .close,.alert-dismissible .close{position:relative;top:-2px;right:-21px;color:inherit}.alert-success{color:#3c763d;background-color:#dff0d8;border-color:#d6e9c6}.alert-success hr{border-top-color:#c9e2b3}.alert-success .alert-link{color:#2b542c}.alert-info{color:#31708f;background-color:#d9edf7;border-color:#bce8f1}.alert-info hr{border-top-color:#a6e1ec}.alert-info .alert-link{color:#245269}.alert-warning{color:#8a6d3b;background-color:#fcf8e3;border-color:#faebcc}.alert-warning hr{border-top-color:#f7e1b5}.alert-warning .alert-link{color:#66512c}.alert-danger{color:#a94442;background-color:#f2dede;border-color:#ebccd1}.alert-danger hr{border-top-color:#e4b9c0}.alert-danger .alert-link{color:#843534}@-webkit-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@-o-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}.progress{height:20px;margin-bottom:20px;overflow:hidden;background-color:#f5f5f5;border-radius:4px;-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,.1);box-shadow:inset 0 1px 2px rgba(0,0,0,.1)}.progress-bar{float:left;width:0;height:100%;font-size:12px;line-height:20px;color:#fff;text-align:center;background-color:#337ab7;-webkit-box-shadow:inset 0 -1px 0 rgba(0,0,0,.15);box-shadow:inset 0 -1px 0 rgba(0,0,0,.15);-webkit-transition:width .6s ease;-o-transition:width .6s ease;transition:width .6s ease}.progress-bar-striped,.progress-striped .progress-bar{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);-webkit-background-size:40px 40px;background-size:40px 40px}.progress-bar.active,.progress.active .progress-bar{-webkit-animation:progress-bar-stripes 2s linear infinite;-o-animation:progress-bar-stripes 2s linear infinite;animation:progress-bar-stripes 2s linear infinite}.progress-bar-success{background-color:#5cb85c}.progress-striped .progress-bar-success{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.progress-bar-info{background-color:#5bc0de}.progress-striped .progress-bar-info{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.progress-bar-warning{background-color:#f0ad4e}.progress-striped .progress-bar-warning{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.progress-bar-danger{background-color:#d9534f}.progress-striped .progress-bar-danger{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.media{margin-top:15px}.media:first-child{margin-top:0}.media,.media-body{overflow:hidden;zoom:1}.media-body{width:10000px}.media-object{display:block}.media-object.img-thumbnail{max-width:none}.media-right,.media>.pull-right{padding-left:10px}.media-left,.media>.pull-left{padding-right:10px}.media-body,.media-left,.media-right{display:table-cell;vertical-align:top}.media-middle{vertical-align:middle}.media-bottom{vertical-align:bottom}.media-heading{margin-top:0;margin-bottom:5px}.media-list{padding-left:0;list-style:none}.list-group{padding-left:0;margin-bottom:20px}.list-group-item{position:relative;display:block;padding:10px 15px;margin-bottom:-1px;background-color:#fff;border:1px solid #ddd}.list-group-item:first-child{border-top-left-radius:4px;border-top-right-radius:4px}.list-group-item:last-child{margin-bottom:0;border-bottom-right-radius:4px;border-bottom-left-radius:4px}a.list-group-item,button.list-group-item{color:#555}a.list-group-item .list-group-item-heading,button.list-group-item .list-group-item-heading{color:#333}a.list-group-item:focus,a.list-group-item:hover,button.list-group-item:focus,button.list-group-item:hover{color:#555;text-decoration:none;background-color:#f5f5f5}button.list-group-item{width:100%;text-align:left}.list-group-item.disabled,.list-group-item.disabled:focus,.list-group-item.disabled:hover{color:#777;cursor:not-allowed;background-color:#eee}.list-group-item.disabled .list-group-item-heading,.list-group-item.disabled:focus .list-group-item-heading,.list-group-item.disabled:hover .list-group-item-heading{color:inherit}.list-group-item.disabled .list-group-item-text,.list-group-item.disabled:focus .list-group-item-text,.list-group-item.disabled:hover .list-group-item-text{color:#777}.list-group-item.active,.list-group-item.active:focus,.list-group-item.active:hover{z-index:2;color:#fff;background-color:#337ab7;border-color:#337ab7}.list-group-item.active .list-group-item-heading,.list-group-item.active .list-group-item-heading>.small,.list-group-item.active .list-group-item-heading>small,.list-group-item.active:focus .list-group-item-heading,.list-group-item.active:focus .list-group-item-heading>.small,.list-group-item.active:focus .list-group-item-heading>small,.list-group-item.active:hover .list-group-item-heading,.list-group-item.active:hover .list-group-item-heading>.small,.list-group-item.active:hover .list-group-item-heading>small{color:inherit}.list-group-item.active .list-group-item-text,.list-group-item.active:focus .list-group-item-text,.list-group-item.active:hover .list-group-item-text{color:#c7ddef}.list-group-item-success{color:#3c763d;background-color:#dff0d8}a.list-group-item-success,button.list-group-item-success{color:#3c763d}a.list-group-item-success .list-group-item-heading,button.list-group-item-success .list-group-item-heading{color:inherit}a.list-group-item-success:focus,a.list-group-item-success:hover,button.list-group-item-success:focus,button.list-group-item-success:hover{color:#3c763d;background-color:#d0e9c6}a.list-group-item-success.active,a.list-group-item-success.active:focus,a.list-group-item-success.active:hover,button.list-group-item-success.active,button.list-group-item-success.active:focus,button.list-group-item-success.active:hover{color:#fff;background-color:#3c763d;border-color:#3c763d}.list-group-item-info{color:#31708f;background-color:#d9edf7}a.list-group-item-info,button.list-group-item-info{color:#31708f}a.list-group-item-info .list-group-item-heading,button.list-group-item-info .list-group-item-heading{color:inherit}a.list-group-item-info:focus,a.list-group-item-info:hover,button.list-group-item-info:focus,button.list-group-item-info:hover{color:#31708f;background-color:#c4e3f3}a.list-group-item-info.active,a.list-group-item-info.active:focus,a.list-group-item-info.active:hover,button.list-group-item-info.active,button.list-group-item-info.active:focus,button.list-group-item-info.active:hover{color:#fff;background-color:#31708f;border-color:#31708f}.list-group-item-warning{color:#8a6d3b;background-color:#fcf8e3}a.list-group-item-warning,button.list-group-item-warning{color:#8a6d3b}a.list-group-item-warning .list-group-item-heading,button.list-group-item-warning .list-group-item-heading{color:inherit}a.list-group-item-warning:focus,a.list-group-item-warning:hover,button.list-group-item-warning:focus,button.list-group-item-warning:hover{color:#8a6d3b;background-color:#faf2cc}a.list-group-item-warning.active,a.list-group-item-warning.active:focus,a.list-group-item-warning.active:hover,button.list-group-item-warning.active,button.list-group-item-warning.active:focus,button.list-group-item-warning.active:hover{color:#fff;background-color:#8a6d3b;border-color:#8a6d3b}.list-group-item-danger{color:#a94442;background-color:#f2dede}a.list-group-item-danger,button.list-group-item-danger{color:#a94442}a.list-group-item-danger .list-group-item-heading,button.list-group-item-danger .list-group-item-heading{color:inherit}a.list-group-item-danger:focus,a.list-group-item-danger:hover,button.list-group-item-danger:focus,button.list-group-item-danger:hover{color:#a94442;background-color:#ebcccc}a.list-group-item-danger.active,a.list-group-item-danger.active:focus,a.list-group-item-danger.active:hover,button.list-group-item-danger.active,button.list-group-item-danger.active:focus,button.list-group-item-danger.active:hover{color:#fff;background-color:#a94442;border-color:#a94442}.list-group-item-heading{margin-top:0;margin-bottom:5px}.list-group-item-text{margin-bottom:0;line-height:1.3}.panel{margin-bottom:20px;background-color:#fff;border:1px solid transparent;border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}.panel-body{padding:15px}.panel-heading{padding:10px 15px;border-bottom:1px solid transparent;border-top-left-radius:3px;border-top-right-radius:3px}.panel-heading>.dropdown .dropdown-toggle{color:inherit}.panel-title{margin-top:0;margin-bottom:0;font-size:16px;color:inherit}.panel-title>.small,.panel-title>.small>a,.panel-title>a,.panel-title>small,.panel-title>small>a{color:inherit}.panel-footer{padding:10px 15px;background-color:#f5f5f5;border-top:1px solid #ddd;border-bottom-right-radius:3px;border-bottom-left-radius:3px}.panel>.list-group,.panel>.panel-collapse>.list-group{margin-bottom:0}.panel>.list-group .list-group-item,.panel>.panel-collapse>.list-group .list-group-item{border-width:1px 0;border-radius:0}.panel>.list-group:first-child .list-group-item:first-child,.panel>.panel-collapse>.list-group:first-child .list-group-item:first-child{border-top:0;border-top-left-radius:3px;border-top-right-radius:3px}.panel>.list-group:last-child .list-group-item:last-child,.panel>.panel-collapse>.list-group:last-child .list-group-item:last-child{border-bottom:0;border-bottom-right-radius:3px;border-bottom-left-radius:3px}.panel>.panel-heading+.panel-collapse>.list-group .list-group-item:first-child{border-top-left-radius:0;border-top-right-radius:0}.panel-heading+.list-group .list-group-item:first-child{border-top-width:0}.list-group+.panel-footer{border-top-width:0}.panel>.panel-collapse>.table,.panel>.table,.panel>.table-responsive>.table{margin-bottom:0}.panel>.panel-collapse>.table caption,.panel>.table caption,.panel>.table-responsive>.table caption{padding-right:15px;padding-left:15px}.panel>.table-responsive:first-child>.table:first-child,.panel>.table:first-child{border-top-left-radius:3px;border-top-right-radius:3px}.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child,.panel>.table:first-child>tbody:first-child>tr:first-child,.panel>.table:first-child>thead:first-child>tr:first-child{border-top-left-radius:3px;border-top-right-radius:3px}.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child td:first-child,.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child th:first-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child td:first-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child th:first-child,.panel>.table:first-child>tbody:first-child>tr:first-child td:first-child,.panel>.table:first-child>tbody:first-child>tr:first-child th:first-child,.panel>.table:first-child>thead:first-child>tr:first-child td:first-child,.panel>.table:first-child>thead:first-child>tr:first-child th:first-child{border-top-left-radius:3px}.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child td:last-child,.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child th:last-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child td:last-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child th:last-child,.panel>.table:first-child>tbody:first-child>tr:first-child td:last-child,.panel>.table:first-child>tbody:first-child>tr:first-child th:last-child,.panel>.table:first-child>thead:first-child>tr:first-child td:last-child,.panel>.table:first-child>thead:first-child>tr:first-child th:last-child{border-top-right-radius:3px}.panel>.table-responsive:last-child>.table:last-child,.panel>.table:last-child{border-bottom-right-radius:3px;border-bottom-left-radius:3px}.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child,.panel>.table:last-child>tbody:last-child>tr:last-child,.panel>.table:last-child>tfoot:last-child>tr:last-child{border-bottom-right-radius:3px;border-bottom-left-radius:3px}.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child td:first-child,.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child th:first-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child td:first-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child th:first-child,.panel>.table:last-child>tbody:last-child>tr:last-child td:first-child,.panel>.table:last-child>tbody:last-child>tr:last-child th:first-child,.panel>.table:last-child>tfoot:last-child>tr:last-child td:first-child,.panel>.table:last-child>tfoot:last-child>tr:last-child th:first-child{border-bottom-left-radius:3px}.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child td:last-child,.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child th:last-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child td:last-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child th:last-child,.panel>.table:last-child>tbody:last-child>tr:last-child td:last-child,.panel>.table:last-child>tbody:last-child>tr:last-child th:last-child,.panel>.table:last-child>tfoot:last-child>tr:last-child td:last-child,.panel>.table:last-child>tfoot:last-child>tr:last-child th:last-child{border-bottom-right-radius:3px}.panel>.panel-body+.table,.panel>.panel-body+.table-responsive,.panel>.table+.panel-body,.panel>.table-responsive+.panel-body{border-top:1px solid #ddd}.panel>.table>tbody:first-child>tr:first-child td,.panel>.table>tbody:first-child>tr:first-child th{border-top:0}.panel>.table-bordered,.panel>.table-responsive>.table-bordered{border:0}.panel>.table-bordered>tbody>tr>td:first-child,.panel>.table-bordered>tbody>tr>th:first-child,.panel>.table-bordered>tfoot>tr>td:first-child,.panel>.table-bordered>tfoot>tr>th:first-child,.panel>.table-bordered>thead>tr>td:first-child,.panel>.table-bordered>thead>tr>th:first-child,.panel>.table-responsive>.table-bordered>tbody>tr>td:first-child,.panel>.table-responsive>.table-bordered>tbody>tr>th:first-child,.panel>.table-responsive>.table-bordered>tfoot>tr>td:first-child,.panel>.table-responsive>.table-bordered>tfoot>tr>th:first-child,.panel>.table-responsive>.table-bordered>thead>tr>td:first-child,.panel>.table-responsive>.table-bordered>thead>tr>th:first-child{border-left:0}.panel>.table-bordered>tbody>tr>td:last-child,.panel>.table-bordered>tbody>tr>th:last-child,.panel>.table-bordered>tfoot>tr>td:last-child,.panel>.table-bordered>tfoot>tr>th:last-child,.panel>.table-bordered>thead>tr>td:last-child,.panel>.table-bordered>thead>tr>th:last-child,.panel>.table-responsive>.table-bordered>tbody>tr>td:last-child,.panel>.table-responsive>.table-bordered>tbody>tr>th:last-child,.panel>.table-responsive>.table-bordered>tfoot>tr>td:last-child,.panel>.table-responsive>.table-bordered>tfoot>tr>th:last-child,.panel>.table-responsive>.table-bordered>thead>tr>td:last-child,.panel>.table-responsive>.table-bordered>thead>tr>th:last-child{border-right:0}.panel>.table-bordered>tbody>tr:first-child>td,.panel>.table-bordered>tbody>tr:first-child>th,.panel>.table-bordered>thead>tr:first-child>td,.panel>.table-bordered>thead>tr:first-child>th,.panel>.table-responsive>.table-bordered>tbody>tr:first-child>td,.panel>.table-responsive>.table-bordered>tbody>tr:first-child>th,.panel>.table-responsive>.table-bordered>thead>tr:first-child>td,.panel>.table-responsive>.table-bordered>thead>tr:first-child>th{border-bottom:0}.panel>.table-bordered>tbody>tr:last-child>td,.panel>.table-bordered>tbody>tr:last-child>th,.panel>.table-bordered>tfoot>tr:last-child>td,.panel>.table-bordered>tfoot>tr:last-child>th,.panel>.table-responsive>.table-bordered>tbody>tr:last-child>td,.panel>.table-responsive>.table-bordered>tbody>tr:last-child>th,.panel>.table-responsive>.table-bordered>tfoot>tr:last-child>td,.panel>.table-responsive>.table-bordered>tfoot>tr:last-child>th{border-bottom:0}.panel>.table-responsive{margin-bottom:0;border:0}.panel-group{margin-bottom:20px}.panel-group .panel{margin-bottom:0;border-radius:4px}.panel-group .panel+.panel{margin-top:5px}.panel-group .panel-heading{border-bottom:0}.panel-group .panel-heading+.panel-collapse>.list-group,.panel-group .panel-heading+.panel-collapse>.panel-body{border-top:1px solid #ddd}.panel-group .panel-footer{border-top:0}.panel-group .panel-footer+.panel-collapse .panel-body{border-bottom:1px solid #ddd}.panel-default{border-color:#ddd}.panel-default>.panel-heading{color:#333;background-color:#f5f5f5;border-color:#ddd}.panel-default>.panel-heading+.panel-collapse>.panel-body{border-top-color:#ddd}.panel-default>.panel-heading .badge{color:#f5f5f5;background-color:#333}.panel-default>.panel-footer+.panel-collapse>.panel-body{border-bottom-color:#ddd}.panel-primary{border-color:#337ab7}.panel-primary>.panel-heading{color:#fff;background-color:#337ab7;border-color:#337ab7}.panel-primary>.panel-heading+.panel-collapse>.panel-body{border-top-color:#337ab7}.panel-primary>.panel-heading .badge{color:#337ab7;background-color:#fff}.panel-primary>.panel-footer+.panel-collapse>.panel-body{border-bottom-color:#337ab7}.panel-success{border-color:#d6e9c6}.panel-success>.panel-heading{color:#3c763d;background-color:#dff0d8;border-color:#d6e9c6}.panel-success>.panel-heading+.panel-collapse>.panel-body{border-top-color:#d6e9c6}.panel-success>.panel-heading .badge{color:#dff0d8;background-color:#3c763d}.panel-success>.panel-footer+.panel-collapse>.panel-body{border-bottom-color:#d6e9c6}.panel-info{border-color:#bce8f1}.panel-info>.panel-heading{color:#31708f;background-color:#d9edf7;border-color:#bce8f1}.panel-info>.panel-heading+.panel-collapse>.panel-body{border-top-color:#bce8f1}.panel-info>.panel-heading .badge{color:#d9edf7;background-color:#31708f}.panel-info>.panel-footer+.panel-collapse>.panel-body{border-bottom-color:#bce8f1}.panel-warning{border-color:#faebcc}.panel-warning>.panel-heading{color:#8a6d3b;background-color:#fcf8e3;border-color:#faebcc}.panel-warning>.panel-heading+.panel-collapse>.panel-body{border-top-color:#faebcc}.panel-warning>.panel-heading .badge{color:#fcf8e3;background-color:#8a6d3b}.panel-warning>.panel-footer+.panel-collapse>.panel-body{border-bottom-color:#faebcc}.panel-danger{border-color:#ebccd1}.panel-danger>.panel-heading{color:#a94442;background-color:#f2dede;border-color:#ebccd1}.panel-danger>.panel-heading+.panel-collapse>.panel-body{border-top-color:#ebccd1}.panel-danger>.panel-heading .badge{color:#f2dede;background-color:#a94442}.panel-danger>.panel-footer+.panel-collapse>.panel-body{border-bottom-color:#ebccd1}.embed-responsive{position:relative;display:block;height:0;padding:0;overflow:hidden}.embed-responsive .embed-responsive-item,.embed-responsive embed,.embed-responsive iframe,.embed-responsive object,.embed-responsive video{position:absolute;top:0;bottom:0;left:0;width:100%;height:100%;border:0}.embed-responsive-16by9{padding-bottom:56.25%}.embed-responsive-4by3{padding-bottom:75%}.well{min-height:20px;padding:19px;margin-bottom:20px;background-color:#f5f5f5;border:1px solid #e3e3e3;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.05);box-shadow:inset 0 1px 1px rgba(0,0,0,.05)}.well blockquote{border-color:#ddd;border-color:rgba(0,0,0,.15)}.well-lg{padding:24px;border-radius:6px}.well-sm{padding:9px;border-radius:3px}.close{float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2}.close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer;filter:alpha(opacity=50);opacity:.5}button.close{-webkit-appearance:none;padding:0;cursor:pointer;background:0 0;border:0}.modal-open{overflow:hidden}.modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;-webkit-overflow-scrolling:touch;outline:0}.modal.fade .modal-dialog{-webkit-transition:-webkit-transform .3s ease-out;-o-transition:-o-transform .3s ease-out;transition:transform .3s ease-out;-webkit-transform:translate(0,-25%);-ms-transform:translate(0,-25%);-o-transform:translate(0,-25%);transform:translate(0,-25%)}.modal.in .modal-dialog{-webkit-transform:translate(0,0);-ms-transform:translate(0,0);-o-transform:translate(0,0);transform:translate(0,0)}.modal-open .modal{overflow-x:hidden;overflow-y:auto}.modal-dialog{position:relative;width:auto;margin:10px}.modal-content{position:relative;background-color:#fff;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #999;border:1px solid rgba(0,0,0,.2);border-radius:6px;outline:0;-webkit-box-shadow:0 3px 9px rgba(0,0,0,.5);box-shadow:0 3px 9px rgba(0,0,0,.5)}.modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}.modal-backdrop.fade{filter:alpha(opacity=0);opacity:0}.modal-backdrop.in{filter:alpha(opacity=50);opacity:.5}.modal-header{padding:15px;border-bottom:1px solid #e5e5e5}.modal-header .close{margin-top:-2px}.modal-title{margin:0;line-height:1.42857143}.modal-body{position:relative;padding:15px}.modal-footer{padding:15px;text-align:right;border-top:1px solid #e5e5e5}.modal-footer .btn+.btn{margin-bottom:0;margin-left:5px}.modal-footer .btn-group .btn+.btn{margin-left:-1px}.modal-footer .btn-block+.btn-block{margin-left:0}.modal-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}@media (min-width:768px){.modal-dialog{width:600px;margin:30px auto}.modal-content{-webkit-box-shadow:0 5px 15px rgba(0,0,0,.5);box-shadow:0 5px 15px rgba(0,0,0,.5)}.modal-sm{width:300px}}@media (min-width:992px){.modal-lg{width:900px}}.tooltip{position:absolute;z-index:1070;display:block;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:12px;font-style:normal;font-weight:400;line-height:1.42857143;text-align:left;text-align:start;text-decoration:none;text-shadow:none;text-transform:none;letter-spacing:normal;word-break:normal;word-spacing:normal;word-wrap:normal;white-space:normal;filter:alpha(opacity=0);opacity:0;line-break:auto}.tooltip.in{filter:alpha(opacity=90);opacity:.9}.tooltip.top{padding:5px 0;margin-top:-3px}.tooltip.right{padding:0 5px;margin-left:3px}.tooltip.bottom{padding:5px 0;margin-top:3px}.tooltip.left{padding:0 5px;margin-left:-3px}.tooltip-inner{max-width:200px;padding:3px 8px;color:#fff;text-align:center;background-color:#000;border-radius:4px}.tooltip-arrow{position:absolute;width:0;height:0;border-color:transparent;border-style:solid}.tooltip.top .tooltip-arrow{bottom:0;left:50%;margin-left:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.top-left .tooltip-arrow{right:5px;bottom:0;margin-bottom:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.top-right .tooltip-arrow{bottom:0;left:5px;margin-bottom:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.right .tooltip-arrow{top:50%;left:0;margin-top:-5px;border-width:5px 5px 5px 0;border-right-color:#000}.tooltip.left .tooltip-arrow{top:50%;right:0;margin-top:-5px;border-width:5px 0 5px 5px;border-left-color:#000}.tooltip.bottom .tooltip-arrow{top:0;left:50%;margin-left:-5px;border-width:0 5px 5px;border-bottom-color:#000}.tooltip.bottom-left .tooltip-arrow{top:0;right:5px;margin-top:-5px;border-width:0 5px 5px;border-bottom-color:#000}.tooltip.bottom-right .tooltip-arrow{top:0;left:5px;margin-top:-5px;border-width:0 5px 5px;border-bottom-color:#000}.popover{position:absolute;top:0;left:0;z-index:1060;display:none;max-width:276px;padding:1px;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;font-style:normal;font-weight:400;line-height:1.42857143;text-align:left;text-align:start;text-decoration:none;text-shadow:none;text-transform:none;letter-spacing:normal;word-break:normal;word-spacing:normal;word-wrap:normal;white-space:normal;background-color:#fff;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #ccc;border:1px solid rgba(0,0,0,.2);border-radius:6px;-webkit-box-shadow:0 5px 10px rgba(0,0,0,.2);box-shadow:0 5px 10px rgba(0,0,0,.2);line-break:auto}.popover.top{margin-top:-10px}.popover.right{margin-left:10px}.popover.bottom{margin-top:10px}.popover.left{margin-left:-10px}.popover-title{padding:8px 14px;margin:0;font-size:14px;background-color:#f7f7f7;border-bottom:1px solid #ebebeb;border-radius:5px 5px 0 0}.popover-content{padding:9px 14px}.popover>.arrow,.popover>.arrow:after{position:absolute;display:block;width:0;height:0;border-color:transparent;border-style:solid}.popover>.arrow{border-width:11px}.popover>.arrow:after{content:"";border-width:10px}.popover.top>.arrow{bottom:-11px;left:50%;margin-left:-11px;border-top-color:#999;border-top-color:rgba(0,0,0,.25);border-bottom-width:0}.popover.top>.arrow:after{bottom:1px;margin-left:-10px;content:" ";border-top-color:#fff;border-bottom-width:0}.popover.right>.arrow{top:50%;left:-11px;margin-top:-11px;border-right-color:#999;border-right-color:rgba(0,0,0,.25);border-left-width:0}.popover.right>.arrow:after{bottom:-10px;left:1px;content:" ";border-right-color:#fff;border-left-width:0}.popover.bottom>.arrow{top:-11px;left:50%;margin-left:-11px;border-top-width:0;border-bottom-color:#999;border-bottom-color:rgba(0,0,0,.25)}.popover.bottom>.arrow:after{top:1px;margin-left:-10px;content:" ";border-top-width:0;border-bottom-color:#fff}.popover.left>.arrow{top:50%;right:-11px;margin-top:-11px;border-right-width:0;border-left-color:#999;border-left-color:rgba(0,0,0,.25)}.popover.left>.arrow:after{right:1px;bottom:-10px;content:" ";border-right-width:0;border-left-color:#fff}.carousel{position:relative}.carousel-inner{position:relative;width:100%;overflow:hidden}.carousel-inner>.item{position:relative;display:none;-webkit-transition:.6s ease-in-out left;-o-transition:.6s ease-in-out left;transition:.6s ease-in-out left}.carousel-inner>.item>a>img,.carousel-inner>.item>img{line-height:1}@media all and (transform-3d),(-webkit-transform-3d){.carousel-inner>.item{-webkit-transition:-webkit-transform .6s ease-in-out;-o-transition:-o-transform .6s ease-in-out;transition:transform .6s ease-in-out;-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-perspective:1000px;perspective:1000px}.carousel-inner>.item.active.right,.carousel-inner>.item.next{left:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}.carousel-inner>.item.active.left,.carousel-inner>.item.prev{left:0;-webkit-transform:translate3d(-100%,0,0);transform:translate3d(-100%,0,0)}.carousel-inner>.item.active,.carousel-inner>.item.next.left,.carousel-inner>.item.prev.right{left:0;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}}.carousel-inner>.active,.carousel-inner>.next,.carousel-inner>.prev{display:block}.carousel-inner>.active{left:0}.carousel-inner>.next,.carousel-inner>.prev{position:absolute;top:0;width:100%}.carousel-inner>.next{left:100%}.carousel-inner>.prev{left:-100%}.carousel-inner>.next.left,.carousel-inner>.prev.right{left:0}.carousel-inner>.active.left{left:-100%}.carousel-inner>.active.right{left:100%}.carousel-control{position:absolute;top:0;bottom:0;left:0;width:15%;font-size:20px;color:#fff;text-align:center;text-shadow:0 1px 2px rgba(0,0,0,.6);background-color:rgba(0,0,0,0);filter:alpha(opacity=50);opacity:.5}.carousel-control.left{background-image:-webkit-linear-gradient(left,rgba(0,0,0,.5) 0,rgba(0,0,0,.0001) 100%);background-image:-o-linear-gradient(left,rgba(0,0,0,.5) 0,rgba(0,0,0,.0001) 100%);background-image:-webkit-gradient(linear,left top,right top,from(rgba(0,0,0,.5)),to(rgba(0,0,0,.0001)));background-image:linear-gradient(to right,rgba(0,0,0,.5) 0,rgba(0,0,0,.0001) 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#80000000', endColorstr='#00000000', GradientType=1);background-repeat:repeat-x}.carousel-control.right{right:0;left:auto;background-image:-webkit-linear-gradient(left,rgba(0,0,0,.0001) 0,rgba(0,0,0,.5) 100%);background-image:-o-linear-gradient(left,rgba(0,0,0,.0001) 0,rgba(0,0,0,.5) 100%);background-image:-webkit-gradient(linear,left top,right top,from(rgba(0,0,0,.0001)),to(rgba(0,0,0,.5)));background-image:linear-gradient(to right,rgba(0,0,0,.0001) 0,rgba(0,0,0,.5) 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#80000000', GradientType=1);background-repeat:repeat-x}.carousel-control:focus,.carousel-control:hover{color:#fff;text-decoration:none;filter:alpha(opacity=90);outline:0;opacity:.9}.carousel-control .glyphicon-chevron-left,.carousel-control .glyphicon-chevron-right,.carousel-control .icon-next,.carousel-control .icon-prev{position:absolute;top:50%;z-index:5;display:inline-block;margin-top:-10px}.carousel-control .glyphicon-chevron-left,.carousel-control .icon-prev{left:50%;margin-left:-10px}.carousel-control .glyphicon-chevron-right,.carousel-control .icon-next{right:50%;margin-right:-10px}.carousel-control .icon-next,.carousel-control .icon-prev{width:20px;height:20px;font-family:serif;line-height:1}.carousel-control .icon-prev:before{content:'\2039'}.carousel-control .icon-next:before{content:'\203a'}.carousel-indicators{position:absolute;bottom:10px;left:50%;z-index:15;width:60%;padding-left:0;margin-left:-30%;text-align:center;list-style:none}.carousel-indicators li{display:inline-block;width:10px;height:10px;margin:1px;text-indent:-999px;cursor:pointer;background-color:#000\9;background-color:rgba(0,0,0,0);border:1px solid #fff;border-radius:10px}.carousel-indicators .active{width:12px;height:12px;margin:0;background-color:#fff}.carousel-caption{position:absolute;right:15%;bottom:20px;left:15%;z-index:10;padding-top:20px;padding-bottom:20px;color:#fff;text-align:center;text-shadow:0 1px 2px rgba(0,0,0,.6)}.carousel-caption .btn{text-shadow:none}@media screen and (min-width:768px){.carousel-control .glyphicon-chevron-left,.carousel-control .glyphicon-chevron-right,.carousel-control .icon-next,.carousel-control .icon-prev{width:30px;height:30px;margin-top:-10px;font-size:30px}.carousel-control .glyphicon-chevron-left,.carousel-control .icon-prev{margin-left:-10px}.carousel-control .glyphicon-chevron-right,.carousel-control .icon-next{margin-right:-10px}.carousel-caption{right:20%;left:20%;padding-bottom:30px}.carousel-indicators{bottom:20px}}.btn-group-vertical>.btn-group:after,.btn-group-vertical>.btn-group:before,.btn-toolbar:after,.btn-toolbar:before,.clearfix:after,.clearfix:before,.container-fluid:after,.container-fluid:before,.container:after,.container:before,.dl-horizontal dd:after,.dl-horizontal dd:before,.form-horizontal .form-group:after,.form-horizontal .form-group:before,.modal-footer:after,.modal-footer:before,.modal-header:after,.modal-header:before,.nav:after,.nav:before,.navbar-collapse:after,.navbar-collapse:before,.navbar-header:after,.navbar-header:before,.navbar:after,.navbar:before,.pager:after,.pager:before,.panel-body:after,.panel-body:before,.row:after,.row:before{display:table;content:" "}.btn-group-vertical>.btn-group:after,.btn-toolbar:after,.clearfix:after,.container-fluid:after,.container:after,.dl-horizontal dd:after,.form-horizontal .form-group:after,.modal-footer:after,.modal-header:after,.nav:after,.navbar-collapse:after,.navbar-header:after,.navbar:after,.pager:after,.panel-body:after,.row:after{clear:both}.center-block{display:block;margin-right:auto;margin-left:auto}.pull-right{float:right!important}.pull-left{float:left!important}.hide{display:none!important}.show{display:block!important}.invisible{visibility:hidden}.text-hide{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0}.hidden{display:none!important}.affix{position:fixed}@-ms-viewport{width:device-width}.visible-lg,.visible-md,.visible-sm,.visible-xs{display:none!important}.visible-lg-block,.visible-lg-inline,.visible-lg-inline-block,.visible-md-block,.visible-md-inline,.visible-md-inline-block,.visible-sm-block,.visible-sm-inline,.visible-sm-inline-block,.visible-xs-block,.visible-xs-inline,.visible-xs-inline-block{display:none!important}@media (max-width:767px){.visible-xs{display:block!important}table.visible-xs{display:table!important}tr.visible-xs{display:table-row!important}td.visible-xs,th.visible-xs{display:table-cell!important}}@media (max-width:767px){.visible-xs-block{display:block!important}}@media (max-width:767px){.visible-xs-inline{display:inline!important}}@media (max-width:767px){.visible-xs-inline-block{display:inline-block!important}}@media (min-width:768px) and (max-width:991px){.visible-sm{display:block!important}table.visible-sm{display:table!important}tr.visible-sm{display:table-row!important}td.visible-sm,th.visible-sm{display:table-cell!important}}@media (min-width:768px) and (max-width:991px){.visible-sm-block{display:block!important}}@media (min-width:768px) and (max-width:991px){.visible-sm-inline{display:inline!important}}@media (min-width:768px) and (max-width:991px){.visible-sm-inline-block{display:inline-block!important}}@media (min-width:992px) and (max-width:1199px){.visible-md{display:block!important}table.visible-md{display:table!important}tr.visible-md{display:table-row!important}td.visible-md,th.visible-md{display:table-cell!important}}@media (min-width:992px) and (max-width:1199px){.visible-md-block{display:block!important}}@media (min-width:992px) and (max-width:1199px){.visible-md-inline{display:inline!important}}@media (min-width:992px) and (max-width:1199px){.visible-md-inline-block{display:inline-block!important}}@media (min-width:1200px){.visible-lg{display:block!important}table.visible-lg{display:table!important}tr.visible-lg{display:table-row!important}td.visible-lg,th.visible-lg{display:table-cell!important}}@media (min-width:1200px){.visible-lg-block{display:block!important}}@media (min-width:1200px){.visible-lg-inline{display:inline!important}}@media (min-width:1200px){.visible-lg-inline-block{display:inline-block!important}}@media (max-width:767px){.hidden-xs{display:none!important}}@media (min-width:768px) and (max-width:991px){.hidden-sm{display:none!important}}@media (min-width:992px) and (max-width:1199px){.hidden-md{display:none!important}}@media (min-width:1200px){.hidden-lg{display:none!important}}.visible-print{display:none!important}@media print{.visible-print{display:block!important}table.visible-print{display:table!important}tr.visible-print{display:table-row!important}td.visible-print,th.visible-print{display:table-cell!important}}.visible-print-block{display:none!important}@media print{.visible-print-block{display:block!important}}.visible-print-inline{display:none!important}@media print{.visible-print-inline{display:inline!important}}.visible-print-inline-block{display:none!important}@media print{.visible-print-inline-block{display:inline-block!important}}@media print{.hidden-print{display:none!important}}
/*# sourceMappingURL=bootstrap.min.css.map */ <?php print '</style>
			<style type="text/css">';?> .treeview .list-group-item{cursor:pointer}.treeview span.indent{margin-left:10px;margin-right:10px}.treeview span.icon,.treeview span.image{width:12px;margin-right:5px}.treeview .node-disabled{color:silver;cursor:not-allowed}.treeview .node-hidden{display:none}.treeview span.image{display:inline-block;height:1.19em;vertical-align:middle;background-size:contain;background-repeat:no-repeat;line-height:1em} <?php print '</style>
			<style type="text/css">';?> /*
 * This combined file was created by the DataTables downloader builder:
 *   https://datatables.net/download
 *
 * To rebuild or modify this file with the latest versions of the included
 * software please visit:
 *   https://datatables.net/download/#bs/dt-1.10.16
 *
 * Included libraries:
 *   DataTables 1.10.16
 */

table.dataTable{clear:both;margin-top:6px !important;margin-bottom:6px !important;max-width:none !important;border-collapse:separate !important}table.dataTable td,table.dataTable th{-webkit-box-sizing:content-box;box-sizing:content-box}table.dataTable td.dataTables_empty,table.dataTable th.dataTables_empty{text-align:center}table.dataTable.nowrap th,table.dataTable.nowrap td{white-space:nowrap}div.dataTables_wrapper div.dataTables_length label{font-weight:normal;text-align:left;white-space:nowrap}div.dataTables_wrapper div.dataTables_length select{width:75px;display:inline-block}div.dataTables_wrapper div.dataTables_filter{text-align:right}div.dataTables_wrapper div.dataTables_filter label{font-weight:normal;white-space:nowrap;text-align:left}div.dataTables_wrapper div.dataTables_filter input{margin-left:0.5em;display:inline-block;width:auto}div.dataTables_wrapper div.dataTables_info{padding-top:8px;white-space:nowrap}div.dataTables_wrapper div.dataTables_paginate{margin:0;white-space:nowrap;text-align:right}div.dataTables_wrapper div.dataTables_paginate ul.pagination{margin:2px 0;white-space:nowrap}div.dataTables_wrapper div.dataTables_processing{position:absolute;top:50%;left:50%;width:200px;margin-left:-100px;margin-top:-26px;text-align:center;padding:1em 0}table.dataTable thead>tr>th.sorting_asc,table.dataTable thead>tr>th.sorting_desc,table.dataTable thead>tr>th.sorting,table.dataTable thead>tr>td.sorting_asc,table.dataTable thead>tr>td.sorting_desc,table.dataTable thead>tr>td.sorting{padding-right:30px}table.dataTable thead>tr>th:active,table.dataTable thead>tr>td:active{outline:none}table.dataTable thead .sorting,table.dataTable thead .sorting_asc,table.dataTable thead .sorting_desc,table.dataTable thead .sorting_asc_disabled,table.dataTable thead .sorting_desc_disabled{cursor:pointer;position:relative}table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after,table.dataTable thead .sorting_asc_disabled:after,table.dataTable thead .sorting_desc_disabled:after{position:absolute;bottom:8px;right:8px;display:block;font-family:'Glyphicons Halflings';opacity:0.5}table.dataTable thead .sorting:after{opacity:0.2;content:"\e150"}table.dataTable thead .sorting_asc:after{content:"\e155"}table.dataTable thead .sorting_desc:after{content:"\e156"}table.dataTable thead .sorting_asc_disabled:after,table.dataTable thead .sorting_desc_disabled:after{color:#eee}div.dataTables_scrollHead table.dataTable{margin-bottom:0 !important}div.dataTables_scrollBody>table{border-top:none;margin-top:0 !important;margin-bottom:0 !important}div.dataTables_scrollBody>table>thead .sorting:after,div.dataTables_scrollBody>table>thead .sorting_asc:after,div.dataTables_scrollBody>table>thead .sorting_desc:after{display:none}div.dataTables_scrollBody>table>tbody>tr:first-child>th,div.dataTables_scrollBody>table>tbody>tr:first-child>td{border-top:none}div.dataTables_scrollFoot>.dataTables_scrollFootInner{box-sizing:content-box}div.dataTables_scrollFoot>.dataTables_scrollFootInner>table{margin-top:0 !important;border-top:none}@media screen and (max-width: 767px){div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{text-align:center}}table.dataTable.table-condensed>thead>tr>th{padding-right:20px}table.dataTable.table-condensed .sorting:after,table.dataTable.table-condensed .sorting_asc:after,table.dataTable.table-condensed .sorting_desc:after{top:6px;right:6px}table.table-bordered.dataTable th,table.table-bordered.dataTable td{border-left-width:0}table.table-bordered.dataTable th:last-child,table.table-bordered.dataTable th:last-child,table.table-bordered.dataTable td:last-child,table.table-bordered.dataTable td:last-child{border-right-width:0}table.table-bordered.dataTable tbody th,table.table-bordered.dataTable tbody td{border-bottom-width:0}div.dataTables_scrollHead table.table-bordered{border-bottom-width:0}div.table-responsive>div.dataTables_wrapper>div.row{margin:0}div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:first-child{padding-left:0}div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child{padding-right:0}


 <?php print '</style>
			<style type="text/css">';?> @font-face {
  font-family: 'fontello';
  src: url('../font/fontello.eot?80353264');
  src: url('../font/fontello.eot?80353264#iefix') format('embedded-opentype'),
       url('../font/fontello.svg?80353264#fontello') format('svg');
  font-weight: normal;
  font-style: normal;
}
@font-face {
  font-family: 'fontello';
  src: url('data:application/octet-stream;base64,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') format('woff'),
       url('data:application/octet-stream;base64,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AggGAwEHBgQECgUGBAEIAQIBAwMEBAQEBgEGAggJBQQGAgQDAQgMAQUcBAMCAgEIAQ4BAgcJAwQEAQQCAwEHCgIEBQ0DAxQOEwQIBgECAQIFCQIBEwkGBAIFBgoDCAQHBQIDBgkEBgEFCQQFAwMCBQQBDgcLDwQQAwMBCAQIAQgDAQgEAwICAwQCBBIFAwwMAQMDAgwZGwMGBQUTBQMLBA0LAQQCBgQIBAkEUTIEBQIGBQMBGAoBAgcFBAMEBAQBAgEBAQIKBwcSBAcJBAMIBAIOAQECAg4CBAICDwgDBAMCAwUBBAoKAQQIBAUMBwIDCAMJBxYGBgUICBAEFAoBAgQCBgMOAwQBCgUIEQoCAgICAQUCBAEKAgMMAwIIAQIIAwEDAgcLBAECAggUAwgKAQIBBAIDBQIBAwIBAwEEGAMJAwEBAQMNAg4EAgMBBAMFAgYIBAICAQgEBAcIBQcMBAQCAgIGAQUEAwIDBQwEAhIBBAICBQ4JAgIKCAUJAgYGBwUJDAppc1ABDAENAQQDFQEDBQIDAgIBBQwIAwYGBgYBAQQIBAoBBwYCCgIEAQwBAQICBAsPAQIJCgEDC3TE6sR0dMTqxHT+3QEIAgYGAQQIAwULAQwBAwICDAEKBwIDBAIEAQIGDAUGAwMCBAEBAwMEAgQBAwMCAggEAgYEAQMEAQQEBgcDCAcKBwQFBgUMAwECBAIBAwwJDgMEBQcIBQMRAgMOCAUMAwEDCQkGBAMGAQ4ECgQBAgUCAgYKBAcHBwEJBQgHCAMCBwMCBAIGAgQFCgMDDgIFAgIFBAcCAQoIDwIDAwcDAg4DAgMEBgQGBAQBAS1PBAEIBAMEBg8KAgYEBQQFDgkUCwIBBhoCARcFBAYDBRQDAxAFAgEECAUIBAELGA0FDAICBAQMCA4EDgEKCxQHCAEFAw0CAQIBEgMKBAQJBQYCAwoDAgMFDAIQCBIDAwQEBgIECgcOAQUCBAEEAgIQBQ8FAgUDAgsCCAQEAgIEGA4JDgUJAQQGAQIDAgEEAwYHBgUCDwoBBAECAwECAwgFFwQCCAgDBQ4CCgoFAQIDBAsJBQICAgIGAgoGCgQEBAMBBAoEBgEHAgEHBgUEAgMBBQQC/g0VVQICBQQGAg8BAQIBAgEBAwIKAwYCAgUGBwMOBgIBBQQCCAECCAICAgIFHAgRCQ4JDAIEEAcAAv//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ZW4LdHJhc2gtZW1wdHkEcGx1cwxwbHVzLWNpcmNsZWQFbWludXMNbWludXMtY2lyY2xlZAlkb3duLW9wZW4HdXAtb3BlbgZ3cmVuY2gHY29nLWFsdANjb2cFYmxvY2sJYXR0ZW50aW9uB3NxdWFyZXMGbG9nb3V0BWdsb2JlBnNlYXJjaAVzcGluMwVzcGluNAVzcGluNQVzcGluNghsaW5rLWV4dARkb2NzBHNvcnQJc29ydC1kb3duB3NvcnQtdXAOZG93bmxvYWQtY2xvdWQMdXBsb2FkLWNsb3VkCGRvYy10ZXh0DHBsdXMtc3F1YXJlZAhhbmdsZS11cAphbmdsZS1kb3duDGZvbGRlci1lbXB0eRFmb2xkZXItb3Blbi1lbXB0eQh0ZXJtaW5hbA1taW51cy1zcXVhcmVkEW1pbnVzLXNxdWFyZWQtYWx0DnBlbmNpbC1zcXVhcmVkDGxpbmstZXh0LWFsdAdkb2MtaW52DGRvYy10ZXh0LWludgxzb3J0LW5hbWUtdXAOc29ydC1uYW1lLWRvd24Lc29ydC1hbHQtdXANc29ydC1hbHQtZG93bg5zb3J0LW51bWJlci11cBBzb3J0LW51bWJlci1kb3duEHBsdXMtc3F1YXJlZC1hbHQIbGFuZ3VhZ2UIZmlsZS1wZGYJZmlsZS13b3JkCmZpbGUtZXhjZWwPZmlsZS1wb3dlcnBvaW50CmZpbGUtaW1hZ2UMZmlsZS1hcmNoaXZlCmZpbGUtYXVkaW8KZmlsZS12aWRlbwlmaWxlLWNvZGUHc2xpZGVycwVzaGFyZQ1zaGFyZS1zcXVhcmVkBXRyYXNoAAAAAAEAAf//AA8AAAAAAAAAAAAAAAAAAAAAABgAGAAYABgDUv9bA1L/W7AALCCwAFVYRVkgIEu4AA5RS7AGU1pYsDQbsChZYGYgilVYsAIlYbkIAAgAY2MjYhshIbAAWbAAQyNEsgABAENgQi2wASywIGBmLbACLCBkILDAULAEJlqyKAEKQ0VjRVJbWCEjIRuKWCCwUFBYIbBAWRsgsDhQWCGwOFlZILEBCkNFY0VhZLAoUFghsQEKQ0VjRSCwMFBYIbAwWRsgsMBQWCBmIIqKYSCwClBYYBsgsCBQWCGwCmAbILA2UFghsDZgG2BZWVkbsAErWVkjsABQWGVZWS2wAywgRSCwBCVh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UlggPFkusSsBFCstsDwsIyAuRrACJUZQWCA8WS6xKwEUKy2wPSwjIC5GsAIlRlJYIDxZIyAuRrACJUZQWCA8WS6xKwEUKy2wPiywNSsjIC5GsAIlRlJYIDxZLrErARQrLbA/LLA2K4ogIDywBCNCijgjIC5GsAIlRlJYIDxZLrErARQrsARDLrArKy2wQCywABawBCWwBCYgLkcjRyNhsAlDKyMgPCAuIzixKwEUKy2wQSyxCAQlQrAAFrAEJbAEJSAuRyNHI2EgsAQjQrAJQysgsGBQWCCwQFFYswIgAyAbswImAxpZQkIjIEewBEOwAmIgsABQWLBAYFlmsAFjYCCwASsgiophILACQ2BkI7ADQ2FkUFiwAkNhG7ADQ2BZsAMlsAJiILAAUFiwQGBZZrABY2GwAiVGYTgjIDwjOBshICBGI0ewASsjYTghWbErARQrLbBCLLA1Ky6xKwEUKy2wQyywNishIyAgPLAEI0IjOLErARQrsARDLrArKy2wRCywABUgR7AAI0KyAAEBFRQTLrAxKi2wRSywABUgR7AAI0KyAAEBFRQTLrAxKi2wRiyxAAEUE7AyKi2wRyywNCotsEgssAAWRSMgLiBGiiNhOLErARQrLbBJLLAII0KwSCstsEossgAAQSstsEsssgABQSstsEwssgEAQSstsE0ssgEBQSstsE4ssgAAQistsE8ssgABQistsFAssgEAQistsFEssgEBQistsFIssgAAPistsFMssgABPistsFQssgEAPistsFUssgEBPistsFYssgAAQCstsFcssgABQCstsFgssgEAQCstsFkssgEBQCstsFossgAAQystsFsssgABQystsFwssgEAQystsF0ssgEBQystsF4ssgAAPystsF8ssgABPystsGAssgEAPystsGEssgEBPystsGIssDcrLrErARQrLbBjLLA3K7A7Ky2wZCywNyuwPCstsGUssAAWsDcrsD0rLbBmLLA4Ky6xKwEUKy2wZyywOCuwOystsGgssDgrsDwrLbBpLLA4K7A9Ky2waiywOSsusSsBFCstsGsssDkrsDsrLbBsLLA5K7A8Ky2wbSywOSuwPSstsG4ssDorLrErARQrLbBvLLA6K7A7Ky2wcCywOiuwPCstsHEssDorsD0rLbByLLMJBAIDRVghGyMhWUIrsAhlsAMkUHiwARUwLQBLuADIUlixAQGOWbABuQgACABjcLEABUKyAAEAKrEABUKzCgIBCCqxAAVCsw4AAQgqsQAGQroCwAABAAkqsQAHQroAQAABAAkqsQMARLEkAYhRWLBAiFixA2REsSYBiFFYugiAAAEEQIhjVFixAwBEWVlZWbMMAgEMKrgB/4WwBI2xAgBEAAA=') format('truetype');
}
/* Chrome hack: SVG is rendered more smooth in Windozze. 100% magic, uncomment if you need it. */
/* Note, that will break hinting! In other OS-es font will be not as sharp as it could be */
/*
@media screen and (-webkit-min-device-pixel-ratio:0) {
  @font-face {
    font-family: 'fontello';
    src: url('../font/fontello.svg?80353264#fontello') format('svg');
  }
}
*/
 
 [class^="icon-"]:before, [class*=" icon-"]:before {
  font-family: "fontello";
  font-style: normal;
  font-weight: normal;
  speak: none;
 
  display: inline-block;
  text-decoration: inherit;
  width: 1em;
  margin-right: .2em;
  text-align: center;
  /* opacity: .8; */
 
  /* For safety - reset parent styles, that can break glyph codes*/
  font-variant: normal;
  text-transform: none;
     
  /* fix buttons height, for twitter bootstrap */
  line-height: 1em;
 
  /* Animation center compensation - margins should be symmetric */
  /* remove if not needed */
  margin-left: .2em;
 
  /* you can be more comfortable with increased icons size */
  /* font-size: 120%; */
 
  /* Uncomment for 3D effect */
  /* text-shadow: 1px 1px 1px rgba(127, 127, 127, 0.3); */
}
.icon-arrows-cw:before { content: '\e800'; } /* '' */
.icon-doc:before { content: '\e801'; } /* '' */
.icon-archive:before { content: '\e802'; } /* '' */
.icon-download:before { content: '\e803'; } /* '' */
.icon-upload:before { content: '\e804'; } /* '' */
.icon-edit:before { content: '\e805'; } /* '' */
.icon-pencil:before { content: '\e806'; } /* '' */
.icon-folder:before { content: '\e807'; } /* '' */
.icon-folder-open:before { content: '\e808'; } /* '' */
.icon-trash-empty:before { content: '\e809'; } /* '' */
.icon-plus:before { content: '\e80a'; } /* '' */
.icon-plus-circled:before { content: '\e80b'; } /* '' */
.icon-minus:before { content: '\e80c'; } /* '' */
.icon-minus-circled:before { content: '\e80d'; } /* '' */
.icon-down-open:before { content: '\e80e'; } /* '' */
.icon-up-open:before { content: '\e80f'; } /* '' */
.icon-wrench:before { content: '\e810'; } /* '' */
.icon-cog-alt:before { content: '\e811'; } /* '' */
.icon-cog:before { content: '\e812'; } /* '' */
.icon-block:before { content: '\e813'; } /* '' */
.icon-attention:before { content: '\e814'; } /* '' */
.icon-squares:before { content: '\e815'; } /* '' */
.icon-logout:before { content: '\e816'; } /* '' */
.icon-globe:before { content: '\e817'; } /* '' */
.icon-search:before { content: '\e818'; } /* '' */
.icon-spin3:before { content: '\e832'; } /* '' */
.icon-spin4:before { content: '\e834'; } /* '' */
.icon-spin5:before { content: '\e838'; } /* '' */
.icon-spin6:before { content: '\e839'; } /* '' */
.icon-link-ext:before { content: '\f08e'; } /* '' */
.icon-docs:before { content: '\f0c5'; } /* '' */
.icon-sort:before { content: '\f0dc'; } /* '' */
.icon-sort-down:before { content: '\f0dd'; } /* '' */
.icon-sort-up:before { content: '\f0de'; } /* '' */
.icon-download-cloud:before { content: '\f0ed'; } /* '' */
.icon-upload-cloud:before { content: '\f0ee'; } /* '' */
.icon-doc-text:before { content: '\f0f6'; } /* '' */
.icon-plus-squared:before { content: '\f0fe'; } /* '' */
.icon-angle-up:before { content: '\f106'; } /* '' */
.icon-angle-down:before { content: '\f107'; } /* '' */
.icon-folder-empty:before { content: '\f114'; } /* '' */
.icon-folder-open-empty:before { content: '\f115'; } /* '' */
.icon-terminal:before { content: '\f120'; } /* '' */
.icon-minus-squared:before { content: '\f146'; } /* '' */
.icon-minus-squared-alt:before { content: '\f147'; } /* '' */
.icon-pencil-squared:before { content: '\f14b'; } /* '' */
.icon-link-ext-alt:before { content: '\f14c'; } /* '' */
.icon-doc-inv:before { content: '\f15b'; } /* '' */
.icon-doc-text-inv:before { content: '\f15c'; } /* '' */
.icon-sort-name-up:before { content: '\f15d'; } /* '' */
.icon-sort-name-down:before { content: '\f15e'; } /* '' */
.icon-sort-alt-up:before { content: '\f160'; } /* '' */
.icon-sort-alt-down:before { content: '\f161'; } /* '' */
.icon-sort-number-up:before { content: '\f162'; } /* '' */
.icon-sort-number-down:before { content: '\f163'; } /* '' */
.icon-plus-squared-alt:before { content: '\f196'; } /* '' */
.icon-language:before { content: '\f1ab'; } /* '' */
.icon-file-pdf:before { content: '\f1c1'; } /* '' */
.icon-file-word:before { content: '\f1c2'; } /* '' */
.icon-file-excel:before { content: '\f1c3'; } /* '' */
.icon-file-powerpoint:before { content: '\f1c4'; } /* '' */
.icon-file-image:before { content: '\f1c5'; } /* '' */
.icon-file-archive:before { content: '\f1c6'; } /* '' */
.icon-file-audio:before { content: '\f1c7'; } /* '' */
.icon-file-video:before { content: '\f1c8'; } /* '' */
.icon-file-code:before { content: '\f1c9'; } /* '' */
.icon-sliders:before { content: '\f1de'; } /* '' */
.icon-share:before { content: '\f1e0'; } /* '' */
.icon-share-squared:before { content: '\f1e1'; } /* '' */
.icon-trash:before { content: '\f1f8'; } /* '' */ <?php print '</style>
			<style type="text/css">';?> /*
   Animation example, for spinners
*/
.animate-spin {
  -moz-animation: spin 2s infinite linear;
  -o-animation: spin 2s infinite linear;
  -webkit-animation: spin 2s infinite linear;
  animation: spin 2s infinite linear;
  display: inline-block;
}
@-moz-keyframes spin {
  0% {
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  100% {
    -moz-transform: rotate(359deg);
    -o-transform: rotate(359deg);
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
@-webkit-keyframes spin {
  0% {
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  100% {
    -moz-transform: rotate(359deg);
    -o-transform: rotate(359deg);
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
@-o-keyframes spin {
  0% {
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  100% {
    -moz-transform: rotate(359deg);
    -o-transform: rotate(359deg);
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
@-ms-keyframes spin {
  0% {
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  100% {
    -moz-transform: rotate(359deg);
    -o-transform: rotate(359deg);
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
@keyframes spin {
  0% {
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  100% {
    -moz-transform: rotate(359deg);
    -o-transform: rotate(359deg);
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
 <?php print '</style>
			<style type="text/css">';?> body {
	padding-top: 70px;
	overflow-y: scroll !important;
}

/*
 * This prevents the content from moving when opening a modal
 */
.modal-open {
	padding-right: 0 !important;
}

.icon {
	font-size: 14pt;
}

#filetable td:nth-child(5), #filetable th:nth-child(5) {
	text-align: center;
}
#filetable td:nth-child(6), #filetable th:nth-child(6) {
	text-align: center;
}
#filetable tr td:last-child {
	text-align: right;
}
#filetable td:last-child a:hover {
	text-decoration: none;
}

a { cursor: pointer !important; }
a.ifmitem:focus { outline: 0 }

img.imgpreview { max-width: 100%; }

div#content { width: 100%; height: 350px; }

input[name=newpermissions] { padding: 6px 8px; }

#filetable tr th.buttons { min-width: 95px; }
#filetable tbody tr.highlightedItem { box-shadow: 0px 0px 10px 2px #337ab7; }
#filetable tbody tr.highlightedItem td:first-child a { outline: none; }
#filetable tbody tr.selectedItem { background-color: #337ab7; color: #FFF; }
#filetable tbody tr.selectedItem * a { color: #FFF; }
#varianttable tbody tr.selectedItem { background-color: #337ab7; color: #FFF; }
#varianttable tbody tr.selectedItem * a { color: #FFF; }
#filetable tbody tr td { vertical-align: inherit; }

#navbar { max-width: 100%; }

div.ifminfo { color: #adadad; font-size: 10pt; }
div.ifminfo div.panel-body { padding: 5px !important; }

@media (max-width: 768px) {
	.icon { font-size: 12pt; }
	#filetable tr th.buttons { min-width: 85px !important; }
}

footer {
	position: fixed;
	padding-top: 1em;
	border-top: 1px;
	background-color: #EEE;
	bottom: 0;
	width: 100%;
	max-height: 6em;
	overflow:hidden;
}

#waitqueue .progress {
	position:relative;
	margin-bottom: 0;
}
#waitqueue .progbarlabel {
	position:absolute;
	top: 0;
	left: 10px;
	font-weight: bold;
}

#filedropoverlay {
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	text-align: center;
	color: #FFF;
	background-color: lightblue;
	filter: alpha(opacity=70);
	-moz-opacity: 0.7;
	opacity: 0.7;
	z-index: 1000;
}

#filedropoverlay h1 {
    border-radius: 5px;
    color: #000;
    position:relative;
    top:50%;
	font-size: 6em;
	pointer-events: none;
}

.td-permissions { width: 1px; }

/* datatables */
table.dataTable thead th {
	position: relative;
	background-image: none !important;
}

table.dataTable thead th.sorting:after,
table.dataTable thead th.sorting_asc:after,
table.dataTable thead th.sorting_desc:after {
	position: absolute;
	top: 12px;
	right: 8px;
	display: block;
	font-family: fontello;
}

table.dataTable thead th.sorting:after {
	content: "\F0DC";
	font-size: 0.8em;
	padding-top: 0.12em;
}
table.dataTable thead th.sorting_asc:after {
	content: "\f0de";
}
table.dataTable thead th.sorting_desc:after {
	content: "\f0dd";
}
 <?php print '</style>
		';
	}

	public function getJS() {
		echo <<<'f00bar'
<script>
			/*! jQuery v3.1.1 | (c) jQuery Foundation | jquery.org/license */
!function(a,b){"use strict";"object"==typeof module&&"object"==typeof module.exports?module.exports=a.document?b(a,!0):function(a){if(!a.document)throw new Error("jQuery requires a window with a document");return b(a)}:b(a)}("undefined"!=typeof window?window:this,function(a,b){"use strict";var c=[],d=a.document,e=Object.getPrototypeOf,f=c.slice,g=c.concat,h=c.push,i=c.indexOf,j={},k=j.toString,l=j.hasOwnProperty,m=l.toString,n=m.call(Object),o={};function p(a,b){b=b||d;var c=b.createElement("script");c.text=a,b.head.appendChild(c).parentNode.removeChild(c)}var q="3.1.1",r=function(a,b){return new r.fn.init(a,b)},s=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,t=/^-ms-/,u=/-([a-z])/g,v=function(a,b){return b.toUpperCase()};r.fn=r.prototype={jquery:q,constructor:r,length:0,toArray:function(){return f.call(this)},get:function(a){return null==a?f.call(this):a<0?this[a+this.length]:this[a]},pushStack:function(a){var b=r.merge(this.constructor(),a);return b.prevObject=this,b},each:function(a){return r.each(this,a)},map:function(a){return this.pushStack(r.map(this,function(b,c){return a.call(b,c,b)}))},slice:function(){return this.pushStack(f.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(a<0?b:0);return this.pushStack(c>=0&&c<b?[this[c]]:[])},end:function(){return this.prevObject||this.constructor()},push:h,sort:c.sort,splice:c.splice},r.extend=r.fn.extend=function(){var a,b,c,d,e,f,g=arguments[0]||{},h=1,i=arguments.length,j=!1;for("boolean"==typeof g&&(j=g,g=arguments[h]||{},h++),"object"==typeof g||r.isFunction(g)||(g={}),h===i&&(g=this,h--);h<i;h++)if(null!=(a=arguments[h]))for(b in a)c=g[b],d=a[b],g!==d&&(j&&d&&(r.isPlainObject(d)||(e=r.isArray(d)))?(e?(e=!1,f=c&&r.isArray(c)?c:[]):f=c&&r.isPlainObject(c)?c:{},g[b]=r.extend(j,f,d)):void 0!==d&&(g[b]=d));return g},r.extend({expando:"jQuery"+(q+Math.random()).replace(/\D/g,""),isReady:!0,error:function(a){throw new Error(a)},noop:function(){},isFunction:function(a){return"function"===r.type(a)},isArray:Array.isArray,isWindow:function(a){return null!=a&&a===a.window},isNumeric:function(a){var b=r.type(a);return("number"===b||"string"===b)&&!isNaN(a-parseFloat(a))},isPlainObject:function(a){var b,c;return!(!a||"[object Object]"!==k.call(a))&&(!(b=e(a))||(c=l.call(b,"constructor")&&b.constructor,"function"==typeof c&&m.call(c)===n))},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},type:function(a){return null==a?a+"":"object"==typeof a||"function"==typeof a?j[k.call(a)]||"object":typeof a},globalEval:function(a){p(a)},camelCase:function(a){return a.replace(t,"ms-").replace(u,v)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b){var c,d=0;if(w(a)){for(c=a.length;d<c;d++)if(b.call(a[d],d,a[d])===!1)break}else for(d in a)if(b.call(a[d],d,a[d])===!1)break;return a},trim:function(a){return null==a?"":(a+"").replace(s,"")},makeArray:function(a,b){var c=b||[];return null!=a&&(w(Object(a))?r.merge(c,"string"==typeof a?[a]:a):h.call(c,a)),c},inArray:function(a,b,c){return null==b?-1:i.call(b,a,c)},merge:function(a,b){for(var c=+b.length,d=0,e=a.length;d<c;d++)a[e++]=b[d];return a.length=e,a},grep:function(a,b,c){for(var d,e=[],f=0,g=a.length,h=!c;f<g;f++)d=!b(a[f],f),d!==h&&e.push(a[f]);return e},map:function(a,b,c){var d,e,f=0,h=[];if(w(a))for(d=a.length;f<d;f++)e=b(a[f],f,c),null!=e&&h.push(e);else for(f in a)e=b(a[f],f,c),null!=e&&h.push(e);return g.apply([],h)},guid:1,proxy:function(a,b){var c,d,e;if("string"==typeof b&&(c=a[b],b=a,a=c),r.isFunction(a))return d=f.call(arguments,2),e=function(){return a.apply(b||this,d.concat(f.call(arguments)))},e.guid=a.guid=a.guid||r.guid++,e},now:Date.now,support:o}),"function"==typeof Symbol&&(r.fn[Symbol.iterator]=c[Symbol.iterator]),r.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),function(a,b){j["[object "+b+"]"]=b.toLowerCase()});function w(a){var b=!!a&&"length"in a&&a.length,c=r.type(a);return"function"!==c&&!r.isWindow(a)&&("array"===c||0===b||"number"==typeof b&&b>0&&b-1 in a)}var x=function(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u="sizzle"+1*new Date,v=a.document,w=0,x=0,y=ha(),z=ha(),A=ha(),B=function(a,b){return a===b&&(l=!0),0},C={}.hasOwnProperty,D=[],E=D.pop,F=D.push,G=D.push,H=D.slice,I=function(a,b){for(var c=0,d=a.length;c<d;c++)if(a[c]===b)return c;return-1},J="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",K="[\\x20\\t\\r\\n\\f]",L="(?:\\\\.|[\\w-]|[^\0-\\xa0])+",M="\\["+K+"*("+L+")(?:"+K+"*([*^$|!~]?=)"+K+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+L+"))|)"+K+"*\\]",N=":("+L+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+M+")*)|.*)\\)|)",O=new RegExp(K+"+","g"),P=new RegExp("^"+K+"+|((?:^|[^\\\\])(?:\\\\.)*)"+K+"+$","g"),Q=new RegExp("^"+K+"*,"+K+"*"),R=new RegExp("^"+K+"*([>+~]|"+K+")"+K+"*"),S=new RegExp("="+K+"*([^\\]'\"]*?)"+K+"*\\]","g"),T=new RegExp(N),U=new RegExp("^"+L+"$"),V={ID:new RegExp("^#("+L+")"),CLASS:new RegExp("^\\.("+L+")"),TAG:new RegExp("^("+L+"|[*])"),ATTR:new RegExp("^"+M),PSEUDO:new RegExp("^"+N),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+K+"*(even|odd|(([+-]|)(\\d*)n|)"+K+"*(?:([+-]|)"+K+"*(\\d+)|))"+K+"*\\)|)","i"),bool:new RegExp("^(?:"+J+")$","i"),needsContext:new RegExp("^"+K+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+K+"*((?:-\\d)?\\d*)"+K+"*\\)|)(?=[^-]|$)","i")},W=/^(?:input|select|textarea|button)$/i,X=/^h\d$/i,Y=/^[^{]+\{\s*\[native \w/,Z=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,$=/[+~]/,_=new RegExp("\\\\([\\da-f]{1,6}"+K+"?|("+K+")|.)","ig"),aa=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:d<0?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)},ba=/([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,ca=function(a,b){return b?"\0"===a?"\ufffd":a.slice(0,-1)+"\\"+a.charCodeAt(a.length-1).toString(16)+" ":"\\"+a},da=function(){m()},ea=ta(function(a){return a.disabled===!0&&("form"in a||"label"in a)},{dir:"parentNode",next:"legend"});try{G.apply(D=H.call(v.childNodes),v.childNodes),D[v.childNodes.length].nodeType}catch(fa){G={apply:D.length?function(a,b){F.apply(a,H.call(b))}:function(a,b){var c=a.length,d=0;while(a[c++]=b[d++]);a.length=c-1}}}function ga(a,b,d,e){var f,h,j,k,l,o,r,s=b&&b.ownerDocument,w=b?b.nodeType:9;if(d=d||[],"string"!=typeof a||!a||1!==w&&9!==w&&11!==w)return d;if(!e&&((b?b.ownerDocument||b:v)!==n&&m(b),b=b||n,p)){if(11!==w&&(l=Z.exec(a)))if(f=l[1]){if(9===w){if(!(j=b.getElementById(f)))return d;if(j.id===f)return d.push(j),d}else if(s&&(j=s.getElementById(f))&&t(b,j)&&j.id===f)return d.push(j),d}else{if(l[2])return G.apply(d,b.getElementsByTagName(a)),d;if((f=l[3])&&c.getElementsByClassName&&b.getElementsByClassName)return G.apply(d,b.getElementsByClassName(f)),d}if(c.qsa&&!A[a+" "]&&(!q||!q.test(a))){if(1!==w)s=b,r=a;else if("object"!==b.nodeName.toLowerCase()){(k=b.getAttribute("id"))?k=k.replace(ba,ca):b.setAttribute("id",k=u),o=g(a),h=o.length;while(h--)o[h]="#"+k+" "+sa(o[h]);r=o.join(","),s=$.test(a)&&qa(b.parentNode)||b}if(r)try{return G.apply(d,s.querySelectorAll(r)),d}catch(x){}finally{k===u&&b.removeAttribute("id")}}}return i(a.replace(P,"$1"),b,d,e)}function ha(){var a=[];function b(c,e){return a.push(c+" ")>d.cacheLength&&delete b[a.shift()],b[c+" "]=e}return b}function ia(a){return a[u]=!0,a}function ja(a){var b=n.createElement("fieldset");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function ka(a,b){var c=a.split("|"),e=c.length;while(e--)d.attrHandle[c[e]]=b}function la(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&a.sourceIndex-b.sourceIndex;if(d)return d;if(c)while(c=c.nextSibling)if(c===b)return-1;return a?1:-1}function ma(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function na(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function oa(a){return function(b){return"form"in b?b.parentNode&&b.disabled===!1?"label"in b?"label"in b.parentNode?b.parentNode.disabled===a:b.disabled===a:b.isDisabled===a||b.isDisabled!==!a&&ea(b)===a:b.disabled===a:"label"in b&&b.disabled===a}}function pa(a){return ia(function(b){return b=+b,ia(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function qa(a){return a&&"undefined"!=typeof a.getElementsByTagName&&a}c=ga.support={},f=ga.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return!!b&&"HTML"!==b.nodeName},m=ga.setDocument=function(a){var b,e,g=a?a.ownerDocument||a:v;return g!==n&&9===g.nodeType&&g.documentElement?(n=g,o=n.documentElement,p=!f(n),v!==n&&(e=n.defaultView)&&e.top!==e&&(e.addEventListener?e.addEventListener("unload",da,!1):e.attachEvent&&e.attachEvent("onunload",da)),c.attributes=ja(function(a){return a.className="i",!a.getAttribute("className")}),c.getElementsByTagName=ja(function(a){return a.appendChild(n.createComment("")),!a.getElementsByTagName("*").length}),c.getElementsByClassName=Y.test(n.getElementsByClassName),c.getById=ja(function(a){return o.appendChild(a).id=u,!n.getElementsByName||!n.getElementsByName(u).length}),c.getById?(d.filter.ID=function(a){var b=a.replace(_,aa);return function(a){return a.getAttribute("id")===b}},d.find.ID=function(a,b){if("undefined"!=typeof b.getElementById&&p){var c=b.getElementById(a);return c?[c]:[]}}):(d.filter.ID=function(a){var b=a.replace(_,aa);return function(a){var c="undefined"!=typeof a.getAttributeNode&&a.getAttributeNode("id");return c&&c.value===b}},d.find.ID=function(a,b){if("undefined"!=typeof b.getElementById&&p){var c,d,e,f=b.getElementById(a);if(f){if(c=f.getAttributeNode("id"),c&&c.value===a)return[f];e=b.getElementsByName(a),d=0;while(f=e[d++])if(c=f.getAttributeNode("id"),c&&c.value===a)return[f]}return[]}}),d.find.TAG=c.getElementsByTagName?function(a,b){return"undefined"!=typeof b.getElementsByTagName?b.getElementsByTagName(a):c.qsa?b.querySelectorAll(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){while(c=f[e++])1===c.nodeType&&d.push(c);return d}return f},d.find.CLASS=c.getElementsByClassName&&function(a,b){if("undefined"!=typeof b.getElementsByClassName&&p)return b.getElementsByClassName(a)},r=[],q=[],(c.qsa=Y.test(n.querySelectorAll))&&(ja(function(a){o.appendChild(a).innerHTML="<a id='"+u+"'></a><select id='"+u+"-\r\\' msallowcapture=''><option selected=''></option></select>",a.querySelectorAll("[msallowcapture^='']").length&&q.push("[*^$]="+K+"*(?:''|\"\")"),a.querySelectorAll("[selected]").length||q.push("\\["+K+"*(?:value|"+J+")"),a.querySelectorAll("[id~="+u+"-]").length||q.push("~="),a.querySelectorAll(":checked").length||q.push(":checked"),a.querySelectorAll("a#"+u+"+*").length||q.push(".#.+[+~]")}),ja(function(a){a.innerHTML="<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";var b=n.createElement("input");b.setAttribute("type","hidden"),a.appendChild(b).setAttribute("name","D"),a.querySelectorAll("[name=d]").length&&q.push("name"+K+"*[*^$|!~]?="),2!==a.querySelectorAll(":enabled").length&&q.push(":enabled",":disabled"),o.appendChild(a).disabled=!0,2!==a.querySelectorAll(":disabled").length&&q.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),q.push(",.*:")})),(c.matchesSelector=Y.test(s=o.matches||o.webkitMatchesSelector||o.mozMatchesSelector||o.oMatchesSelector||o.msMatchesSelector))&&ja(function(a){c.disconnectedMatch=s.call(a,"*"),s.call(a,"[s!='']:x"),r.push("!=",N)}),q=q.length&&new RegExp(q.join("|")),r=r.length&&new RegExp(r.join("|")),b=Y.test(o.compareDocumentPosition),t=b||Y.test(o.contains)?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)while(b=b.parentNode)if(b===a)return!0;return!1},B=b?function(a,b){if(a===b)return l=!0,0;var d=!a.compareDocumentPosition-!b.compareDocumentPosition;return d?d:(d=(a.ownerDocument||a)===(b.ownerDocument||b)?a.compareDocumentPosition(b):1,1&d||!c.sortDetached&&b.compareDocumentPosition(a)===d?a===n||a.ownerDocument===v&&t(v,a)?-1:b===n||b.ownerDocument===v&&t(v,b)?1:k?I(k,a)-I(k,b):0:4&d?-1:1)}:function(a,b){if(a===b)return l=!0,0;var c,d=0,e=a.parentNode,f=b.parentNode,g=[a],h=[b];if(!e||!f)return a===n?-1:b===n?1:e?-1:f?1:k?I(k,a)-I(k,b):0;if(e===f)return la(a,b);c=a;while(c=c.parentNode)g.unshift(c);c=b;while(c=c.parentNode)h.unshift(c);while(g[d]===h[d])d++;return d?la(g[d],h[d]):g[d]===v?-1:h[d]===v?1:0},n):n},ga.matches=function(a,b){return ga(a,null,null,b)},ga.matchesSelector=function(a,b){if((a.ownerDocument||a)!==n&&m(a),b=b.replace(S,"='$1']"),c.matchesSelector&&p&&!A[b+" "]&&(!r||!r.test(b))&&(!q||!q.test(b)))try{var d=s.call(a,b);if(d||c.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return ga(b,n,null,[a]).length>0},ga.contains=function(a,b){return(a.ownerDocument||a)!==n&&m(a),t(a,b)},ga.attr=function(a,b){(a.ownerDocument||a)!==n&&m(a);var e=d.attrHandle[b.toLowerCase()],f=e&&C.call(d.attrHandle,b.toLowerCase())?e(a,b,!p):void 0;return void 0!==f?f:c.attributes||!p?a.getAttribute(b):(f=a.getAttributeNode(b))&&f.specified?f.value:null},ga.escape=function(a){return(a+"").replace(ba,ca)},ga.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},ga.uniqueSort=function(a){var b,d=[],e=0,f=0;if(l=!c.detectDuplicates,k=!c.sortStable&&a.slice(0),a.sort(B),l){while(b=a[f++])b===a[f]&&(e=d.push(f));while(e--)a.splice(d[e],1)}return k=null,a},e=ga.getText=function(a){var b,c="",d=0,f=a.nodeType;if(f){if(1===f||9===f||11===f){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=e(a)}else if(3===f||4===f)return a.nodeValue}else while(b=a[d++])c+=e(b);return c},d=ga.selectors={cacheLength:50,createPseudo:ia,match:V,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(_,aa),a[3]=(a[3]||a[4]||a[5]||"").replace(_,aa),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||ga.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&ga.error(a[0]),a},PSEUDO:function(a){var b,c=!a[6]&&a[2];return V.CHILD.test(a[0])?null:(a[3]?a[2]=a[4]||a[5]||"":c&&T.test(c)&&(b=g(c,!0))&&(b=c.indexOf(")",c.length-b)-c.length)&&(a[0]=a[0].slice(0,b),a[2]=c.slice(0,b)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(_,aa).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=y[a+" "];return b||(b=new RegExp("(^|"+K+")"+a+"("+K+"|$)"))&&y(a,function(a){return b.test("string"==typeof a.className&&a.className||"undefined"!=typeof a.getAttribute&&a.getAttribute("class")||"")})},ATTR:function(a,b,c){return function(d){var e=ga.attr(d,a);return null==e?"!="===b:!b||(e+="","="===b?e===c:"!="===b?e!==c:"^="===b?c&&0===e.indexOf(c):"*="===b?c&&e.indexOf(c)>-1:"$="===b?c&&e.slice(-c.length)===c:"~="===b?(" "+e.replace(O," ")+" ").indexOf(c)>-1:"|="===b&&(e===c||e.slice(0,c.length+1)===c+"-"))}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h,t=!1;if(q){if(f){while(p){m=b;while(m=m[p])if(h?m.nodeName.toLowerCase()===r:1===m.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){m=q,l=m[u]||(m[u]={}),k=l[m.uniqueID]||(l[m.uniqueID]={}),j=k[a]||[],n=j[0]===w&&j[1],t=n&&j[2],m=n&&q.childNodes[n];while(m=++n&&m&&m[p]||(t=n=0)||o.pop())if(1===m.nodeType&&++t&&m===b){k[a]=[w,n,t];break}}else if(s&&(m=b,l=m[u]||(m[u]={}),k=l[m.uniqueID]||(l[m.uniqueID]={}),j=k[a]||[],n=j[0]===w&&j[1],t=n),t===!1)while(m=++n&&m&&m[p]||(t=n=0)||o.pop())if((h?m.nodeName.toLowerCase()===r:1===m.nodeType)&&++t&&(s&&(l=m[u]||(m[u]={}),k=l[m.uniqueID]||(l[m.uniqueID]={}),k[a]=[w,t]),m===b))break;return t-=e,t===d||t%d===0&&t/d>=0}}},PSEUDO:function(a,b){var c,e=d.pseudos[a]||d.setFilters[a.toLowerCase()]||ga.error("unsupported pseudo: "+a);return e[u]?e(b):e.length>1?(c=[a,a,"",b],d.setFilters.hasOwnProperty(a.toLowerCase())?ia(function(a,c){var d,f=e(a,b),g=f.length;while(g--)d=I(a,f[g]),a[d]=!(c[d]=f[g])}):function(a){return e(a,0,c)}):e}},pseudos:{not:ia(function(a){var b=[],c=[],d=h(a.replace(P,"$1"));return d[u]?ia(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),b[0]=null,!c.pop()}}),has:ia(function(a){return function(b){return ga(a,b).length>0}}),contains:ia(function(a){return a=a.replace(_,aa),function(b){return(b.textContent||b.innerText||e(b)).indexOf(a)>-1}}),lang:ia(function(a){return U.test(a||"")||ga.error("unsupported lang: "+a),a=a.replace(_,aa).toLowerCase(),function(b){var c;do if(c=p?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===o},focus:function(a){return a===n.activeElement&&(!n.hasFocus||n.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:oa(!1),disabled:oa(!0),checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeType<6)return!1;return!0},parent:function(a){return!d.pseudos.empty(a)},header:function(a){return X.test(a.nodeName)},input:function(a){return W.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||"text"===b.toLowerCase())},first:pa(function(){return[0]}),last:pa(function(a,b){return[b-1]}),eq:pa(function(a,b,c){return[c<0?c+b:c]}),even:pa(function(a,b){for(var c=0;c<b;c+=2)a.push(c);return a}),odd:pa(function(a,b){for(var c=1;c<b;c+=2)a.push(c);return a}),lt:pa(function(a,b,c){for(var d=c<0?c+b:c;--d>=0;)a.push(d);return a}),gt:pa(function(a,b,c){for(var d=c<0?c+b:c;++d<b;)a.push(d);return a})}},d.pseudos.nth=d.pseudos.eq;for(b in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})d.pseudos[b]=ma(b);for(b in{submit:!0,reset:!0})d.pseudos[b]=na(b);function ra(){}ra.prototype=d.filters=d.pseudos,d.setFilters=new ra,g=ga.tokenize=function(a,b){var c,e,f,g,h,i,j,k=z[a+" "];if(k)return b?0:k.slice(0);h=a,i=[],j=d.preFilter;while(h){c&&!(e=Q.exec(h))||(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),c=!1,(e=R.exec(h))&&(c=e.shift(),f.push({value:c,type:e[0].replace(P," ")}),h=h.slice(c.length));for(g in d.filter)!(e=V[g].exec(h))||j[g]&&!(e=j[g](e))||(c=e.shift(),f.push({value:c,type:g,matches:e}),h=h.slice(c.length));if(!c)break}return b?h.length:h?ga.error(a):z(a,i).slice(0)};function sa(a){for(var b=0,c=a.length,d="";b<c;b++)d+=a[b].value;return d}function ta(a,b,c){var d=b.dir,e=b.next,f=e||d,g=c&&"parentNode"===f,h=x++;return b.first?function(b,c,e){while(b=b[d])if(1===b.nodeType||g)return a(b,c,e);return!1}:function(b,c,i){var j,k,l,m=[w,h];if(i){while(b=b[d])if((1===b.nodeType||g)&&a(b,c,i))return!0}else while(b=b[d])if(1===b.nodeType||g)if(l=b[u]||(b[u]={}),k=l[b.uniqueID]||(l[b.uniqueID]={}),e&&e===b.nodeName.toLowerCase())b=b[d]||b;else{if((j=k[f])&&j[0]===w&&j[1]===h)return m[2]=j[2];if(k[f]=m,m[2]=a(b,c,i))return!0}return!1}}function ua(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function va(a,b,c){for(var d=0,e=b.length;d<e;d++)ga(a,b[d],c);return c}function wa(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;h<i;h++)(f=a[h])&&(c&&!c(f,d,e)||(g.push(f),j&&b.push(h)));return g}function xa(a,b,c,d,e,f){return d&&!d[u]&&(d=xa(d)),e&&!e[u]&&(e=xa(e,f)),ia(function(f,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=f||va(b||"*",h.nodeType?[h]:h,[]),q=!a||!f&&b?p:wa(p,m,a,h,i),r=c?e||(f?a:o||d)?[]:g:q;if(c&&c(q,r,h,i),d){j=wa(r,n),d(j,[],h,i),k=j.length;while(k--)(l=j[k])&&(r[n[k]]=!(q[n[k]]=l))}if(f){if(e||a){if(e){j=[],k=r.length;while(k--)(l=r[k])&&j.push(q[k]=l);e(null,r=[],j,i)}k=r.length;while(k--)(l=r[k])&&(j=e?I(f,l):m[k])>-1&&(f[j]=!(g[j]=l))}}else r=wa(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):G.apply(g,r)})}function ya(a){for(var b,c,e,f=a.length,g=d.relative[a[0].type],h=g||d.relative[" "],i=g?1:0,k=ta(function(a){return a===b},h,!0),l=ta(function(a){return I(b,a)>-1},h,!0),m=[function(a,c,d){var e=!g&&(d||c!==j)||((b=c).nodeType?k(a,c,d):l(a,c,d));return b=null,e}];i<f;i++)if(c=d.relative[a[i].type])m=[ta(ua(m),c)];else{if(c=d.filter[a[i].type].apply(null,a[i].matches),c[u]){for(e=++i;e<f;e++)if(d.relative[a[e].type])break;return xa(i>1&&ua(m),i>1&&sa(a.slice(0,i-1).concat({value:" "===a[i-2].type?"*":""})).replace(P,"$1"),c,i<e&&ya(a.slice(i,e)),e<f&&ya(a=a.slice(e)),e<f&&sa(a))}m.push(c)}return ua(m)}function za(a,b){var c=b.length>0,e=a.length>0,f=function(f,g,h,i,k){var l,o,q,r=0,s="0",t=f&&[],u=[],v=j,x=f||e&&d.find.TAG("*",k),y=w+=null==v?1:Math.random()||.1,z=x.length;for(k&&(j=g===n||g||k);s!==z&&null!=(l=x[s]);s++){if(e&&l){o=0,g||l.ownerDocument===n||(m(l),h=!p);while(q=a[o++])if(q(l,g||n,h)){i.push(l);break}k&&(w=y)}c&&((l=!q&&l)&&r--,f&&t.push(l))}if(r+=s,c&&s!==r){o=0;while(q=b[o++])q(t,u,g,h);if(f){if(r>0)while(s--)t[s]||u[s]||(u[s]=E.call(i));u=wa(u)}G.apply(i,u),k&&!f&&u.length>0&&r+b.length>1&&ga.uniqueSort(i)}return k&&(w=y,j=v),t};return c?ia(f):f}return h=ga.compile=function(a,b){var c,d=[],e=[],f=A[a+" "];if(!f){b||(b=g(a)),c=b.length;while(c--)f=ya(b[c]),f[u]?d.push(f):e.push(f);f=A(a,za(e,d)),f.selector=a}return f},i=ga.select=function(a,b,c,e){var f,i,j,k,l,m="function"==typeof a&&a,n=!e&&g(a=m.selector||a);if(c=c||[],1===n.length){if(i=n[0]=n[0].slice(0),i.length>2&&"ID"===(j=i[0]).type&&9===b.nodeType&&p&&d.relative[i[1].type]){if(b=(d.find.ID(j.matches[0].replace(_,aa),b)||[])[0],!b)return c;m&&(b=b.parentNode),a=a.slice(i.shift().value.length)}f=V.needsContext.test(a)?0:i.length;while(f--){if(j=i[f],d.relative[k=j.type])break;if((l=d.find[k])&&(e=l(j.matches[0].replace(_,aa),$.test(i[0].type)&&qa(b.parentNode)||b))){if(i.splice(f,1),a=e.length&&sa(i),!a)return G.apply(c,e),c;break}}}return(m||h(a,n))(e,b,!p,c,!b||$.test(a)&&qa(b.parentNode)||b),c},c.sortStable=u.split("").sort(B).join("")===u,c.detectDuplicates=!!l,m(),c.sortDetached=ja(function(a){return 1&a.compareDocumentPosition(n.createElement("fieldset"))}),ja(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||ka("type|href|height|width",function(a,b,c){if(!c)return a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),c.attributes&&ja(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||ka("value",function(a,b,c){if(!c&&"input"===a.nodeName.toLowerCase())return a.defaultValue}),ja(function(a){return null==a.getAttribute("disabled")})||ka(J,function(a,b,c){var d;if(!c)return a[b]===!0?b.toLowerCase():(d=a.getAttributeNode(b))&&d.specified?d.value:null}),ga}(a);r.find=x,r.expr=x.selectors,r.expr[":"]=r.expr.pseudos,r.uniqueSort=r.unique=x.uniqueSort,r.text=x.getText,r.isXMLDoc=x.isXML,r.contains=x.contains,r.escapeSelector=x.escape;var y=function(a,b,c){var d=[],e=void 0!==c;while((a=a[b])&&9!==a.nodeType)if(1===a.nodeType){if(e&&r(a).is(c))break;d.push(a)}return d},z=function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c},A=r.expr.match.needsContext,B=/^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i,C=/^.[^:#\[\.,]*$/;function D(a,b,c){return r.isFunction(b)?r.grep(a,function(a,d){return!!b.call(a,d,a)!==c}):b.nodeType?r.grep(a,function(a){return a===b!==c}):"string"!=typeof b?r.grep(a,function(a){return i.call(b,a)>-1!==c}):C.test(b)?r.filter(b,a,c):(b=r.filter(b,a),r.grep(a,function(a){return i.call(b,a)>-1!==c&&1===a.nodeType}))}r.filter=function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?r.find.matchesSelector(d,a)?[d]:[]:r.find.matches(a,r.grep(b,function(a){return 1===a.nodeType}))},r.fn.extend({find:function(a){var b,c,d=this.length,e=this;if("string"!=typeof a)return this.pushStack(r(a).filter(function(){for(b=0;b<d;b++)if(r.contains(e[b],this))return!0}));for(c=this.pushStack([]),b=0;b<d;b++)r.find(a,e[b],c);return d>1?r.uniqueSort(c):c},filter:function(a){return this.pushStack(D(this,a||[],!1))},not:function(a){return this.pushStack(D(this,a||[],!0))},is:function(a){return!!D(this,"string"==typeof a&&A.test(a)?r(a):a||[],!1).length}});var E,F=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/,G=r.fn.init=function(a,b,c){var e,f;if(!a)return this;if(c=c||E,"string"==typeof a){if(e="<"===a[0]&&">"===a[a.length-1]&&a.length>=3?[null,a,null]:F.exec(a),!e||!e[1]&&b)return!b||b.jquery?(b||c).find(a):this.constructor(b).find(a);if(e[1]){if(b=b instanceof r?b[0]:b,r.merge(this,r.parseHTML(e[1],b&&b.nodeType?b.ownerDocument||b:d,!0)),B.test(e[1])&&r.isPlainObject(b))for(e in b)r.isFunction(this[e])?this[e](b[e]):this.attr(e,b[e]);return this}return f=d.getElementById(e[2]),f&&(this[0]=f,this.length=1),this}return a.nodeType?(this[0]=a,this.length=1,this):r.isFunction(a)?void 0!==c.ready?c.ready(a):a(r):r.makeArray(a,this)};G.prototype=r.fn,E=r(d);var H=/^(?:parents|prev(?:Until|All))/,I={children:!0,contents:!0,next:!0,prev:!0};r.fn.extend({has:function(a){var b=r(a,this),c=b.length;return this.filter(function(){for(var a=0;a<c;a++)if(r.contains(this,b[a]))return!0})},closest:function(a,b){var c,d=0,e=this.length,f=[],g="string"!=typeof a&&r(a);if(!A.test(a))for(;d<e;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&r.find.matchesSelector(c,a))){f.push(c);break}return this.pushStack(f.length>1?r.uniqueSort(f):f)},index:function(a){return a?"string"==typeof a?i.call(r(a),this[0]):i.call(this,a.jquery?a[0]:a):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){return this.pushStack(r.uniqueSort(r.merge(this.get(),r(a,b))))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}});function J(a,b){while((a=a[b])&&1!==a.nodeType);return a}r.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return y(a,"parentNode")},parentsUntil:function(a,b,c){return y(a,"parentNode",c)},next:function(a){return J(a,"nextSibling")},prev:function(a){return J(a,"previousSibling")},nextAll:function(a){return y(a,"nextSibling")},prevAll:function(a){return y(a,"previousSibling")},nextUntil:function(a,b,c){return y(a,"nextSibling",c)},prevUntil:function(a,b,c){return y(a,"previousSibling",c)},siblings:function(a){return z((a.parentNode||{}).firstChild,a)},children:function(a){return z(a.firstChild)},contents:function(a){return a.contentDocument||r.merge([],a.childNodes)}},function(a,b){r.fn[a]=function(c,d){var e=r.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=r.filter(d,e)),this.length>1&&(I[a]||r.uniqueSort(e),H.test(a)&&e.reverse()),this.pushStack(e)}});var K=/[^\x20\t\r\n\f]+/g;function L(a){var b={};return r.each(a.match(K)||[],function(a,c){b[c]=!0}),b}r.Callbacks=function(a){a="string"==typeof a?L(a):r.extend({},a);var b,c,d,e,f=[],g=[],h=-1,i=function(){for(e=a.once,d=b=!0;g.length;h=-1){c=g.shift();while(++h<f.length)f[h].apply(c[0],c[1])===!1&&a.stopOnFalse&&(h=f.length,c=!1)}a.memory||(c=!1),b=!1,e&&(f=c?[]:"")},j={add:function(){return f&&(c&&!b&&(h=f.length-1,g.push(c)),function d(b){r.each(b,function(b,c){r.isFunction(c)?a.unique&&j.has(c)||f.push(c):c&&c.length&&"string"!==r.type(c)&&d(c)})}(arguments),c&&!b&&i()),this},remove:function(){return r.each(arguments,function(a,b){var c;while((c=r.inArray(b,f,c))>-1)f.splice(c,1),c<=h&&h--}),this},has:function(a){return a?r.inArray(a,f)>-1:f.length>0},empty:function(){return f&&(f=[]),this},disable:function(){return e=g=[],f=c="",this},disabled:function(){return!f},lock:function(){return e=g=[],c||b||(f=c=""),this},locked:function(){return!!e},fireWith:function(a,c){return e||(c=c||[],c=[a,c.slice?c.slice():c],g.push(c),b||i()),this},fire:function(){return j.fireWith(this,arguments),this},fired:function(){return!!d}};return j};function M(a){return a}function N(a){throw a}function O(a,b,c){var d;try{a&&r.isFunction(d=a.promise)?d.call(a).done(b).fail(c):a&&r.isFunction(d=a.then)?d.call(a,b,c):b.call(void 0,a)}catch(a){c.call(void 0,a)}}r.extend({Deferred:function(b){var c=[["notify","progress",r.Callbacks("memory"),r.Callbacks("memory"),2],["resolve","done",r.Callbacks("once memory"),r.Callbacks("once memory"),0,"resolved"],["reject","fail",r.Callbacks("once memory"),r.Callbacks("once memory"),1,"rejected"]],d="pending",e={state:function(){return d},always:function(){return f.done(arguments).fail(arguments),this},"catch":function(a){return e.then(null,a)},pipe:function(){var a=arguments;return r.Deferred(function(b){r.each(c,function(c,d){var e=r.isFunction(a[d[4]])&&a[d[4]];f[d[1]](function(){var a=e&&e.apply(this,arguments);a&&r.isFunction(a.promise)?a.promise().progress(b.notify).done(b.resolve).fail(b.reject):b[d[0]+"With"](this,e?[a]:arguments)})}),a=null}).promise()},then:function(b,d,e){var f=0;function g(b,c,d,e){return function(){var h=this,i=arguments,j=function(){var a,j;if(!(b<f)){if(a=d.apply(h,i),a===c.promise())throw new TypeError("Thenable self-resolution");j=a&&("object"==typeof a||"function"==typeof a)&&a.then,r.isFunction(j)?e?j.call(a,g(f,c,M,e),g(f,c,N,e)):(f++,j.call(a,g(f,c,M,e),g(f,c,N,e),g(f,c,M,c.notifyWith))):(d!==M&&(h=void 0,i=[a]),(e||c.resolveWith)(h,i))}},k=e?j:function(){try{j()}catch(a){r.Deferred.exceptionHook&&r.Deferred.exceptionHook(a,k.stackTrace),b+1>=f&&(d!==N&&(h=void 0,i=[a]),c.rejectWith(h,i))}};b?k():(r.Deferred.getStackHook&&(k.stackTrace=r.Deferred.getStackHook()),a.setTimeout(k))}}return r.Deferred(function(a){c[0][3].add(g(0,a,r.isFunction(e)?e:M,a.notifyWith)),c[1][3].add(g(0,a,r.isFunction(b)?b:M)),c[2][3].add(g(0,a,r.isFunction(d)?d:N))}).promise()},promise:function(a){return null!=a?r.extend(a,e):e}},f={};return r.each(c,function(a,b){var g=b[2],h=b[5];e[b[1]]=g.add,h&&g.add(function(){d=h},c[3-a][2].disable,c[0][2].lock),g.add(b[3].fire),f[b[0]]=function(){return f[b[0]+"With"](this===f?void 0:this,arguments),this},f[b[0]+"With"]=g.fireWith}),e.promise(f),b&&b.call(f,f),f},when:function(a){var b=arguments.length,c=b,d=Array(c),e=f.call(arguments),g=r.Deferred(),h=function(a){return function(c){d[a]=this,e[a]=arguments.length>1?f.call(arguments):c,--b||g.resolveWith(d,e)}};if(b<=1&&(O(a,g.done(h(c)).resolve,g.reject),"pending"===g.state()||r.isFunction(e[c]&&e[c].then)))return g.then();while(c--)O(e[c],h(c),g.reject);return g.promise()}});var P=/^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;r.Deferred.exceptionHook=function(b,c){a.console&&a.console.warn&&b&&P.test(b.name)&&a.console.warn("jQuery.Deferred exception: "+b.message,b.stack,c)},r.readyException=function(b){a.setTimeout(function(){throw b})};var Q=r.Deferred();r.fn.ready=function(a){return Q.then(a)["catch"](function(a){r.readyException(a)}),this},r.extend({isReady:!1,readyWait:1,holdReady:function(a){a?r.readyWait++:r.ready(!0)},ready:function(a){(a===!0?--r.readyWait:r.isReady)||(r.isReady=!0,a!==!0&&--r.readyWait>0||Q.resolveWith(d,[r]))}}),r.ready.then=Q.then;function R(){d.removeEventListener("DOMContentLoaded",R),
a.removeEventListener("load",R),r.ready()}"complete"===d.readyState||"loading"!==d.readyState&&!d.documentElement.doScroll?a.setTimeout(r.ready):(d.addEventListener("DOMContentLoaded",R),a.addEventListener("load",R));var S=function(a,b,c,d,e,f,g){var h=0,i=a.length,j=null==c;if("object"===r.type(c)){e=!0;for(h in c)S(a,b,h,c[h],!0,f,g)}else if(void 0!==d&&(e=!0,r.isFunction(d)||(g=!0),j&&(g?(b.call(a,d),b=null):(j=b,b=function(a,b,c){return j.call(r(a),c)})),b))for(;h<i;h++)b(a[h],c,g?d:d.call(a[h],h,b(a[h],c)));return e?a:j?b.call(a):i?b(a[0],c):f},T=function(a){return 1===a.nodeType||9===a.nodeType||!+a.nodeType};function U(){this.expando=r.expando+U.uid++}U.uid=1,U.prototype={cache:function(a){var b=a[this.expando];return b||(b={},T(a)&&(a.nodeType?a[this.expando]=b:Object.defineProperty(a,this.expando,{value:b,configurable:!0}))),b},set:function(a,b,c){var d,e=this.cache(a);if("string"==typeof b)e[r.camelCase(b)]=c;else for(d in b)e[r.camelCase(d)]=b[d];return e},get:function(a,b){return void 0===b?this.cache(a):a[this.expando]&&a[this.expando][r.camelCase(b)]},access:function(a,b,c){return void 0===b||b&&"string"==typeof b&&void 0===c?this.get(a,b):(this.set(a,b,c),void 0!==c?c:b)},remove:function(a,b){var c,d=a[this.expando];if(void 0!==d){if(void 0!==b){r.isArray(b)?b=b.map(r.camelCase):(b=r.camelCase(b),b=b in d?[b]:b.match(K)||[]),c=b.length;while(c--)delete d[b[c]]}(void 0===b||r.isEmptyObject(d))&&(a.nodeType?a[this.expando]=void 0:delete a[this.expando])}},hasData:function(a){var b=a[this.expando];return void 0!==b&&!r.isEmptyObject(b)}};var V=new U,W=new U,X=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,Y=/[A-Z]/g;function Z(a){return"true"===a||"false"!==a&&("null"===a?null:a===+a+""?+a:X.test(a)?JSON.parse(a):a)}function $(a,b,c){var d;if(void 0===c&&1===a.nodeType)if(d="data-"+b.replace(Y,"-$&").toLowerCase(),c=a.getAttribute(d),"string"==typeof c){try{c=Z(c)}catch(e){}W.set(a,b,c)}else c=void 0;return c}r.extend({hasData:function(a){return W.hasData(a)||V.hasData(a)},data:function(a,b,c){return W.access(a,b,c)},removeData:function(a,b){W.remove(a,b)},_data:function(a,b,c){return V.access(a,b,c)},_removeData:function(a,b){V.remove(a,b)}}),r.fn.extend({data:function(a,b){var c,d,e,f=this[0],g=f&&f.attributes;if(void 0===a){if(this.length&&(e=W.get(f),1===f.nodeType&&!V.get(f,"hasDataAttrs"))){c=g.length;while(c--)g[c]&&(d=g[c].name,0===d.indexOf("data-")&&(d=r.camelCase(d.slice(5)),$(f,d,e[d])));V.set(f,"hasDataAttrs",!0)}return e}return"object"==typeof a?this.each(function(){W.set(this,a)}):S(this,function(b){var c;if(f&&void 0===b){if(c=W.get(f,a),void 0!==c)return c;if(c=$(f,a),void 0!==c)return c}else this.each(function(){W.set(this,a,b)})},null,b,arguments.length>1,null,!0)},removeData:function(a){return this.each(function(){W.remove(this,a)})}}),r.extend({queue:function(a,b,c){var d;if(a)return b=(b||"fx")+"queue",d=V.get(a,b),c&&(!d||r.isArray(c)?d=V.access(a,b,r.makeArray(c)):d.push(c)),d||[]},dequeue:function(a,b){b=b||"fx";var c=r.queue(a,b),d=c.length,e=c.shift(),f=r._queueHooks(a,b),g=function(){r.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return V.get(a,c)||V.access(a,c,{empty:r.Callbacks("once memory").add(function(){V.remove(a,[b+"queue",c])})})}}),r.fn.extend({queue:function(a,b){var c=2;return"string"!=typeof a&&(b=a,a="fx",c--),arguments.length<c?r.queue(this[0],a):void 0===b?this:this.each(function(){var c=r.queue(this,a,b);r._queueHooks(this,a),"fx"===a&&"inprogress"!==c[0]&&r.dequeue(this,a)})},dequeue:function(a){return this.each(function(){r.dequeue(this,a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,b){var c,d=1,e=r.Deferred(),f=this,g=this.length,h=function(){--d||e.resolveWith(f,[f])};"string"!=typeof a&&(b=a,a=void 0),a=a||"fx";while(g--)c=V.get(f[g],a+"queueHooks"),c&&c.empty&&(d++,c.empty.add(h));return h(),e.promise(b)}});var _=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,aa=new RegExp("^(?:([+-])=|)("+_+")([a-z%]*)$","i"),ba=["Top","Right","Bottom","Left"],ca=function(a,b){return a=b||a,"none"===a.style.display||""===a.style.display&&r.contains(a.ownerDocument,a)&&"none"===r.css(a,"display")},da=function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e};function ea(a,b,c,d){var e,f=1,g=20,h=d?function(){return d.cur()}:function(){return r.css(a,b,"")},i=h(),j=c&&c[3]||(r.cssNumber[b]?"":"px"),k=(r.cssNumber[b]||"px"!==j&&+i)&&aa.exec(r.css(a,b));if(k&&k[3]!==j){j=j||k[3],c=c||[],k=+i||1;do f=f||".5",k/=f,r.style(a,b,k+j);while(f!==(f=h()/i)&&1!==f&&--g)}return c&&(k=+k||+i||0,e=c[1]?k+(c[1]+1)*c[2]:+c[2],d&&(d.unit=j,d.start=k,d.end=e)),e}var fa={};function ga(a){var b,c=a.ownerDocument,d=a.nodeName,e=fa[d];return e?e:(b=c.body.appendChild(c.createElement(d)),e=r.css(b,"display"),b.parentNode.removeChild(b),"none"===e&&(e="block"),fa[d]=e,e)}function ha(a,b){for(var c,d,e=[],f=0,g=a.length;f<g;f++)d=a[f],d.style&&(c=d.style.display,b?("none"===c&&(e[f]=V.get(d,"display")||null,e[f]||(d.style.display="")),""===d.style.display&&ca(d)&&(e[f]=ga(d))):"none"!==c&&(e[f]="none",V.set(d,"display",c)));for(f=0;f<g;f++)null!=e[f]&&(a[f].style.display=e[f]);return a}r.fn.extend({show:function(){return ha(this,!0)},hide:function(){return ha(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){ca(this)?r(this).show():r(this).hide()})}});var ia=/^(?:checkbox|radio)$/i,ja=/<([a-z][^\/\0>\x20\t\r\n\f]+)/i,ka=/^$|\/(?:java|ecma)script/i,la={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};la.optgroup=la.option,la.tbody=la.tfoot=la.colgroup=la.caption=la.thead,la.th=la.td;function ma(a,b){var c;return c="undefined"!=typeof a.getElementsByTagName?a.getElementsByTagName(b||"*"):"undefined"!=typeof a.querySelectorAll?a.querySelectorAll(b||"*"):[],void 0===b||b&&r.nodeName(a,b)?r.merge([a],c):c}function na(a,b){for(var c=0,d=a.length;c<d;c++)V.set(a[c],"globalEval",!b||V.get(b[c],"globalEval"))}var oa=/<|&#?\w+;/;function pa(a,b,c,d,e){for(var f,g,h,i,j,k,l=b.createDocumentFragment(),m=[],n=0,o=a.length;n<o;n++)if(f=a[n],f||0===f)if("object"===r.type(f))r.merge(m,f.nodeType?[f]:f);else if(oa.test(f)){g=g||l.appendChild(b.createElement("div")),h=(ja.exec(f)||["",""])[1].toLowerCase(),i=la[h]||la._default,g.innerHTML=i[1]+r.htmlPrefilter(f)+i[2],k=i[0];while(k--)g=g.lastChild;r.merge(m,g.childNodes),g=l.firstChild,g.textContent=""}else m.push(b.createTextNode(f));l.textContent="",n=0;while(f=m[n++])if(d&&r.inArray(f,d)>-1)e&&e.push(f);else if(j=r.contains(f.ownerDocument,f),g=ma(l.appendChild(f),"script"),j&&na(g),c){k=0;while(f=g[k++])ka.test(f.type||"")&&c.push(f)}return l}!function(){var a=d.createDocumentFragment(),b=a.appendChild(d.createElement("div")),c=d.createElement("input");c.setAttribute("type","radio"),c.setAttribute("checked","checked"),c.setAttribute("name","t"),b.appendChild(c),o.checkClone=b.cloneNode(!0).cloneNode(!0).lastChild.checked,b.innerHTML="<textarea>x</textarea>",o.noCloneChecked=!!b.cloneNode(!0).lastChild.defaultValue}();var qa=d.documentElement,ra=/^key/,sa=/^(?:mouse|pointer|contextmenu|drag|drop)|click/,ta=/^([^.]*)(?:\.(.+)|)/;function ua(){return!0}function va(){return!1}function wa(){try{return d.activeElement}catch(a){}}function xa(a,b,c,d,e,f){var g,h;if("object"==typeof b){"string"!=typeof c&&(d=d||c,c=void 0);for(h in b)xa(a,h,c,d,b[h],f);return a}if(null==d&&null==e?(e=c,d=c=void 0):null==e&&("string"==typeof c?(e=d,d=void 0):(e=d,d=c,c=void 0)),e===!1)e=va;else if(!e)return a;return 1===f&&(g=e,e=function(a){return r().off(a),g.apply(this,arguments)},e.guid=g.guid||(g.guid=r.guid++)),a.each(function(){r.event.add(this,b,e,d,c)})}r.event={global:{},add:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,n,o,p,q=V.get(a);if(q){c.handler&&(f=c,c=f.handler,e=f.selector),e&&r.find.matchesSelector(qa,e),c.guid||(c.guid=r.guid++),(i=q.events)||(i=q.events={}),(g=q.handle)||(g=q.handle=function(b){return"undefined"!=typeof r&&r.event.triggered!==b.type?r.event.dispatch.apply(a,arguments):void 0}),b=(b||"").match(K)||[""],j=b.length;while(j--)h=ta.exec(b[j])||[],n=p=h[1],o=(h[2]||"").split(".").sort(),n&&(l=r.event.special[n]||{},n=(e?l.delegateType:l.bindType)||n,l=r.event.special[n]||{},k=r.extend({type:n,origType:p,data:d,handler:c,guid:c.guid,selector:e,needsContext:e&&r.expr.match.needsContext.test(e),namespace:o.join(".")},f),(m=i[n])||(m=i[n]=[],m.delegateCount=0,l.setup&&l.setup.call(a,d,o,g)!==!1||a.addEventListener&&a.addEventListener(n,g)),l.add&&(l.add.call(a,k),k.handler.guid||(k.handler.guid=c.guid)),e?m.splice(m.delegateCount++,0,k):m.push(k),r.event.global[n]=!0)}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,n,o,p,q=V.hasData(a)&&V.get(a);if(q&&(i=q.events)){b=(b||"").match(K)||[""],j=b.length;while(j--)if(h=ta.exec(b[j])||[],n=p=h[1],o=(h[2]||"").split(".").sort(),n){l=r.event.special[n]||{},n=(d?l.delegateType:l.bindType)||n,m=i[n]||[],h=h[2]&&new RegExp("(^|\\.)"+o.join("\\.(?:.*\\.|)")+"(\\.|$)"),g=f=m.length;while(f--)k=m[f],!e&&p!==k.origType||c&&c.guid!==k.guid||h&&!h.test(k.namespace)||d&&d!==k.selector&&("**"!==d||!k.selector)||(m.splice(f,1),k.selector&&m.delegateCount--,l.remove&&l.remove.call(a,k));g&&!m.length&&(l.teardown&&l.teardown.call(a,o,q.handle)!==!1||r.removeEvent(a,n,q.handle),delete i[n])}else for(n in i)r.event.remove(a,n+b[j],c,d,!0);r.isEmptyObject(i)&&V.remove(a,"handle events")}},dispatch:function(a){var b=r.event.fix(a),c,d,e,f,g,h,i=new Array(arguments.length),j=(V.get(this,"events")||{})[b.type]||[],k=r.event.special[b.type]||{};for(i[0]=b,c=1;c<arguments.length;c++)i[c]=arguments[c];if(b.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,b)!==!1){h=r.event.handlers.call(this,b,j),c=0;while((f=h[c++])&&!b.isPropagationStopped()){b.currentTarget=f.elem,d=0;while((g=f.handlers[d++])&&!b.isImmediatePropagationStopped())b.rnamespace&&!b.rnamespace.test(g.namespace)||(b.handleObj=g,b.data=g.data,e=((r.event.special[g.origType]||{}).handle||g.handler).apply(f.elem,i),void 0!==e&&(b.result=e)===!1&&(b.preventDefault(),b.stopPropagation()))}return k.postDispatch&&k.postDispatch.call(this,b),b.result}},handlers:function(a,b){var c,d,e,f,g,h=[],i=b.delegateCount,j=a.target;if(i&&j.nodeType&&!("click"===a.type&&a.button>=1))for(;j!==this;j=j.parentNode||this)if(1===j.nodeType&&("click"!==a.type||j.disabled!==!0)){for(f=[],g={},c=0;c<i;c++)d=b[c],e=d.selector+" ",void 0===g[e]&&(g[e]=d.needsContext?r(e,this).index(j)>-1:r.find(e,this,null,[j]).length),g[e]&&f.push(d);f.length&&h.push({elem:j,handlers:f})}return j=this,i<b.length&&h.push({elem:j,handlers:b.slice(i)}),h},addProp:function(a,b){Object.defineProperty(r.Event.prototype,a,{enumerable:!0,configurable:!0,get:r.isFunction(b)?function(){if(this.originalEvent)return b(this.originalEvent)}:function(){if(this.originalEvent)return this.originalEvent[a]},set:function(b){Object.defineProperty(this,a,{enumerable:!0,configurable:!0,writable:!0,value:b})}})},fix:function(a){return a[r.expando]?a:new r.Event(a)},special:{load:{noBubble:!0},focus:{trigger:function(){if(this!==wa()&&this.focus)return this.focus(),!1},delegateType:"focusin"},blur:{trigger:function(){if(this===wa()&&this.blur)return this.blur(),!1},delegateType:"focusout"},click:{trigger:function(){if("checkbox"===this.type&&this.click&&r.nodeName(this,"input"))return this.click(),!1},_default:function(a){return r.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){void 0!==a.result&&a.originalEvent&&(a.originalEvent.returnValue=a.result)}}}},r.removeEvent=function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c)},r.Event=function(a,b){return this instanceof r.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||void 0===a.defaultPrevented&&a.returnValue===!1?ua:va,this.target=a.target&&3===a.target.nodeType?a.target.parentNode:a.target,this.currentTarget=a.currentTarget,this.relatedTarget=a.relatedTarget):this.type=a,b&&r.extend(this,b),this.timeStamp=a&&a.timeStamp||r.now(),void(this[r.expando]=!0)):new r.Event(a,b)},r.Event.prototype={constructor:r.Event,isDefaultPrevented:va,isPropagationStopped:va,isImmediatePropagationStopped:va,isSimulated:!1,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=ua,a&&!this.isSimulated&&a.preventDefault()},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=ua,a&&!this.isSimulated&&a.stopPropagation()},stopImmediatePropagation:function(){var a=this.originalEvent;this.isImmediatePropagationStopped=ua,a&&!this.isSimulated&&a.stopImmediatePropagation(),this.stopPropagation()}},r.each({altKey:!0,bubbles:!0,cancelable:!0,changedTouches:!0,ctrlKey:!0,detail:!0,eventPhase:!0,metaKey:!0,pageX:!0,pageY:!0,shiftKey:!0,view:!0,"char":!0,charCode:!0,key:!0,keyCode:!0,button:!0,buttons:!0,clientX:!0,clientY:!0,offsetX:!0,offsetY:!0,pointerId:!0,pointerType:!0,screenX:!0,screenY:!0,targetTouches:!0,toElement:!0,touches:!0,which:function(a){var b=a.button;return null==a.which&&ra.test(a.type)?null!=a.charCode?a.charCode:a.keyCode:!a.which&&void 0!==b&&sa.test(a.type)?1&b?1:2&b?3:4&b?2:0:a.which}},r.event.addProp),r.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(a,b){r.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return e&&(e===d||r.contains(d,e))||(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),r.fn.extend({on:function(a,b,c,d){return xa(this,a,b,c,d)},one:function(a,b,c,d){return xa(this,a,b,c,d,1)},off:function(a,b,c){var d,e;if(a&&a.preventDefault&&a.handleObj)return d=a.handleObj,r(a.delegateTarget).off(d.namespace?d.origType+"."+d.namespace:d.origType,d.selector,d.handler),this;if("object"==typeof a){for(e in a)this.off(e,b,a[e]);return this}return b!==!1&&"function"!=typeof b||(c=b,b=void 0),c===!1&&(c=va),this.each(function(){r.event.remove(this,a,c,b)})}});var ya=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,za=/<script|<style|<link/i,Aa=/checked\s*(?:[^=]|=\s*.checked.)/i,Ba=/^true\/(.*)/,Ca=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function Da(a,b){return r.nodeName(a,"table")&&r.nodeName(11!==b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a:a}function Ea(a){return a.type=(null!==a.getAttribute("type"))+"/"+a.type,a}function Fa(a){var b=Ba.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function Ga(a,b){var c,d,e,f,g,h,i,j;if(1===b.nodeType){if(V.hasData(a)&&(f=V.access(a),g=V.set(b,f),j=f.events)){delete g.handle,g.events={};for(e in j)for(c=0,d=j[e].length;c<d;c++)r.event.add(b,e,j[e][c])}W.hasData(a)&&(h=W.access(a),i=r.extend({},h),W.set(b,i))}}function Ha(a,b){var c=b.nodeName.toLowerCase();"input"===c&&ia.test(a.type)?b.checked=a.checked:"input"!==c&&"textarea"!==c||(b.defaultValue=a.defaultValue)}function Ia(a,b,c,d){b=g.apply([],b);var e,f,h,i,j,k,l=0,m=a.length,n=m-1,q=b[0],s=r.isFunction(q);if(s||m>1&&"string"==typeof q&&!o.checkClone&&Aa.test(q))return a.each(function(e){var f=a.eq(e);s&&(b[0]=q.call(this,e,f.html())),Ia(f,b,c,d)});if(m&&(e=pa(b,a[0].ownerDocument,!1,a,d),f=e.firstChild,1===e.childNodes.length&&(e=f),f||d)){for(h=r.map(ma(e,"script"),Ea),i=h.length;l<m;l++)j=e,l!==n&&(j=r.clone(j,!0,!0),i&&r.merge(h,ma(j,"script"))),c.call(a[l],j,l);if(i)for(k=h[h.length-1].ownerDocument,r.map(h,Fa),l=0;l<i;l++)j=h[l],ka.test(j.type||"")&&!V.access(j,"globalEval")&&r.contains(k,j)&&(j.src?r._evalUrl&&r._evalUrl(j.src):p(j.textContent.replace(Ca,""),k))}return a}function Ja(a,b,c){for(var d,e=b?r.filter(b,a):a,f=0;null!=(d=e[f]);f++)c||1!==d.nodeType||r.cleanData(ma(d)),d.parentNode&&(c&&r.contains(d.ownerDocument,d)&&na(ma(d,"script")),d.parentNode.removeChild(d));return a}r.extend({htmlPrefilter:function(a){return a.replace(ya,"<$1></$2>")},clone:function(a,b,c){var d,e,f,g,h=a.cloneNode(!0),i=r.contains(a.ownerDocument,a);if(!(o.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||r.isXMLDoc(a)))for(g=ma(h),f=ma(a),d=0,e=f.length;d<e;d++)Ha(f[d],g[d]);if(b)if(c)for(f=f||ma(a),g=g||ma(h),d=0,e=f.length;d<e;d++)Ga(f[d],g[d]);else Ga(a,h);return g=ma(h,"script"),g.length>0&&na(g,!i&&ma(a,"script")),h},cleanData:function(a){for(var b,c,d,e=r.event.special,f=0;void 0!==(c=a[f]);f++)if(T(c)){if(b=c[V.expando]){if(b.events)for(d in b.events)e[d]?r.event.remove(c,d):r.removeEvent(c,d,b.handle);c[V.expando]=void 0}c[W.expando]&&(c[W.expando]=void 0)}}}),r.fn.extend({detach:function(a){return Ja(this,a,!0)},remove:function(a){return Ja(this,a)},text:function(a){return S(this,function(a){return void 0===a?r.text(this):this.empty().each(function(){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||(this.textContent=a)})},null,a,arguments.length)},append:function(){return Ia(this,arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=Da(this,a);b.appendChild(a)}})},prepend:function(){return Ia(this,arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=Da(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return Ia(this,arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return Ia(this,arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},empty:function(){for(var a,b=0;null!=(a=this[b]);b++)1===a.nodeType&&(r.cleanData(ma(a,!1)),a.textContent="");return this},clone:function(a,b){return a=null!=a&&a,b=null==b?a:b,this.map(function(){return r.clone(this,a,b)})},html:function(a){return S(this,function(a){var b=this[0]||{},c=0,d=this.length;if(void 0===a&&1===b.nodeType)return b.innerHTML;if("string"==typeof a&&!za.test(a)&&!la[(ja.exec(a)||["",""])[1].toLowerCase()]){a=r.htmlPrefilter(a);try{for(;c<d;c++)b=this[c]||{},1===b.nodeType&&(r.cleanData(ma(b,!1)),b.innerHTML=a);b=0}catch(e){}}b&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=[];return Ia(this,arguments,function(b){var c=this.parentNode;r.inArray(this,a)<0&&(r.cleanData(ma(this)),c&&c.replaceChild(b,this))},a)}}),r.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){r.fn[a]=function(a){for(var c,d=[],e=r(a),f=e.length-1,g=0;g<=f;g++)c=g===f?this:this.clone(!0),r(e[g])[b](c),h.apply(d,c.get());return this.pushStack(d)}});var Ka=/^margin/,La=new RegExp("^("+_+")(?!px)[a-z%]+$","i"),Ma=function(b){var c=b.ownerDocument.defaultView;return c&&c.opener||(c=a),c.getComputedStyle(b)};!function(){function b(){if(i){i.style.cssText="box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%",i.innerHTML="",qa.appendChild(h);var b=a.getComputedStyle(i);c="1%"!==b.top,g="2px"===b.marginLeft,e="4px"===b.width,i.style.marginRight="50%",f="4px"===b.marginRight,qa.removeChild(h),i=null}}var c,e,f,g,h=d.createElement("div"),i=d.createElement("div");i.style&&(i.style.backgroundClip="content-box",i.cloneNode(!0).style.backgroundClip="",o.clearCloneStyle="content-box"===i.style.backgroundClip,h.style.cssText="border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute",h.appendChild(i),r.extend(o,{pixelPosition:function(){return b(),c},boxSizingReliable:function(){return b(),e},pixelMarginRight:function(){return b(),f},reliableMarginLeft:function(){return b(),g}}))}();function Na(a,b,c){var d,e,f,g,h=a.style;return c=c||Ma(a),c&&(g=c.getPropertyValue(b)||c[b],""!==g||r.contains(a.ownerDocument,a)||(g=r.style(a,b)),!o.pixelMarginRight()&&La.test(g)&&Ka.test(b)&&(d=h.width,e=h.minWidth,f=h.maxWidth,h.minWidth=h.maxWidth=h.width=g,g=c.width,h.width=d,h.minWidth=e,h.maxWidth=f)),void 0!==g?g+"":g}function Oa(a,b){return{get:function(){return a()?void delete this.get:(this.get=b).apply(this,arguments)}}}var Pa=/^(none|table(?!-c[ea]).+)/,Qa={position:"absolute",visibility:"hidden",display:"block"},Ra={letterSpacing:"0",fontWeight:"400"},Sa=["Webkit","Moz","ms"],Ta=d.createElement("div").style;function Ua(a){if(a in Ta)return a;var b=a[0].toUpperCase()+a.slice(1),c=Sa.length;while(c--)if(a=Sa[c]+b,a in Ta)return a}function Va(a,b,c){var d=aa.exec(b);return d?Math.max(0,d[2]-(c||0))+(d[3]||"px"):b}function Wa(a,b,c,d,e){var f,g=0;for(f=c===(d?"border":"content")?4:"width"===b?1:0;f<4;f+=2)"margin"===c&&(g+=r.css(a,c+ba[f],!0,e)),d?("content"===c&&(g-=r.css(a,"padding"+ba[f],!0,e)),"margin"!==c&&(g-=r.css(a,"border"+ba[f]+"Width",!0,e))):(g+=r.css(a,"padding"+ba[f],!0,e),"padding"!==c&&(g+=r.css(a,"border"+ba[f]+"Width",!0,e)));return g}function Xa(a,b,c){var d,e=!0,f=Ma(a),g="border-box"===r.css(a,"boxSizing",!1,f);if(a.getClientRects().length&&(d=a.getBoundingClientRect()[b]),d<=0||null==d){if(d=Na(a,b,f),(d<0||null==d)&&(d=a.style[b]),La.test(d))return d;e=g&&(o.boxSizingReliable()||d===a.style[b]),d=parseFloat(d)||0}return d+Wa(a,b,c||(g?"border":"content"),e,f)+"px"}r.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=Na(a,"opacity");return""===c?"1":c}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":"cssFloat"},style:function(a,b,c,d){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var e,f,g,h=r.camelCase(b),i=a.style;return b=r.cssProps[h]||(r.cssProps[h]=Ua(h)||h),g=r.cssHooks[b]||r.cssHooks[h],void 0===c?g&&"get"in g&&void 0!==(e=g.get(a,!1,d))?e:i[b]:(f=typeof c,"string"===f&&(e=aa.exec(c))&&e[1]&&(c=ea(a,b,e),f="number"),null!=c&&c===c&&("number"===f&&(c+=e&&e[3]||(r.cssNumber[h]?"":"px")),o.clearCloneStyle||""!==c||0!==b.indexOf("background")||(i[b]="inherit"),g&&"set"in g&&void 0===(c=g.set(a,c,d))||(i[b]=c)),void 0)}},css:function(a,b,c,d){var e,f,g,h=r.camelCase(b);return b=r.cssProps[h]||(r.cssProps[h]=Ua(h)||h),g=r.cssHooks[b]||r.cssHooks[h],g&&"get"in g&&(e=g.get(a,!0,c)),void 0===e&&(e=Na(a,b,d)),"normal"===e&&b in Ra&&(e=Ra[b]),""===c||c?(f=parseFloat(e),c===!0||isFinite(f)?f||0:e):e}}),r.each(["height","width"],function(a,b){r.cssHooks[b]={get:function(a,c,d){if(c)return!Pa.test(r.css(a,"display"))||a.getClientRects().length&&a.getBoundingClientRect().width?Xa(a,b,d):da(a,Qa,function(){return Xa(a,b,d)})},set:function(a,c,d){var e,f=d&&Ma(a),g=d&&Wa(a,b,d,"border-box"===r.css(a,"boxSizing",!1,f),f);return g&&(e=aa.exec(c))&&"px"!==(e[3]||"px")&&(a.style[b]=c,c=r.css(a,b)),Va(a,c,g)}}}),r.cssHooks.marginLeft=Oa(o.reliableMarginLeft,function(a,b){if(b)return(parseFloat(Na(a,"marginLeft"))||a.getBoundingClientRect().left-da(a,{marginLeft:0},function(){return a.getBoundingClientRect().left}))+"px"}),r.each({margin:"",padding:"",border:"Width"},function(a,b){r.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];d<4;d++)e[a+ba[d]+b]=f[d]||f[d-2]||f[0];return e}},Ka.test(a)||(r.cssHooks[a+b].set=Va)}),r.fn.extend({css:function(a,b){return S(this,function(a,b,c){var d,e,f={},g=0;if(r.isArray(b)){for(d=Ma(a),e=b.length;g<e;g++)f[b[g]]=r.css(a,b[g],!1,d);return f}return void 0!==c?r.style(a,b,c):r.css(a,b)},a,b,arguments.length>1)}});function Ya(a,b,c,d,e){return new Ya.prototype.init(a,b,c,d,e)}r.Tween=Ya,Ya.prototype={constructor:Ya,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||r.easing._default,this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(r.cssNumber[c]?"":"px")},cur:function(){var a=Ya.propHooks[this.prop];return a&&a.get?a.get(this):Ya.propHooks._default.get(this)},run:function(a){var b,c=Ya.propHooks[this.prop];return this.options.duration?this.pos=b=r.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):Ya.propHooks._default.set(this),this}},Ya.prototype.init.prototype=Ya.prototype,Ya.propHooks={_default:{get:function(a){var b;return 1!==a.elem.nodeType||null!=a.elem[a.prop]&&null==a.elem.style[a.prop]?a.elem[a.prop]:(b=r.css(a.elem,a.prop,""),b&&"auto"!==b?b:0)},set:function(a){r.fx.step[a.prop]?r.fx.step[a.prop](a):1!==a.elem.nodeType||null==a.elem.style[r.cssProps[a.prop]]&&!r.cssHooks[a.prop]?a.elem[a.prop]=a.now:r.style(a.elem,a.prop,a.now+a.unit)}}},Ya.propHooks.scrollTop=Ya.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},r.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2},_default:"swing"},r.fx=Ya.prototype.init,r.fx.step={};var Za,$a,_a=/^(?:toggle|show|hide)$/,ab=/queueHooks$/;function bb(){$a&&(a.requestAnimationFrame(bb),r.fx.tick())}function cb(){return a.setTimeout(function(){Za=void 0}),Za=r.now()}function db(a,b){var c,d=0,e={height:a};for(b=b?1:0;d<4;d+=2-b)c=ba[d],e["margin"+c]=e["padding"+c]=a;return b&&(e.opacity=e.width=a),e}function eb(a,b,c){for(var d,e=(hb.tweeners[b]||[]).concat(hb.tweeners["*"]),f=0,g=e.length;f<g;f++)if(d=e[f].call(c,b,a))return d}function fb(a,b,c){var d,e,f,g,h,i,j,k,l="width"in b||"height"in b,m=this,n={},o=a.style,p=a.nodeType&&ca(a),q=V.get(a,"fxshow");c.queue||(g=r._queueHooks(a,"fx"),null==g.unqueued&&(g.unqueued=0,h=g.empty.fire,g.empty.fire=function(){g.unqueued||h()}),g.unqueued++,m.always(function(){m.always(function(){g.unqueued--,r.queue(a,"fx").length||g.empty.fire()})}));for(d in b)if(e=b[d],_a.test(e)){if(delete b[d],f=f||"toggle"===e,e===(p?"hide":"show")){if("show"!==e||!q||void 0===q[d])continue;p=!0}n[d]=q&&q[d]||r.style(a,d)}if(i=!r.isEmptyObject(b),i||!r.isEmptyObject(n)){l&&1===a.nodeType&&(c.overflow=[o.overflow,o.overflowX,o.overflowY],j=q&&q.display,null==j&&(j=V.get(a,"display")),k=r.css(a,"display"),"none"===k&&(j?k=j:(ha([a],!0),j=a.style.display||j,k=r.css(a,"display"),ha([a]))),("inline"===k||"inline-block"===k&&null!=j)&&"none"===r.css(a,"float")&&(i||(m.done(function(){o.display=j}),null==j&&(k=o.display,j="none"===k?"":k)),o.display="inline-block")),c.overflow&&(o.overflow="hidden",m.always(function(){o.overflow=c.overflow[0],o.overflowX=c.overflow[1],o.overflowY=c.overflow[2]})),i=!1;for(d in n)i||(q?"hidden"in q&&(p=q.hidden):q=V.access(a,"fxshow",{display:j}),f&&(q.hidden=!p),p&&ha([a],!0),m.done(function(){p||ha([a]),V.remove(a,"fxshow");for(d in n)r.style(a,d,n[d])})),i=eb(p?q[d]:0,d,m),d in q||(q[d]=i.start,p&&(i.end=i.start,i.start=0))}}function gb(a,b){var c,d,e,f,g;for(c in a)if(d=r.camelCase(c),e=b[d],f=a[c],r.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=r.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function hb(a,b,c){var d,e,f=0,g=hb.prefilters.length,h=r.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=Za||cb(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;g<i;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),f<1&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:r.extend({},b),opts:r.extend(!0,{specialEasing:{},easing:r.easing._default},c),originalProperties:b,originalOptions:c,startTime:Za||cb(),duration:c.duration,tweens:[],createTween:function(b,c){var d=r.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;c<d;c++)j.tweens[c].run(1);return b?(h.notifyWith(a,[j,1,0]),h.resolveWith(a,[j,b])):h.rejectWith(a,[j,b]),this}}),k=j.props;for(gb(k,j.opts.specialEasing);f<g;f++)if(d=hb.prefilters[f].call(j,a,k,j.opts))return r.isFunction(d.stop)&&(r._queueHooks(j.elem,j.opts.queue).stop=r.proxy(d.stop,d)),d;return r.map(k,eb,j),r.isFunction(j.opts.start)&&j.opts.start.call(a,j),r.fx.timer(r.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}r.Animation=r.extend(hb,{tweeners:{"*":[function(a,b){var c=this.createTween(a,b);return ea(c.elem,a,aa.exec(b),c),c}]},tweener:function(a,b){r.isFunction(a)?(b=a,a=["*"]):a=a.match(K);for(var c,d=0,e=a.length;d<e;d++)c=a[d],hb.tweeners[c]=hb.tweeners[c]||[],hb.tweeners[c].unshift(b)},prefilters:[fb],prefilter:function(a,b){b?hb.prefilters.unshift(a):hb.prefilters.push(a)}}),r.speed=function(a,b,c){var e=a&&"object"==typeof a?r.extend({},a):{complete:c||!c&&b||r.isFunction(a)&&a,duration:a,easing:c&&b||b&&!r.isFunction(b)&&b};return r.fx.off||d.hidden?e.duration=0:"number"!=typeof e.duration&&(e.duration in r.fx.speeds?e.duration=r.fx.speeds[e.duration]:e.duration=r.fx.speeds._default),null!=e.queue&&e.queue!==!0||(e.queue="fx"),e.old=e.complete,e.complete=function(){r.isFunction(e.old)&&e.old.call(this),e.queue&&r.dequeue(this,e.queue)},e},r.fn.extend({fadeTo:function(a,b,c,d){return this.filter(ca).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=r.isEmptyObject(a),f=r.speed(b,c,d),g=function(){var b=hb(this,r.extend({},a),f);(e||V.get(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,b,c){var d=function(a){var b=a.stop;delete a.stop,b(c)};return"string"!=typeof a&&(c=b,b=a,a=void 0),b&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,e=null!=a&&a+"queueHooks",f=r.timers,g=V.get(this);if(e)g[e]&&g[e].stop&&d(g[e]);else for(e in g)g[e]&&g[e].stop&&ab.test(e)&&d(g[e]);for(e=f.length;e--;)f[e].elem!==this||null!=a&&f[e].queue!==a||(f[e].anim.stop(c),b=!1,f.splice(e,1));!b&&c||r.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=V.get(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=r.timers,g=d?d.length:0;for(c.finish=!0,r.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;b<g;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})}}),r.each(["toggle","show","hide"],function(a,b){var c=r.fn[b];r.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(db(b,!0),a,d,e)}}),r.each({slideDown:db("show"),slideUp:db("hide"),slideToggle:db("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){r.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),r.timers=[],r.fx.tick=function(){var a,b=0,c=r.timers;for(Za=r.now();b<c.length;b++)a=c[b],a()||c[b]!==a||c.splice(b--,1);c.length||r.fx.stop(),Za=void 0},r.fx.timer=function(a){r.timers.push(a),a()?r.fx.start():r.timers.pop()},r.fx.interval=13,r.fx.start=function(){$a||($a=a.requestAnimationFrame?a.requestAnimationFrame(bb):a.setInterval(r.fx.tick,r.fx.interval))},r.fx.stop=function(){a.cancelAnimationFrame?a.cancelAnimationFrame($a):a.clearInterval($a),$a=null},r.fx.speeds={slow:600,fast:200,_default:400},r.fn.delay=function(b,c){return b=r.fx?r.fx.speeds[b]||b:b,c=c||"fx",this.queue(c,function(c,d){var e=a.setTimeout(c,b);d.stop=function(){a.clearTimeout(e)}})},function(){var a=d.createElement("input"),b=d.createElement("select"),c=b.appendChild(d.createElement("option"));a.type="checkbox",o.checkOn=""!==a.value,o.optSelected=c.selected,a=d.createElement("input"),a.value="t",a.type="radio",o.radioValue="t"===a.value}();var ib,jb=r.expr.attrHandle;r.fn.extend({attr:function(a,b){return S(this,r.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){r.removeAttr(this,a)})}}),r.extend({attr:function(a,b,c){var d,e,f=a.nodeType;if(3!==f&&8!==f&&2!==f)return"undefined"==typeof a.getAttribute?r.prop(a,b,c):(1===f&&r.isXMLDoc(a)||(e=r.attrHooks[b.toLowerCase()]||(r.expr.match.bool.test(b)?ib:void 0)),
void 0!==c?null===c?void r.removeAttr(a,b):e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:(a.setAttribute(b,c+""),c):e&&"get"in e&&null!==(d=e.get(a,b))?d:(d=r.find.attr(a,b),null==d?void 0:d))},attrHooks:{type:{set:function(a,b){if(!o.radioValue&&"radio"===b&&r.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}},removeAttr:function(a,b){var c,d=0,e=b&&b.match(K);if(e&&1===a.nodeType)while(c=e[d++])a.removeAttribute(c)}}),ib={set:function(a,b,c){return b===!1?r.removeAttr(a,c):a.setAttribute(c,c),c}},r.each(r.expr.match.bool.source.match(/\w+/g),function(a,b){var c=jb[b]||r.find.attr;jb[b]=function(a,b,d){var e,f,g=b.toLowerCase();return d||(f=jb[g],jb[g]=e,e=null!=c(a,b,d)?g:null,jb[g]=f),e}});var kb=/^(?:input|select|textarea|button)$/i,lb=/^(?:a|area)$/i;r.fn.extend({prop:function(a,b){return S(this,r.prop,a,b,arguments.length>1)},removeProp:function(a){return this.each(function(){delete this[r.propFix[a]||a]})}}),r.extend({prop:function(a,b,c){var d,e,f=a.nodeType;if(3!==f&&8!==f&&2!==f)return 1===f&&r.isXMLDoc(a)||(b=r.propFix[b]||b,e=r.propHooks[b]),void 0!==c?e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:a[b]=c:e&&"get"in e&&null!==(d=e.get(a,b))?d:a[b]},propHooks:{tabIndex:{get:function(a){var b=r.find.attr(a,"tabindex");return b?parseInt(b,10):kb.test(a.nodeName)||lb.test(a.nodeName)&&a.href?0:-1}}},propFix:{"for":"htmlFor","class":"className"}}),o.optSelected||(r.propHooks.selected={get:function(a){var b=a.parentNode;return b&&b.parentNode&&b.parentNode.selectedIndex,null},set:function(a){var b=a.parentNode;b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex)}}),r.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){r.propFix[this.toLowerCase()]=this});function mb(a){var b=a.match(K)||[];return b.join(" ")}function nb(a){return a.getAttribute&&a.getAttribute("class")||""}r.fn.extend({addClass:function(a){var b,c,d,e,f,g,h,i=0;if(r.isFunction(a))return this.each(function(b){r(this).addClass(a.call(this,b,nb(this)))});if("string"==typeof a&&a){b=a.match(K)||[];while(c=this[i++])if(e=nb(c),d=1===c.nodeType&&" "+mb(e)+" "){g=0;while(f=b[g++])d.indexOf(" "+f+" ")<0&&(d+=f+" ");h=mb(d),e!==h&&c.setAttribute("class",h)}}return this},removeClass:function(a){var b,c,d,e,f,g,h,i=0;if(r.isFunction(a))return this.each(function(b){r(this).removeClass(a.call(this,b,nb(this)))});if(!arguments.length)return this.attr("class","");if("string"==typeof a&&a){b=a.match(K)||[];while(c=this[i++])if(e=nb(c),d=1===c.nodeType&&" "+mb(e)+" "){g=0;while(f=b[g++])while(d.indexOf(" "+f+" ")>-1)d=d.replace(" "+f+" "," ");h=mb(d),e!==h&&c.setAttribute("class",h)}}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):r.isFunction(a)?this.each(function(c){r(this).toggleClass(a.call(this,c,nb(this),b),b)}):this.each(function(){var b,d,e,f;if("string"===c){d=0,e=r(this),f=a.match(K)||[];while(b=f[d++])e.hasClass(b)?e.removeClass(b):e.addClass(b)}else void 0!==a&&"boolean"!==c||(b=nb(this),b&&V.set(this,"__className__",b),this.setAttribute&&this.setAttribute("class",b||a===!1?"":V.get(this,"__className__")||""))})},hasClass:function(a){var b,c,d=0;b=" "+a+" ";while(c=this[d++])if(1===c.nodeType&&(" "+mb(nb(c))+" ").indexOf(b)>-1)return!0;return!1}});var ob=/\r/g;r.fn.extend({val:function(a){var b,c,d,e=this[0];{if(arguments.length)return d=r.isFunction(a),this.each(function(c){var e;1===this.nodeType&&(e=d?a.call(this,c,r(this).val()):a,null==e?e="":"number"==typeof e?e+="":r.isArray(e)&&(e=r.map(e,function(a){return null==a?"":a+""})),b=r.valHooks[this.type]||r.valHooks[this.nodeName.toLowerCase()],b&&"set"in b&&void 0!==b.set(this,e,"value")||(this.value=e))});if(e)return b=r.valHooks[e.type]||r.valHooks[e.nodeName.toLowerCase()],b&&"get"in b&&void 0!==(c=b.get(e,"value"))?c:(c=e.value,"string"==typeof c?c.replace(ob,""):null==c?"":c)}}}),r.extend({valHooks:{option:{get:function(a){var b=r.find.attr(a,"value");return null!=b?b:mb(r.text(a))}},select:{get:function(a){var b,c,d,e=a.options,f=a.selectedIndex,g="select-one"===a.type,h=g?null:[],i=g?f+1:e.length;for(d=f<0?i:g?f:0;d<i;d++)if(c=e[d],(c.selected||d===f)&&!c.disabled&&(!c.parentNode.disabled||!r.nodeName(c.parentNode,"optgroup"))){if(b=r(c).val(),g)return b;h.push(b)}return h},set:function(a,b){var c,d,e=a.options,f=r.makeArray(b),g=e.length;while(g--)d=e[g],(d.selected=r.inArray(r.valHooks.option.get(d),f)>-1)&&(c=!0);return c||(a.selectedIndex=-1),f}}}}),r.each(["radio","checkbox"],function(){r.valHooks[this]={set:function(a,b){if(r.isArray(b))return a.checked=r.inArray(r(a).val(),b)>-1}},o.checkOn||(r.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})});var pb=/^(?:focusinfocus|focusoutblur)$/;r.extend(r.event,{trigger:function(b,c,e,f){var g,h,i,j,k,m,n,o=[e||d],p=l.call(b,"type")?b.type:b,q=l.call(b,"namespace")?b.namespace.split("."):[];if(h=i=e=e||d,3!==e.nodeType&&8!==e.nodeType&&!pb.test(p+r.event.triggered)&&(p.indexOf(".")>-1&&(q=p.split("."),p=q.shift(),q.sort()),k=p.indexOf(":")<0&&"on"+p,b=b[r.expando]?b:new r.Event(p,"object"==typeof b&&b),b.isTrigger=f?2:3,b.namespace=q.join("."),b.rnamespace=b.namespace?new RegExp("(^|\\.)"+q.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,b.result=void 0,b.target||(b.target=e),c=null==c?[b]:r.makeArray(c,[b]),n=r.event.special[p]||{},f||!n.trigger||n.trigger.apply(e,c)!==!1)){if(!f&&!n.noBubble&&!r.isWindow(e)){for(j=n.delegateType||p,pb.test(j+p)||(h=h.parentNode);h;h=h.parentNode)o.push(h),i=h;i===(e.ownerDocument||d)&&o.push(i.defaultView||i.parentWindow||a)}g=0;while((h=o[g++])&&!b.isPropagationStopped())b.type=g>1?j:n.bindType||p,m=(V.get(h,"events")||{})[b.type]&&V.get(h,"handle"),m&&m.apply(h,c),m=k&&h[k],m&&m.apply&&T(h)&&(b.result=m.apply(h,c),b.result===!1&&b.preventDefault());return b.type=p,f||b.isDefaultPrevented()||n._default&&n._default.apply(o.pop(),c)!==!1||!T(e)||k&&r.isFunction(e[p])&&!r.isWindow(e)&&(i=e[k],i&&(e[k]=null),r.event.triggered=p,e[p](),r.event.triggered=void 0,i&&(e[k]=i)),b.result}},simulate:function(a,b,c){var d=r.extend(new r.Event,c,{type:a,isSimulated:!0});r.event.trigger(d,null,b)}}),r.fn.extend({trigger:function(a,b){return this.each(function(){r.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];if(c)return r.event.trigger(a,b,c,!0)}}),r.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "),function(a,b){r.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),r.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),o.focusin="onfocusin"in a,o.focusin||r.each({focus:"focusin",blur:"focusout"},function(a,b){var c=function(a){r.event.simulate(b,a.target,r.event.fix(a))};r.event.special[b]={setup:function(){var d=this.ownerDocument||this,e=V.access(d,b);e||d.addEventListener(a,c,!0),V.access(d,b,(e||0)+1)},teardown:function(){var d=this.ownerDocument||this,e=V.access(d,b)-1;e?V.access(d,b,e):(d.removeEventListener(a,c,!0),V.remove(d,b))}}});var qb=a.location,rb=r.now(),sb=/\?/;r.parseXML=function(b){var c;if(!b||"string"!=typeof b)return null;try{c=(new a.DOMParser).parseFromString(b,"text/xml")}catch(d){c=void 0}return c&&!c.getElementsByTagName("parsererror").length||r.error("Invalid XML: "+b),c};var tb=/\[\]$/,ub=/\r?\n/g,vb=/^(?:submit|button|image|reset|file)$/i,wb=/^(?:input|select|textarea|keygen)/i;function xb(a,b,c,d){var e;if(r.isArray(b))r.each(b,function(b,e){c||tb.test(a)?d(a,e):xb(a+"["+("object"==typeof e&&null!=e?b:"")+"]",e,c,d)});else if(c||"object"!==r.type(b))d(a,b);else for(e in b)xb(a+"["+e+"]",b[e],c,d)}r.param=function(a,b){var c,d=[],e=function(a,b){var c=r.isFunction(b)?b():b;d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(null==c?"":c)};if(r.isArray(a)||a.jquery&&!r.isPlainObject(a))r.each(a,function(){e(this.name,this.value)});else for(c in a)xb(c,a[c],b,e);return d.join("&")},r.fn.extend({serialize:function(){return r.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=r.prop(this,"elements");return a?r.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!r(this).is(":disabled")&&wb.test(this.nodeName)&&!vb.test(a)&&(this.checked||!ia.test(a))}).map(function(a,b){var c=r(this).val();return null==c?null:r.isArray(c)?r.map(c,function(a){return{name:b.name,value:a.replace(ub,"\r\n")}}):{name:b.name,value:c.replace(ub,"\r\n")}}).get()}});var yb=/%20/g,zb=/#.*$/,Ab=/([?&])_=[^&]*/,Bb=/^(.*?):[ \t]*([^\r\n]*)$/gm,Cb=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,Db=/^(?:GET|HEAD)$/,Eb=/^\/\//,Fb={},Gb={},Hb="*/".concat("*"),Ib=d.createElement("a");Ib.href=qb.href;function Jb(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(K)||[];if(r.isFunction(c))while(d=f[e++])"+"===d[0]?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function Kb(a,b,c,d){var e={},f=a===Gb;function g(h){var i;return e[h]=!0,r.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||f||e[j]?f?!(i=j):void 0:(b.dataTypes.unshift(j),g(j),!1)}),i}return g(b.dataTypes[0])||!e["*"]&&g("*")}function Lb(a,b){var c,d,e=r.ajaxSettings.flatOptions||{};for(c in b)void 0!==b[c]&&((e[c]?a:d||(d={}))[c]=b[c]);return d&&r.extend(!0,a,d),a}function Mb(a,b,c){var d,e,f,g,h=a.contents,i=a.dataTypes;while("*"===i[0])i.shift(),void 0===d&&(d=a.mimeType||b.getResponseHeader("Content-Type"));if(d)for(e in h)if(h[e]&&h[e].test(d)){i.unshift(e);break}if(i[0]in c)f=i[0];else{for(e in c){if(!i[0]||a.converters[e+" "+i[0]]){f=e;break}g||(g=e)}f=f||g}if(f)return f!==i[0]&&i.unshift(f),c[f]}function Nb(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];f=k.shift();while(f)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}r.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:qb.href,type:"GET",isLocal:Cb.test(qb.protocol),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":Hb,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/\bxml\b/,html:/\bhtml/,json:/\bjson\b/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":JSON.parse,"text xml":r.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?Lb(Lb(a,r.ajaxSettings),b):Lb(r.ajaxSettings,a)},ajaxPrefilter:Jb(Fb),ajaxTransport:Jb(Gb),ajax:function(b,c){"object"==typeof b&&(c=b,b=void 0),c=c||{};var e,f,g,h,i,j,k,l,m,n,o=r.ajaxSetup({},c),p=o.context||o,q=o.context&&(p.nodeType||p.jquery)?r(p):r.event,s=r.Deferred(),t=r.Callbacks("once memory"),u=o.statusCode||{},v={},w={},x="canceled",y={readyState:0,getResponseHeader:function(a){var b;if(k){if(!h){h={};while(b=Bb.exec(g))h[b[1].toLowerCase()]=b[2]}b=h[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return k?g:null},setRequestHeader:function(a,b){return null==k&&(a=w[a.toLowerCase()]=w[a.toLowerCase()]||a,v[a]=b),this},overrideMimeType:function(a){return null==k&&(o.mimeType=a),this},statusCode:function(a){var b;if(a)if(k)y.always(a[y.status]);else for(b in a)u[b]=[u[b],a[b]];return this},abort:function(a){var b=a||x;return e&&e.abort(b),A(0,b),this}};if(s.promise(y),o.url=((b||o.url||qb.href)+"").replace(Eb,qb.protocol+"//"),o.type=c.method||c.type||o.method||o.type,o.dataTypes=(o.dataType||"*").toLowerCase().match(K)||[""],null==o.crossDomain){j=d.createElement("a");try{j.href=o.url,j.href=j.href,o.crossDomain=Ib.protocol+"//"+Ib.host!=j.protocol+"//"+j.host}catch(z){o.crossDomain=!0}}if(o.data&&o.processData&&"string"!=typeof o.data&&(o.data=r.param(o.data,o.traditional)),Kb(Fb,o,c,y),k)return y;l=r.event&&o.global,l&&0===r.active++&&r.event.trigger("ajaxStart"),o.type=o.type.toUpperCase(),o.hasContent=!Db.test(o.type),f=o.url.replace(zb,""),o.hasContent?o.data&&o.processData&&0===(o.contentType||"").indexOf("application/x-www-form-urlencoded")&&(o.data=o.data.replace(yb,"+")):(n=o.url.slice(f.length),o.data&&(f+=(sb.test(f)?"&":"?")+o.data,delete o.data),o.cache===!1&&(f=f.replace(Ab,"$1"),n=(sb.test(f)?"&":"?")+"_="+rb++ +n),o.url=f+n),o.ifModified&&(r.lastModified[f]&&y.setRequestHeader("If-Modified-Since",r.lastModified[f]),r.etag[f]&&y.setRequestHeader("If-None-Match",r.etag[f])),(o.data&&o.hasContent&&o.contentType!==!1||c.contentType)&&y.setRequestHeader("Content-Type",o.contentType),y.setRequestHeader("Accept",o.dataTypes[0]&&o.accepts[o.dataTypes[0]]?o.accepts[o.dataTypes[0]]+("*"!==o.dataTypes[0]?", "+Hb+"; q=0.01":""):o.accepts["*"]);for(m in o.headers)y.setRequestHeader(m,o.headers[m]);if(o.beforeSend&&(o.beforeSend.call(p,y,o)===!1||k))return y.abort();if(x="abort",t.add(o.complete),y.done(o.success),y.fail(o.error),e=Kb(Gb,o,c,y)){if(y.readyState=1,l&&q.trigger("ajaxSend",[y,o]),k)return y;o.async&&o.timeout>0&&(i=a.setTimeout(function(){y.abort("timeout")},o.timeout));try{k=!1,e.send(v,A)}catch(z){if(k)throw z;A(-1,z)}}else A(-1,"No Transport");function A(b,c,d,h){var j,m,n,v,w,x=c;k||(k=!0,i&&a.clearTimeout(i),e=void 0,g=h||"",y.readyState=b>0?4:0,j=b>=200&&b<300||304===b,d&&(v=Mb(o,y,d)),v=Nb(o,v,y,j),j?(o.ifModified&&(w=y.getResponseHeader("Last-Modified"),w&&(r.lastModified[f]=w),w=y.getResponseHeader("etag"),w&&(r.etag[f]=w)),204===b||"HEAD"===o.type?x="nocontent":304===b?x="notmodified":(x=v.state,m=v.data,n=v.error,j=!n)):(n=x,!b&&x||(x="error",b<0&&(b=0))),y.status=b,y.statusText=(c||x)+"",j?s.resolveWith(p,[m,x,y]):s.rejectWith(p,[y,x,n]),y.statusCode(u),u=void 0,l&&q.trigger(j?"ajaxSuccess":"ajaxError",[y,o,j?m:n]),t.fireWith(p,[y,x]),l&&(q.trigger("ajaxComplete",[y,o]),--r.active||r.event.trigger("ajaxStop")))}return y},getJSON:function(a,b,c){return r.get(a,b,c,"json")},getScript:function(a,b){return r.get(a,void 0,b,"script")}}),r.each(["get","post"],function(a,b){r[b]=function(a,c,d,e){return r.isFunction(c)&&(e=e||d,d=c,c=void 0),r.ajax(r.extend({url:a,type:b,dataType:e,data:c,success:d},r.isPlainObject(a)&&a))}}),r._evalUrl=function(a){return r.ajax({url:a,type:"GET",dataType:"script",cache:!0,async:!1,global:!1,"throws":!0})},r.fn.extend({wrapAll:function(a){var b;return this[0]&&(r.isFunction(a)&&(a=a.call(this[0])),b=r(a,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstElementChild)a=a.firstElementChild;return a}).append(this)),this},wrapInner:function(a){return r.isFunction(a)?this.each(function(b){r(this).wrapInner(a.call(this,b))}):this.each(function(){var b=r(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=r.isFunction(a);return this.each(function(c){r(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(a){return this.parent(a).not("body").each(function(){r(this).replaceWith(this.childNodes)}),this}}),r.expr.pseudos.hidden=function(a){return!r.expr.pseudos.visible(a)},r.expr.pseudos.visible=function(a){return!!(a.offsetWidth||a.offsetHeight||a.getClientRects().length)},r.ajaxSettings.xhr=function(){try{return new a.XMLHttpRequest}catch(b){}};var Ob={0:200,1223:204},Pb=r.ajaxSettings.xhr();o.cors=!!Pb&&"withCredentials"in Pb,o.ajax=Pb=!!Pb,r.ajaxTransport(function(b){var c,d;if(o.cors||Pb&&!b.crossDomain)return{send:function(e,f){var g,h=b.xhr();if(h.open(b.type,b.url,b.async,b.username,b.password),b.xhrFields)for(g in b.xhrFields)h[g]=b.xhrFields[g];b.mimeType&&h.overrideMimeType&&h.overrideMimeType(b.mimeType),b.crossDomain||e["X-Requested-With"]||(e["X-Requested-With"]="XMLHttpRequest");for(g in e)h.setRequestHeader(g,e[g]);c=function(a){return function(){c&&(c=d=h.onload=h.onerror=h.onabort=h.onreadystatechange=null,"abort"===a?h.abort():"error"===a?"number"!=typeof h.status?f(0,"error"):f(h.status,h.statusText):f(Ob[h.status]||h.status,h.statusText,"text"!==(h.responseType||"text")||"string"!=typeof h.responseText?{binary:h.response}:{text:h.responseText},h.getAllResponseHeaders()))}},h.onload=c(),d=h.onerror=c("error"),void 0!==h.onabort?h.onabort=d:h.onreadystatechange=function(){4===h.readyState&&a.setTimeout(function(){c&&d()})},c=c("abort");try{h.send(b.hasContent&&b.data||null)}catch(i){if(c)throw i}},abort:function(){c&&c()}}}),r.ajaxPrefilter(function(a){a.crossDomain&&(a.contents.script=!1)}),r.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/\b(?:java|ecma)script\b/},converters:{"text script":function(a){return r.globalEval(a),a}}}),r.ajaxPrefilter("script",function(a){void 0===a.cache&&(a.cache=!1),a.crossDomain&&(a.type="GET")}),r.ajaxTransport("script",function(a){if(a.crossDomain){var b,c;return{send:function(e,f){b=r("<script>").prop({charset:a.scriptCharset,src:a.url}).on("load error",c=function(a){b.remove(),c=null,a&&f("error"===a.type?404:200,a.type)}),d.head.appendChild(b[0])},abort:function(){c&&c()}}}});var Qb=[],Rb=/(=)\?(?=&|$)|\?\?/;r.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=Qb.pop()||r.expando+"_"+rb++;return this[a]=!0,a}}),r.ajaxPrefilter("json jsonp",function(b,c,d){var e,f,g,h=b.jsonp!==!1&&(Rb.test(b.url)?"url":"string"==typeof b.data&&0===(b.contentType||"").indexOf("application/x-www-form-urlencoded")&&Rb.test(b.data)&&"data");if(h||"jsonp"===b.dataTypes[0])return e=b.jsonpCallback=r.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,h?b[h]=b[h].replace(Rb,"$1"+e):b.jsonp!==!1&&(b.url+=(sb.test(b.url)?"&":"?")+b.jsonp+"="+e),b.converters["script json"]=function(){return g||r.error(e+" was not called"),g[0]},b.dataTypes[0]="json",f=a[e],a[e]=function(){g=arguments},d.always(function(){void 0===f?r(a).removeProp(e):a[e]=f,b[e]&&(b.jsonpCallback=c.jsonpCallback,Qb.push(e)),g&&r.isFunction(f)&&f(g[0]),g=f=void 0}),"script"}),o.createHTMLDocument=function(){var a=d.implementation.createHTMLDocument("").body;return a.innerHTML="<form></form><form></form>",2===a.childNodes.length}(),r.parseHTML=function(a,b,c){if("string"!=typeof a)return[];"boolean"==typeof b&&(c=b,b=!1);var e,f,g;return b||(o.createHTMLDocument?(b=d.implementation.createHTMLDocument(""),e=b.createElement("base"),e.href=d.location.href,b.head.appendChild(e)):b=d),f=B.exec(a),g=!c&&[],f?[b.createElement(f[1])]:(f=pa([a],b,g),g&&g.length&&r(g).remove(),r.merge([],f.childNodes))},r.fn.load=function(a,b,c){var d,e,f,g=this,h=a.indexOf(" ");return h>-1&&(d=mb(a.slice(h)),a=a.slice(0,h)),r.isFunction(b)?(c=b,b=void 0):b&&"object"==typeof b&&(e="POST"),g.length>0&&r.ajax({url:a,type:e||"GET",dataType:"html",data:b}).done(function(a){f=arguments,g.html(d?r("<div>").append(r.parseHTML(a)).find(d):a)}).always(c&&function(a,b){g.each(function(){c.apply(this,f||[a.responseText,b,a])})}),this},r.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){r.fn[b]=function(a){return this.on(b,a)}}),r.expr.pseudos.animated=function(a){return r.grep(r.timers,function(b){return a===b.elem}).length};function Sb(a){return r.isWindow(a)?a:9===a.nodeType&&a.defaultView}r.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=r.css(a,"position"),l=r(a),m={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=r.css(a,"top"),i=r.css(a,"left"),j=("absolute"===k||"fixed"===k)&&(f+i).indexOf("auto")>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),r.isFunction(b)&&(b=b.call(a,c,r.extend({},h))),null!=b.top&&(m.top=b.top-h.top+g),null!=b.left&&(m.left=b.left-h.left+e),"using"in b?b.using.call(a,m):l.css(m)}},r.fn.extend({offset:function(a){if(arguments.length)return void 0===a?this:this.each(function(b){r.offset.setOffset(this,a,b)});var b,c,d,e,f=this[0];if(f)return f.getClientRects().length?(d=f.getBoundingClientRect(),d.width||d.height?(e=f.ownerDocument,c=Sb(e),b=e.documentElement,{top:d.top+c.pageYOffset-b.clientTop,left:d.left+c.pageXOffset-b.clientLeft}):d):{top:0,left:0}},position:function(){if(this[0]){var a,b,c=this[0],d={top:0,left:0};return"fixed"===r.css(c,"position")?b=c.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),r.nodeName(a[0],"html")||(d=a.offset()),d={top:d.top+r.css(a[0],"borderTopWidth",!0),left:d.left+r.css(a[0],"borderLeftWidth",!0)}),{top:b.top-d.top-r.css(c,"marginTop",!0),left:b.left-d.left-r.css(c,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var a=this.offsetParent;while(a&&"static"===r.css(a,"position"))a=a.offsetParent;return a||qa})}}),r.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,b){var c="pageYOffset"===b;r.fn[a]=function(d){return S(this,function(a,d,e){var f=Sb(a);return void 0===e?f?f[b]:a[d]:void(f?f.scrollTo(c?f.pageXOffset:e,c?e:f.pageYOffset):a[d]=e)},a,d,arguments.length)}}),r.each(["top","left"],function(a,b){r.cssHooks[b]=Oa(o.pixelPosition,function(a,c){if(c)return c=Na(a,b),La.test(c)?r(a).position()[b]+"px":c})}),r.each({Height:"height",Width:"width"},function(a,b){r.each({padding:"inner"+a,content:b,"":"outer"+a},function(c,d){r.fn[d]=function(e,f){var g=arguments.length&&(c||"boolean"!=typeof e),h=c||(e===!0||f===!0?"margin":"border");return S(this,function(b,c,e){var f;return r.isWindow(b)?0===d.indexOf("outer")?b["inner"+a]:b.document.documentElement["client"+a]:9===b.nodeType?(f=b.documentElement,Math.max(b.body["scroll"+a],f["scroll"+a],b.body["offset"+a],f["offset"+a],f["client"+a])):void 0===e?r.css(b,c,h):r.style(b,c,e,h)},b,g?e:void 0,g)}})}),r.fn.extend({bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)}}),r.parseJSON=JSON.parse,"function"==typeof define&&define.amd&&define("jquery",[],function(){return r});var Tb=a.jQuery,Ub=a.$;return r.noConflict=function(b){return a.$===r&&(a.$=Ub),b&&a.jQuery===r&&(a.jQuery=Tb),r},b||(a.jQuery=a.$=r),r});

			/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under the MIT license
 */
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(a){"use strict";var b=a.fn.jquery.split(" ")[0].split(".");if(b[0]<2&&b[1]<9||1==b[0]&&9==b[1]&&b[2]<1||b[0]>3)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")}(jQuery),+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one("bsTransitionEnd",function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b(),a.support.transition&&(a.event.special.bsTransitionEnd={bindType:a.support.transition.end,delegateType:a.support.transition.end,handle:function(b){if(a(b.target).is(this))return b.handleObj.handler.apply(this,arguments)}})})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var c=a(this),e=c.data("bs.alert");e||c.data("bs.alert",e=new d(this)),"string"==typeof b&&e[b].call(c)})}var c='[data-dismiss="alert"]',d=function(b){a(b).on("click",c,this.close)};d.VERSION="3.3.7",d.TRANSITION_DURATION=150,d.prototype.close=function(b){function c(){g.detach().trigger("closed.bs.alert").remove()}var e=a(this),f=e.attr("data-target");f||(f=e.attr("href"),f=f&&f.replace(/.*(?=#[^\s]*$)/,""));var g=a("#"===f?[]:f);b&&b.preventDefault(),g.length||(g=e.closest(".alert")),g.trigger(b=a.Event("close.bs.alert")),b.isDefaultPrevented()||(g.removeClass("in"),a.support.transition&&g.hasClass("fade")?g.one("bsTransitionEnd",c).emulateTransitionEnd(d.TRANSITION_DURATION):c())};var e=a.fn.alert;a.fn.alert=b,a.fn.alert.Constructor=d,a.fn.alert.noConflict=function(){return a.fn.alert=e,this},a(document).on("click.bs.alert.data-api",c,d.prototype.close)}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.button"),f="object"==typeof b&&b;e||d.data("bs.button",e=new c(this,f)),"toggle"==b?e.toggle():b&&e.setState(b)})}var c=function(b,d){this.$element=a(b),this.options=a.extend({},c.DEFAULTS,d),this.isLoading=!1};c.VERSION="3.3.7",c.DEFAULTS={loadingText:"loading..."},c.prototype.setState=function(b){var c="disabled",d=this.$element,e=d.is("input")?"val":"html",f=d.data();b+="Text",null==f.resetText&&d.data("resetText",d[e]()),setTimeout(a.proxy(function(){d[e](null==f[b]?this.options[b]:f[b]),"loadingText"==b?(this.isLoading=!0,d.addClass(c).attr(c,c).prop(c,!0)):this.isLoading&&(this.isLoading=!1,d.removeClass(c).removeAttr(c).prop(c,!1))},this),0)},c.prototype.toggle=function(){var a=!0,b=this.$element.closest('[data-toggle="buttons"]');if(b.length){var c=this.$element.find("input");"radio"==c.prop("type")?(c.prop("checked")&&(a=!1),b.find(".active").removeClass("active"),this.$element.addClass("active")):"checkbox"==c.prop("type")&&(c.prop("checked")!==this.$element.hasClass("active")&&(a=!1),this.$element.toggleClass("active")),c.prop("checked",this.$element.hasClass("active")),a&&c.trigger("change")}else this.$element.attr("aria-pressed",!this.$element.hasClass("active")),this.$element.toggleClass("active")};var d=a.fn.button;a.fn.button=b,a.fn.button.Constructor=c,a.fn.button.noConflict=function(){return a.fn.button=d,this},a(document).on("click.bs.button.data-api",'[data-toggle^="button"]',function(c){var d=a(c.target).closest(".btn");b.call(d,"toggle"),a(c.target).is('input[type="radio"], input[type="checkbox"]')||(c.preventDefault(),d.is("input,button")?d.trigger("focus"):d.find("input:visible,button:visible").first().trigger("focus"))}).on("focus.bs.button.data-api blur.bs.button.data-api",'[data-toggle^="button"]',function(b){a(b.target).closest(".btn").toggleClass("focus",/^focus(in)?$/.test(b.type))})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},c.DEFAULTS,d.data(),"object"==typeof b&&b),g="string"==typeof b?b:f.slide;e||d.data("bs.carousel",e=new c(this,f)),"number"==typeof b?e.to(b):g?e[g]():f.interval&&e.pause().cycle()})}var c=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".carousel-indicators"),this.options=c,this.paused=null,this.sliding=null,this.interval=null,this.$active=null,this.$items=null,this.options.keyboard&&this.$element.on("keydown.bs.carousel",a.proxy(this.keydown,this)),"hover"==this.options.pause&&!("ontouchstart"in document.documentElement)&&this.$element.on("mouseenter.bs.carousel",a.proxy(this.pause,this)).on("mouseleave.bs.carousel",a.proxy(this.cycle,this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=600,c.DEFAULTS={interval:5e3,pause:"hover",wrap:!0,keyboard:!0},c.prototype.keydown=function(a){if(!/input|textarea/i.test(a.target.tagName)){switch(a.which){case 37:this.prev();break;case 39:this.next();break;default:return}a.preventDefault()}},c.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},c.prototype.getItemIndex=function(a){return this.$items=a.parent().children(".item"),this.$items.index(a||this.$active)},c.prototype.getItemForDirection=function(a,b){var c=this.getItemIndex(b),d="prev"==a&&0===c||"next"==a&&c==this.$items.length-1;if(d&&!this.options.wrap)return b;var e="prev"==a?-1:1,f=(c+e)%this.$items.length;return this.$items.eq(f)},c.prototype.to=function(a){var b=this,c=this.getItemIndex(this.$active=this.$element.find(".item.active"));if(!(a>this.$items.length-1||a<0))return this.sliding?this.$element.one("slid.bs.carousel",function(){b.to(a)}):c==a?this.pause().cycle():this.slide(a>c?"next":"prev",this.$items.eq(a))},c.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},c.prototype.next=function(){if(!this.sliding)return this.slide("next")},c.prototype.prev=function(){if(!this.sliding)return this.slide("prev")},c.prototype.slide=function(b,d){var e=this.$element.find(".item.active"),f=d||this.getItemForDirection(b,e),g=this.interval,h="next"==b?"left":"right",i=this;if(f.hasClass("active"))return this.sliding=!1;var j=f[0],k=a.Event("slide.bs.carousel",{relatedTarget:j,direction:h});if(this.$element.trigger(k),!k.isDefaultPrevented()){if(this.sliding=!0,g&&this.pause(),this.$indicators.length){this.$indicators.find(".active").removeClass("active");var l=a(this.$indicators.children()[this.getItemIndex(f)]);l&&l.addClass("active")}var m=a.Event("slid.bs.carousel",{relatedTarget:j,direction:h});return a.support.transition&&this.$element.hasClass("slide")?(f.addClass(b),f[0].offsetWidth,e.addClass(h),f.addClass(h),e.one("bsTransitionEnd",function(){f.removeClass([b,h].join(" ")).addClass("active"),e.removeClass(["active",h].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger(m)},0)}).emulateTransitionEnd(c.TRANSITION_DURATION)):(e.removeClass("active"),f.addClass("active"),this.sliding=!1,this.$element.trigger(m)),g&&this.cycle(),this}};var d=a.fn.carousel;a.fn.carousel=b,a.fn.carousel.Constructor=c,a.fn.carousel.noConflict=function(){return a.fn.carousel=d,this};var e=function(c){var d,e=a(this),f=a(e.attr("data-target")||(d=e.attr("href"))&&d.replace(/.*(?=#[^\s]+$)/,""));if(f.hasClass("carousel")){var g=a.extend({},f.data(),e.data()),h=e.attr("data-slide-to");h&&(g.interval=!1),b.call(f,g),h&&f.data("bs.carousel").to(h),c.preventDefault()}};a(document).on("click.bs.carousel.data-api","[data-slide]",e).on("click.bs.carousel.data-api","[data-slide-to]",e),a(window).on("load",function(){a('[data-ride="carousel"]').each(function(){var c=a(this);b.call(c,c.data())})})}(jQuery),+function(a){"use strict";function b(b){var c,d=b.attr("data-target")||(c=b.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"");return a(d)}function c(b){return this.each(function(){var c=a(this),e=c.data("bs.collapse"),f=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b);!e&&f.toggle&&/show|hide/.test(b)&&(f.toggle=!1),e||c.data("bs.collapse",e=new d(this,f)),"string"==typeof b&&e[b]()})}var d=function(b,c){this.$element=a(b),this.options=a.extend({},d.DEFAULTS,c),this.$trigger=a('[data-toggle="collapse"][href="#'+b.id+'"],[data-toggle="collapse"][data-target="#'+b.id+'"]'),this.transitioning=null,this.options.parent?this.$parent=this.getParent():this.addAriaAndCollapsedClass(this.$element,this.$trigger),this.options.toggle&&this.toggle()};d.VERSION="3.3.7",d.TRANSITION_DURATION=350,d.DEFAULTS={toggle:!0},d.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},d.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b,e=this.$parent&&this.$parent.children(".panel").children(".in, .collapsing");if(!(e&&e.length&&(b=e.data("bs.collapse"),b&&b.transitioning))){var f=a.Event("show.bs.collapse");if(this.$element.trigger(f),!f.isDefaultPrevented()){e&&e.length&&(c.call(e,"hide"),b||e.data("bs.collapse",null));var g=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[g](0).attr("aria-expanded",!0),this.$trigger.removeClass("collapsed").attr("aria-expanded",!0),this.transitioning=1;var h=function(){this.$element.removeClass("collapsing").addClass("collapse in")[g](""),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return h.call(this);var i=a.camelCase(["scroll",g].join("-"));this.$element.one("bsTransitionEnd",a.proxy(h,this)).emulateTransitionEnd(d.TRANSITION_DURATION)[g](this.$element[0][i])}}}},d.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded",!1),this.$trigger.addClass("collapsed").attr("aria-expanded",!1),this.transitioning=1;var e=function(){this.transitioning=0,this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")};return a.support.transition?void this.$element[c](0).one("bsTransitionEnd",a.proxy(e,this)).emulateTransitionEnd(d.TRANSITION_DURATION):e.call(this)}}},d.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()},d.prototype.getParent=function(){return a(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each(a.proxy(function(c,d){var e=a(d);this.addAriaAndCollapsedClass(b(e),e)},this)).end()},d.prototype.addAriaAndCollapsedClass=function(a,b){var c=a.hasClass("in");a.attr("aria-expanded",c),b.toggleClass("collapsed",!c).attr("aria-expanded",c)};var e=a.fn.collapse;a.fn.collapse=c,a.fn.collapse.Constructor=d,a.fn.collapse.noConflict=function(){return a.fn.collapse=e,this},a(document).on("click.bs.collapse.data-api",'[data-toggle="collapse"]',function(d){var e=a(this);e.attr("data-target")||d.preventDefault();var f=b(e),g=f.data("bs.collapse"),h=g?"toggle":e.data();c.call(f,h)})}(jQuery),+function(a){"use strict";function b(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#[A-Za-z]/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}function c(c){c&&3===c.which||(a(e).remove(),a(f).each(function(){var d=a(this),e=b(d),f={relatedTarget:this};e.hasClass("open")&&(c&&"click"==c.type&&/input|textarea/i.test(c.target.tagName)&&a.contains(e[0],c.target)||(e.trigger(c=a.Event("hide.bs.dropdown",f)),c.isDefaultPrevented()||(d.attr("aria-expanded","false"),e.removeClass("open").trigger(a.Event("hidden.bs.dropdown",f)))))}))}function d(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new g(this)),"string"==typeof b&&d[b].call(c)})}var e=".dropdown-backdrop",f='[data-toggle="dropdown"]',g=function(b){a(b).on("click.bs.dropdown",this.toggle)};g.VERSION="3.3.7",g.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=b(e),g=f.hasClass("open");if(c(),!g){"ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click",c);var h={relatedTarget:this};if(f.trigger(d=a.Event("show.bs.dropdown",h)),d.isDefaultPrevented())return;e.trigger("focus").attr("aria-expanded","true"),f.toggleClass("open").trigger(a.Event("shown.bs.dropdown",h))}return!1}},g.prototype.keydown=function(c){if(/(38|40|27|32)/.test(c.which)&&!/input|textarea/i.test(c.target.tagName)){var d=a(this);if(c.preventDefault(),c.stopPropagation(),!d.is(".disabled, :disabled")){var e=b(d),g=e.hasClass("open");if(!g&&27!=c.which||g&&27==c.which)return 27==c.which&&e.find(f).trigger("focus"),d.trigger("click");var h=" li:not(.disabled):visible a",i=e.find(".dropdown-menu"+h);if(i.length){var j=i.index(c.target);38==c.which&&j>0&&j--,40==c.which&&j<i.length-1&&j++,~j||(j=0),i.eq(j).trigger("focus")}}}};var h=a.fn.dropdown;a.fn.dropdown=d,a.fn.dropdown.Constructor=g,a.fn.dropdown.noConflict=function(){return a.fn.dropdown=h,this},a(document).on("click.bs.dropdown.data-api",c).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",f,g.prototype.toggle).on("keydown.bs.dropdown.data-api",f,g.prototype.keydown).on("keydown.bs.dropdown.data-api",".dropdown-menu",g.prototype.keydown)}(jQuery),+function(a){"use strict";function b(b,d){return this.each(function(){var e=a(this),f=e.data("bs.modal"),g=a.extend({},c.DEFAULTS,e.data(),"object"==typeof b&&b);f||e.data("bs.modal",f=new c(this,g)),"string"==typeof b?f[b](d):g.show&&f.show(d)})}var c=function(b,c){this.options=c,this.$body=a(document.body),this.$element=a(b),this.$dialog=this.$element.find(".modal-dialog"),this.$backdrop=null,this.isShown=null,this.originalBodyPad=null,this.scrollbarWidth=0,this.ignoreBackdropClick=!1,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=300,c.BACKDROP_TRANSITION_DURATION=150,c.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},c.prototype.toggle=function(a){return this.isShown?this.hide():this.show(a)},c.prototype.show=function(b){var d=this,e=a.Event("show.bs.modal",{relatedTarget:b});this.$element.trigger(e),this.isShown||e.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',a.proxy(this.hide,this)),this.$dialog.on("mousedown.dismiss.bs.modal",function(){d.$element.one("mouseup.dismiss.bs.modal",function(b){a(b.target).is(d.$element)&&(d.ignoreBackdropClick=!0)})}),this.backdrop(function(){var e=a.support.transition&&d.$element.hasClass("fade");d.$element.parent().length||d.$element.appendTo(d.$body),d.$element.show().scrollTop(0),d.adjustDialog(),e&&d.$element[0].offsetWidth,d.$element.addClass("in"),d.enforceFocus();var f=a.Event("shown.bs.modal",{relatedTarget:b});e?d.$dialog.one("bsTransitionEnd",function(){d.$element.trigger("focus").trigger(f)}).emulateTransitionEnd(c.TRANSITION_DURATION):d.$element.trigger("focus").trigger(f)}))},c.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),a(document).off("focusin.bs.modal"),this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"),this.$dialog.off("mousedown.dismiss.bs.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one("bsTransitionEnd",a.proxy(this.hideModal,this)).emulateTransitionEnd(c.TRANSITION_DURATION):this.hideModal())},c.prototype.enforceFocus=function(){a(document).off("focusin.bs.modal").on("focusin.bs.modal",a.proxy(function(a){document===a.target||this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.trigger("focus")},this))},c.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.bs.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.bs.modal")},c.prototype.resize=function(){this.isShown?a(window).on("resize.bs.modal",a.proxy(this.handleUpdate,this)):a(window).off("resize.bs.modal")},c.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.$body.removeClass("modal-open"),a.resetAdjustments(),a.resetScrollbar(),a.$element.trigger("hidden.bs.modal")})},c.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},c.prototype.backdrop=function(b){var d=this,e=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var f=a.support.transition&&e;if(this.$backdrop=a(document.createElement("div")).addClass("modal-backdrop "+e).appendTo(this.$body),this.$element.on("click.dismiss.bs.modal",a.proxy(function(a){return this.ignoreBackdropClick?void(this.ignoreBackdropClick=!1):void(a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus():this.hide()))},this)),f&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;f?this.$backdrop.one("bsTransitionEnd",b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):b()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var g=function(){d.removeBackdrop(),b&&b()};a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("bsTransitionEnd",g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):g()}else b&&b()},c.prototype.handleUpdate=function(){this.adjustDialog()},c.prototype.adjustDialog=function(){var a=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&a?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!a?this.scrollbarWidth:""})},c.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},c.prototype.checkScrollbar=function(){var a=window.innerWidth;if(!a){var b=document.documentElement.getBoundingClientRect();a=b.right-Math.abs(b.left)}this.bodyIsOverflowing=document.body.clientWidth<a,this.scrollbarWidth=this.measureScrollbar()},c.prototype.setScrollbar=function(){var a=parseInt(this.$body.css("padding-right")||0,10);this.originalBodyPad=document.body.style.paddingRight||"",this.bodyIsOverflowing&&this.$body.css("padding-right",a+this.scrollbarWidth)},c.prototype.resetScrollbar=function(){this.$body.css("padding-right",this.originalBodyPad)},c.prototype.measureScrollbar=function(){var a=document.createElement("div");a.className="modal-scrollbar-measure",this.$body.append(a);var b=a.offsetWidth-a.clientWidth;return this.$body[0].removeChild(a),b};var d=a.fn.modal;a.fn.modal=b,a.fn.modal.Constructor=c,a.fn.modal.noConflict=function(){return a.fn.modal=d,this},a(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(c){var d=a(this),e=d.attr("href"),f=a(d.attr("data-target")||e&&e.replace(/.*(?=#[^\s]+$)/,"")),g=f.data("bs.modal")?"toggle":a.extend({remote:!/#/.test(e)&&e},f.data(),d.data());d.is("a")&&c.preventDefault(),f.one("show.bs.modal",function(a){a.isDefaultPrevented()||f.one("hidden.bs.modal",function(){d.is(":visible")&&d.trigger("focus")})}),b.call(f,g,this)})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("bs.tooltip",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",a,b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},c.prototype.init=function(b,c,d){if(this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d),this.$viewport=this.options.viewport&&a(a.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},c.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},c.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);return c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c)),b instanceof a.Event&&(c.inState["focusin"==b.type?"focus":"hover"]=!0),c.tip().hasClass("in")||"in"==c.hoverState?void(c.hoverState="in"):(clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show())},c.prototype.isInStateTrue=function(){for(var a in this.inState)if(this.inState[a])return!0;return!1},c.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);if(c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c)),b instanceof a.Event&&(c.inState["focusout"==b.type?"focus":"hover"]=!1),!c.isInStateTrue())return clearTimeout(c.timeout),c.hoverState="out",c.options.delay&&c.options.delay.hide?void(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)):c.hide()},c.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(b);var d=a.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(b.isDefaultPrevented()||!d)return;var e=this,f=this.tip(),g=this.getUID(this.type);this.setContent(),f.attr("id",g),this.$element.attr("aria-describedby",g),this.options.animation&&f.addClass("fade");var h="function"==typeof this.options.placement?this.options.placement.call(this,f[0],this.$element[0]):this.options.placement,i=/\s?auto?\s?/i,j=i.test(h);j&&(h=h.replace(i,"")||"top"),f.detach().css({top:0,left:0,display:"block"}).addClass(h).data("bs."+this.type,this),this.options.container?f.appendTo(this.options.container):f.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var k=this.getPosition(),l=f[0].offsetWidth,m=f[0].offsetHeight;if(j){var n=h,o=this.getPosition(this.$viewport);h="bottom"==h&&k.bottom+m>o.bottom?"top":"top"==h&&k.top-m<o.top?"bottom":"right"==h&&k.right+l>o.width?"left":"left"==h&&k.left-l<o.left?"right":h,f.removeClass(n).addClass(h)}var p=this.getCalculatedOffset(h,k,l,m);this.applyPlacement(p,h);var q=function(){var a=e.hoverState;e.$element.trigger("shown.bs."+e.type),e.hoverState=null,"out"==a&&e.leave(e)};a.support.transition&&this.$tip.hasClass("fade")?f.one("bsTransitionEnd",q).emulateTransitionEnd(c.TRANSITION_DURATION):q()}},c.prototype.applyPlacement=function(b,c){var d=this.tip(),e=d[0].offsetWidth,f=d[0].offsetHeight,g=parseInt(d.css("margin-top"),10),h=parseInt(d.css("margin-left"),10);isNaN(g)&&(g=0),isNaN(h)&&(h=0),b.top+=g,b.left+=h,a.offset.setOffset(d[0],a.extend({using:function(a){d.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),d.addClass("in");var i=d[0].offsetWidth,j=d[0].offsetHeight;"top"==c&&j!=f&&(b.top=b.top+f-j);var k=this.getViewportAdjustedDelta(c,b,i,j);k.left?b.left+=k.left:b.top+=k.top;var l=/top|bottom/.test(c),m=l?2*k.left-e+i:2*k.top-f+j,n=l?"offsetWidth":"offsetHeight";d.offset(b),this.replaceArrow(m,d[0][n],l)},c.prototype.replaceArrow=function(a,b,c){this.arrow().css(c?"left":"top",50*(1-a/b)+"%").css(c?"top":"left","")},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},c.prototype.hide=function(b){function d(){"in"!=e.hoverState&&f.detach(),e.$element&&e.$element.removeAttr("aria-describedby").trigger("hidden.bs."+e.type),b&&b()}var e=this,f=a(this.$tip),g=a.Event("hide.bs."+this.type);if(this.$element.trigger(g),!g.isDefaultPrevented())return f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one("bsTransitionEnd",d).emulateTransitionEnd(c.TRANSITION_DURATION):d(),this.hoverState=null,this},c.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},c.prototype.hasContent=function(){return this.getTitle()},c.prototype.getPosition=function(b){b=b||this.$element;var c=b[0],d="BODY"==c.tagName,e=c.getBoundingClientRect();null==e.width&&(e=a.extend({},e,{width:e.right-e.left,height:e.bottom-e.top}));var f=window.SVGElement&&c instanceof window.SVGElement,g=d?{top:0,left:0}:f?null:b.offset(),h={scroll:d?document.documentElement.scrollTop||document.body.scrollTop:b.scrollTop()},i=d?{width:a(window).width(),height:a(window).height()}:null;return a.extend({},e,h,i,g)},c.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},c.prototype.getViewportAdjustedDelta=function(a,b,c,d){var e={top:0,left:0};if(!this.$viewport)return e;var f=this.options.viewport&&this.options.viewport.padding||0,g=this.getPosition(this.$viewport);if(/right|left/.test(a)){var h=b.top-f-g.scroll,i=b.top+f-g.scroll+d;h<g.top?e.top=g.top-h:i>g.top+g.height&&(e.top=g.top+g.height-i)}else{var j=b.left-f,k=b.left+f+c;j<g.left?e.left=g.left-j:k>g.right&&(e.left=g.left+g.width-k)}return e},c.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},c.prototype.getUID=function(a){do a+=~~(1e6*Math.random());while(document.getElementById(a));return a},c.prototype.tip=function(){if(!this.$tip&&(this.$tip=a(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},c.prototype.enable=function(){this.enabled=!0},c.prototype.disable=function(){this.enabled=!1},c.prototype.toggleEnabled=function(){this.enabled=!this.enabled},c.prototype.toggle=function(b){var c=this;b&&(c=a(b.currentTarget).data("bs."+this.type),c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c))),b?(c.inState.click=!c.inState.click,c.isInStateTrue()?c.enter(c):c.leave(c)):c.tip().hasClass("in")?c.leave(c):c.enter(c)},c.prototype.destroy=function(){var a=this;clearTimeout(this.timeout),this.hide(function(){a.$element.off("."+a.type).removeData("bs."+a.type),a.$tip&&a.$tip.detach(),a.$tip=null,a.$arrow=null,a.$viewport=null,a.$element=null})};var d=a.fn.tooltip;a.fn.tooltip=b,a.fn.tooltip.Constructor=c,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=d,this}}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.popover"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("bs.popover",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.init("popover",a,b)};if(!a.fn.tooltip)throw new Error("Popover requires tooltip.js");c.VERSION="3.3.7",c.DEFAULTS=a.extend({},a.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),c.prototype=a.extend({},a.fn.tooltip.Constructor.prototype),c.prototype.constructor=c,c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content").children().detach().end()[this.options.html?"string"==typeof c?"html":"append":"text"](c),a.removeClass("fade top bottom left right in"),a.find(".popover-title").html()||a.find(".popover-title").hide()},c.prototype.hasContent=function(){return this.getTitle()||this.getContent()},c.prototype.getContent=function(){var a=this.$element,b=this.options;return a.attr("data-content")||("function"==typeof b.content?b.content.call(a[0]):b.content)},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")};var d=a.fn.popover;a.fn.popover=b,a.fn.popover.Constructor=c,a.fn.popover.noConflict=function(){return a.fn.popover=d,this}}(jQuery),+function(a){"use strict";function b(c,d){this.$body=a(document.body),this.$scrollElement=a(a(c).is(document.body)?window:c),this.options=a.extend({},b.DEFAULTS,d),this.selector=(this.options.target||"")+" .nav li > a",this.offsets=[],this.targets=[],this.activeTarget=null,this.scrollHeight=0,this.$scrollElement.on("scroll.bs.scrollspy",a.proxy(this.process,this)),this.refresh(),this.process()}function c(c){return this.each(function(){var d=a(this),e=d.data("bs.scrollspy"),f="object"==typeof c&&c;e||d.data("bs.scrollspy",e=new b(this,f)),"string"==typeof c&&e[c]()})}b.VERSION="3.3.7",b.DEFAULTS={offset:10},b.prototype.getScrollHeight=function(){return this.$scrollElement[0].scrollHeight||Math.max(this.$body[0].scrollHeight,document.documentElement.scrollHeight)},b.prototype.refresh=function(){var b=this,c="offset",d=0;this.offsets=[],this.targets=[],this.scrollHeight=this.getScrollHeight(),a.isWindow(this.$scrollElement[0])||(c="position",d=this.$scrollElement.scrollTop()),this.$body.find(this.selector).map(function(){var b=a(this),e=b.data("target")||b.attr("href"),f=/^#./.test(e)&&a(e);return f&&f.length&&f.is(":visible")&&[[f[c]().top+d,e]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){b.offsets.push(this[0]),b.targets.push(this[1])})},b.prototype.process=function(){var a,b=this.$scrollElement.scrollTop()+this.options.offset,c=this.getScrollHeight(),d=this.options.offset+c-this.$scrollElement.height(),e=this.offsets,f=this.targets,g=this.activeTarget;if(this.scrollHeight!=c&&this.refresh(),b>=d)return g!=(a=f[f.length-1])&&this.activate(a);if(g&&b<e[0])return this.activeTarget=null,this.clear();for(a=e.length;a--;)g!=f[a]&&b>=e[a]&&(void 0===e[a+1]||b<e[a+1])&&this.activate(f[a])},b.prototype.activate=function(b){
this.activeTarget=b,this.clear();var c=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',d=a(c).parents("li").addClass("active");d.parent(".dropdown-menu").length&&(d=d.closest("li.dropdown").addClass("active")),d.trigger("activate.bs.scrollspy")},b.prototype.clear=function(){a(this.selector).parentsUntil(this.options.target,".active").removeClass("active")};var d=a.fn.scrollspy;a.fn.scrollspy=c,a.fn.scrollspy.Constructor=b,a.fn.scrollspy.noConflict=function(){return a.fn.scrollspy=d,this},a(window).on("load.bs.scrollspy.data-api",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);c.call(b,b.data())})})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new c(this)),"string"==typeof b&&e[b]()})}var c=function(b){this.element=a(b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a"),f=a.Event("hide.bs.tab",{relatedTarget:b[0]}),g=a.Event("show.bs.tab",{relatedTarget:e[0]});if(e.trigger(f),b.trigger(g),!g.isDefaultPrevented()&&!f.isDefaultPrevented()){var h=a(d);this.activate(b.closest("li"),c),this.activate(h,h.parent(),function(){e.trigger({type:"hidden.bs.tab",relatedTarget:b[0]}),b.trigger({type:"shown.bs.tab",relatedTarget:e[0]})})}}},c.prototype.activate=function(b,d,e){function f(){g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!1),b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded",!0),h?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu").length&&b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!0),e&&e()}var g=d.find("> .active"),h=e&&a.support.transition&&(g.length&&g.hasClass("fade")||!!d.find("> .fade").length);g.length&&h?g.one("bsTransitionEnd",f).emulateTransitionEnd(c.TRANSITION_DURATION):f(),g.removeClass("in")};var d=a.fn.tab;a.fn.tab=b,a.fn.tab.Constructor=c,a.fn.tab.noConflict=function(){return a.fn.tab=d,this};var e=function(c){c.preventDefault(),b.call(a(this),"show")};a(document).on("click.bs.tab.data-api",'[data-toggle="tab"]',e).on("click.bs.tab.data-api",'[data-toggle="pill"]',e)}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.affix"),f="object"==typeof b&&b;e||d.data("bs.affix",e=new c(this,f)),"string"==typeof b&&e[b]()})}var c=function(b,d){this.options=a.extend({},c.DEFAULTS,d),this.$target=a(this.options.target).on("scroll.bs.affix.data-api",a.proxy(this.checkPosition,this)).on("click.bs.affix.data-api",a.proxy(this.checkPositionWithEventLoop,this)),this.$element=a(b),this.affixed=null,this.unpin=null,this.pinnedOffset=null,this.checkPosition()};c.VERSION="3.3.7",c.RESET="affix affix-top affix-bottom",c.DEFAULTS={offset:0,target:window},c.prototype.getState=function(a,b,c,d){var e=this.$target.scrollTop(),f=this.$element.offset(),g=this.$target.height();if(null!=c&&"top"==this.affixed)return e<c&&"top";if("bottom"==this.affixed)return null!=c?!(e+this.unpin<=f.top)&&"bottom":!(e+g<=a-d)&&"bottom";var h=null==this.affixed,i=h?e:f.top,j=h?g:b;return null!=c&&e<=c?"top":null!=d&&i+j>=a-d&&"bottom"},c.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset;this.$element.removeClass(c.RESET).addClass("affix");var a=this.$target.scrollTop(),b=this.$element.offset();return this.pinnedOffset=b.top-a},c.prototype.checkPositionWithEventLoop=function(){setTimeout(a.proxy(this.checkPosition,this),1)},c.prototype.checkPosition=function(){if(this.$element.is(":visible")){var b=this.$element.height(),d=this.options.offset,e=d.top,f=d.bottom,g=Math.max(a(document).height(),a(document.body).height());"object"!=typeof d&&(f=e=d),"function"==typeof e&&(e=d.top(this.$element)),"function"==typeof f&&(f=d.bottom(this.$element));var h=this.getState(g,b,e,f);if(this.affixed!=h){null!=this.unpin&&this.$element.css("top","");var i="affix"+(h?"-"+h:""),j=a.Event(i+".bs.affix");if(this.$element.trigger(j),j.isDefaultPrevented())return;this.affixed=h,this.unpin="bottom"==h?this.getPinnedOffset():null,this.$element.removeClass(c.RESET).addClass(i).trigger(i.replace("affix","affixed")+".bs.affix")}"bottom"==h&&this.$element.offset({top:g-b-f})}};var d=a.fn.affix;a.fn.affix=b,a.fn.affix.Constructor=c,a.fn.affix.noConflict=function(){return a.fn.affix=d,this},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var c=a(this),d=c.data();d.offset=d.offset||{},null!=d.offsetBottom&&(d.offset.bottom=d.offsetBottom),null!=d.offsetTop&&(d.offset.top=d.offsetTop),b.call(c,d)})})}(jQuery);
			!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t("object"==typeof exports?require("jquery"):jQuery)}(function(t){function s(s){var e=!1;return t('[data-notify="container"]').each(function(i,n){var a=t(n),o=a.find('[data-notify="title"]').text().trim(),r=a.find('[data-notify="message"]').html().trim(),l=o===t("<div>"+s.settings.content.title+"</div>").html().trim(),d=r===t("<div>"+s.settings.content.message+"</div>").html().trim(),g=a.hasClass("alert-"+s.settings.type);return l&&d&&g&&(e=!0),!e}),e}function e(e,n,a){var o={content:{message:"object"==typeof n?n.message:n,title:n.title?n.title:"",icon:n.icon?n.icon:"",url:n.url?n.url:"#",target:n.target?n.target:"-"}};a=t.extend(!0,{},o,a),this.settings=t.extend(!0,{},i,a),this._defaults=i,"-"===this.settings.content.target&&(this.settings.content.target=this.settings.url_target),this.animations={start:"webkitAnimationStart oanimationstart MSAnimationStart animationstart",end:"webkitAnimationEnd oanimationend MSAnimationEnd animationend"},"number"==typeof this.settings.offset&&(this.settings.offset={x:this.settings.offset,y:this.settings.offset}),(this.settings.allow_duplicates||!this.settings.allow_duplicates&&!s(this))&&this.init()}var i={element:"body",position:null,type:"info",allow_dismiss:!0,allow_duplicates:!0,newest_on_top:!1,showProgressbar:!1,placement:{from:"top",align:"right"},offset:20,spacing:10,z_index:1031,delay:5e3,timer:1e3,url_target:"_blank",mouse_over:null,animate:{enter:"animated fadeInDown",exit:"animated fadeOutUp"},onShow:null,onShown:null,onClose:null,onClosed:null,icon_type:"class",template:'<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'};String.format=function(){for(var t=arguments[0],s=1;s<arguments.length;s++)t=t.replace(RegExp("\\{"+(s-1)+"\\}","gm"),arguments[s]);return t},t.extend(e.prototype,{init:function(){var t=this;this.buildNotify(),this.settings.content.icon&&this.setIcon(),"#"!=this.settings.content.url&&this.styleURL(),this.styleDismiss(),this.placement(),this.bind(),this.notify={$ele:this.$ele,update:function(s,e){var i={};"string"==typeof s?i[s]=e:i=s;for(var n in i)switch(n){case"type":this.$ele.removeClass("alert-"+t.settings.type),this.$ele.find('[data-notify="progressbar"] > .progress-bar').removeClass("progress-bar-"+t.settings.type),t.settings.type=i[n],this.$ele.addClass("alert-"+i[n]).find('[data-notify="progressbar"] > .progress-bar').addClass("progress-bar-"+i[n]);break;case"icon":var a=this.$ele.find('[data-notify="icon"]');"class"===t.settings.icon_type.toLowerCase()?a.removeClass(t.settings.content.icon).addClass(i[n]):(a.is("img")||a.find("img"),a.attr("src",i[n]));break;case"progress":var o=t.settings.delay-t.settings.delay*(i[n]/100);this.$ele.data("notify-delay",o),this.$ele.find('[data-notify="progressbar"] > div').attr("aria-valuenow",i[n]).css("width",i[n]+"%");break;case"url":this.$ele.find('[data-notify="url"]').attr("href",i[n]);break;case"target":this.$ele.find('[data-notify="url"]').attr("target",i[n]);break;default:this.$ele.find('[data-notify="'+n+'"]').html(i[n])}var r=this.$ele.outerHeight()+parseInt(t.settings.spacing)+parseInt(t.settings.offset.y);t.reposition(r)},close:function(){t.close()}}},buildNotify:function(){var s=this.settings.content;this.$ele=t(String.format(this.settings.template,this.settings.type,s.title,s.message,s.url,s.target)),this.$ele.attr("data-notify-position",this.settings.placement.from+"-"+this.settings.placement.align),this.settings.allow_dismiss||this.$ele.find('[data-notify="dismiss"]').css("display","none"),(this.settings.delay<=0&&!this.settings.showProgressbar||!this.settings.showProgressbar)&&this.$ele.find('[data-notify="progressbar"]').remove()},setIcon:function(){"class"===this.settings.icon_type.toLowerCase()?this.$ele.find('[data-notify="icon"]').addClass(this.settings.content.icon):this.$ele.find('[data-notify="icon"]').is("img")?this.$ele.find('[data-notify="icon"]').attr("src",this.settings.content.icon):this.$ele.find('[data-notify="icon"]').append('<img src="'+this.settings.content.icon+'" alt="Notify Icon" />')},styleDismiss:function(){this.$ele.find('[data-notify="dismiss"]').css({position:"absolute",right:"10px",top:"5px",zIndex:this.settings.z_index+2})},styleURL:function(){this.$ele.find('[data-notify="url"]').css({backgroundImage:"url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)",height:"100%",left:0,position:"absolute",top:0,width:"100%",zIndex:this.settings.z_index+1})},placement:function(){var s=this,e=this.settings.offset.y,i={display:"inline-block",margin:"0px auto",position:this.settings.position?this.settings.position:"body"===this.settings.element?"fixed":"absolute",transition:"all .5s ease-in-out",zIndex:this.settings.z_index},n=!1,a=this.settings;switch(t('[data-notify-position="'+this.settings.placement.from+"-"+this.settings.placement.align+'"]:not([data-closing="true"])').each(function(){e=Math.max(e,parseInt(t(this).css(a.placement.from))+parseInt(t(this).outerHeight())+parseInt(a.spacing))}),this.settings.newest_on_top===!0&&(e=this.settings.offset.y),i[this.settings.placement.from]=e+"px",this.settings.placement.align){case"left":case"right":i[this.settings.placement.align]=this.settings.offset.x+"px";break;case"center":i.left=0,i.right=0}this.$ele.css(i).addClass(this.settings.animate.enter),t.each(Array("webkit-","moz-","o-","ms-",""),function(t,e){s.$ele[0].style[e+"AnimationIterationCount"]=1}),t(this.settings.element).append(this.$ele),this.settings.newest_on_top===!0&&(e=parseInt(e)+parseInt(this.settings.spacing)+this.$ele.outerHeight(),this.reposition(e)),t.isFunction(s.settings.onShow)&&s.settings.onShow.call(this.$ele),this.$ele.one(this.animations.start,function(){n=!0}).one(this.animations.end,function(){s.$ele.removeClass(s.settings.animate.enter),t.isFunction(s.settings.onShown)&&s.settings.onShown.call(this)}),setTimeout(function(){n||t.isFunction(s.settings.onShown)&&s.settings.onShown.call(this)},600)},bind:function(){var s=this;if(this.$ele.find('[data-notify="dismiss"]').on("click",function(){s.close()}),this.$ele.mouseover(function(){t(this).data("data-hover","true")}).mouseout(function(){t(this).data("data-hover","false")}),this.$ele.data("data-hover","false"),this.settings.delay>0){s.$ele.data("notify-delay",s.settings.delay);var e=setInterval(function(){var t=parseInt(s.$ele.data("notify-delay"))-s.settings.timer;if("false"===s.$ele.data("data-hover")&&"pause"===s.settings.mouse_over||"pause"!=s.settings.mouse_over){var i=(s.settings.delay-t)/s.settings.delay*100;s.$ele.data("notify-delay",t),s.$ele.find('[data-notify="progressbar"] > div').attr("aria-valuenow",i).css("width",i+"%")}t<=-s.settings.timer&&(clearInterval(e),s.close())},s.settings.timer)}},close:function(){var s=this,e=parseInt(this.$ele.css(this.settings.placement.from)),i=!1;this.$ele.attr("data-closing","true").addClass(this.settings.animate.exit),s.reposition(e),t.isFunction(s.settings.onClose)&&s.settings.onClose.call(this.$ele),this.$ele.one(this.animations.start,function(){i=!0}).one(this.animations.end,function(){t(this).remove(),t.isFunction(s.settings.onClosed)&&s.settings.onClosed.call(this)}),setTimeout(function(){i||(s.$ele.remove(),s.settings.onClosed&&s.settings.onClosed(s.$ele))},600)},reposition:function(s){var e=this,i='[data-notify-position="'+this.settings.placement.from+"-"+this.settings.placement.align+'"]:not([data-closing="true"])',n=this.$ele.nextAll(i);this.settings.newest_on_top===!0&&(n=this.$ele.prevAll(i)),n.each(function(){t(this).css(e.settings.placement.from,s),s=parseInt(s)+parseInt(e.settings.spacing)+t(this).outerHeight()})}}),t.notify=function(t,s){var i=new e(this,t,s);return i.notify},t.notifyDefaults=function(s){return i=t.extend(!0,{},i,s)},t.notifyClose=function(s){"warning"===s&&(s="danger"),"undefined"==typeof s||"all"===s?t("[data-notify]").find('[data-notify="dismiss"]').trigger("click"):"success"===s||"info"===s||"warning"===s||"danger"===s?t(".alert-"+s+"[data-notify]").find('[data-notify="dismiss"]').trigger("click"):s?t(s+"[data-notify]").find('[data-notify="dismiss"]').trigger("click"):t('[data-notify-position="'+s+'"]').find('[data-notify="dismiss"]').trigger("click")},t.notifyCloseExcept=function(s){"warning"===s&&(s="danger"),"success"===s||"info"===s||"warning"===s||"danger"===s?t("[data-notify]").not(".alert-"+s).find('[data-notify="dismiss"]').trigger("click"):t("[data-notify]").not(s).find('[data-notify="dismiss"]').trigger("click")}});
			!function(a,b,c,d){"use strict";var e="treeview",f={};f.settings={injectStyle:!0,levels:2,expandIcon:"glyphicon glyphicon-plus",collapseIcon:"glyphicon glyphicon-minus",loadingIcon:"glyphicon glyphicon-hourglass",emptyIcon:"glyphicon",nodeIcon:"",selectedIcon:"",checkedIcon:"glyphicon glyphicon-check",partiallyCheckedIcon:"glyphicon glyphicon-expand",uncheckedIcon:"glyphicon glyphicon-unchecked",tagsClass:"badge",color:d,backColor:d,borderColor:d,changedNodeColor:"#39A5DC",onhoverColor:"#F5F5F5",selectedColor:"#FFFFFF",selectedBackColor:"#428bca",searchResultColor:"#D9534F",searchResultBackColor:d,highlightSelected:!0,highlightSearchResults:!0,showBorder:!0,showIcon:!0,showImage:!1,showCheckbox:!1,checkboxFirst:!1,highlightChanges:!1,showTags:!1,multiSelect:!1,preventUnselect:!1,allowReselect:!1,hierarchicalCheck:!1,propagateCheckEvent:!1,wrapNodeText:!1,onLoading:d,onLoadingFailed:d,onInitialized:d,onNodeRendered:d,onRendered:d,onDestroyed:d,onNodeChecked:d,onNodeCollapsed:d,onNodeDisabled:d,onNodeEnabled:d,onNodeExpanded:d,onNodeSelected:d,onNodeUnchecked:d,onNodeUnselected:d,onSearchComplete:d,onSearchCleared:d},f.options={silent:!1,ignoreChildren:!1},f.searchOptions={ignoreCase:!0,exactMatch:!1,revealResults:!0},f.dataUrl={method:"GET",dataType:"json",cache:!1};var g=function(b,c){return this.$element=a(b),this._elementId=b.id,this._styleId=this._elementId+"-style",this._init(c),{options:this._options,init:a.proxy(this._init,this),remove:a.proxy(this._remove,this),findNodes:a.proxy(this.findNodes,this),getNodes:a.proxy(this.getNodes,this),getParents:a.proxy(this.getParents,this),getSiblings:a.proxy(this.getSiblings,this),getSelected:a.proxy(this.getSelected,this),getUnselected:a.proxy(this.getUnselected,this),getExpanded:a.proxy(this.getExpanded,this),getCollapsed:a.proxy(this.getCollapsed,this),getChecked:a.proxy(this.getChecked,this),getUnchecked:a.proxy(this.getUnchecked,this),getDisabled:a.proxy(this.getDisabled,this),getEnabled:a.proxy(this.getEnabled,this),addNode:a.proxy(this.addNode,this),addNodeAfter:a.proxy(this.addNodeAfter,this),addNodeBefore:a.proxy(this.addNodeBefore,this),removeNode:a.proxy(this.removeNode,this),updateNode:a.proxy(this.updateNode,this),selectNode:a.proxy(this.selectNode,this),unselectNode:a.proxy(this.unselectNode,this),toggleNodeSelected:a.proxy(this.toggleNodeSelected,this),collapseAll:a.proxy(this.collapseAll,this),collapseNode:a.proxy(this.collapseNode,this),expandAll:a.proxy(this.expandAll,this),expandNode:a.proxy(this.expandNode,this),toggleNodeExpanded:a.proxy(this.toggleNodeExpanded,this),revealNode:a.proxy(this.revealNode,this),checkAll:a.proxy(this.checkAll,this),checkNode:a.proxy(this.checkNode,this),uncheckAll:a.proxy(this.uncheckAll,this),uncheckNode:a.proxy(this.uncheckNode,this),toggleNodeChecked:a.proxy(this.toggleNodeChecked,this),unmarkCheckboxChanges:a.proxy(this.unmarkCheckboxChanges,this),disableAll:a.proxy(this.disableAll,this),disableNode:a.proxy(this.disableNode,this),enableAll:a.proxy(this.enableAll,this),enableNode:a.proxy(this.enableNode,this),toggleNodeDisabled:a.proxy(this.toggleNodeDisabled,this),search:a.proxy(this.search,this),clearSearch:a.proxy(this.clearSearch,this)}};g.prototype._init=function(b){this._tree=[],this._initialized=!1,this._options=a.extend({},f.settings,b),this._template.icon.empty.addClass(this._options.emptyIcon),this._destroy(),this._subscribeEvents(),this._triggerEvent("loading",null,f.options),this._load(b).then(a.proxy(function(b){return this._tree=a.extend(!0,[],b)},this),a.proxy(function(a){this._triggerEvent("loadingFailed",a,f.options)},this)).then(a.proxy(function(a){return this._setInitialStates({nodes:a},0)},this)).then(a.proxy(function(){this._render()},this))},g.prototype._load=function(b){var c=new a.Deferred;return b.data?this._loadLocalData(b,c):b.dataUrl&&this._loadRemoteData(b,c),c.promise()},g.prototype._loadRemoteData=function(b,c){a.ajax(a.extend(!0,{},f.dataUrl,b.dataUrl)).done(function(a){c.resolve(a)}).fail(function(a,b,d){c.reject(d)})},g.prototype._loadLocalData=function(b,c){c.resolve("string"==typeof b.data?JSON.parse(b.data):a.extend(!0,[],b.data))},g.prototype._remove=function(){this._destroy(),a.removeData(this,e),a("#"+this._styleId).remove()},g.prototype._destroy=function(){this._initialized&&(this._initialized=!1,this._triggerEvent("destroyed",null,f.options),this._unsubscribeEvents(),this.$wrapper.remove(),this.$wrapper=null)},g.prototype._unsubscribeEvents=function(){this.$element.off("loading"),this.$element.off("loadingFailed"),this.$element.off("initialized"),this.$element.off("nodeRendered"),this.$element.off("rendered"),this.$element.off("destroyed"),this.$element.off("click"),this.$element.off("nodeChecked"),this.$element.off("nodeCollapsed"),this.$element.off("nodeDisabled"),this.$element.off("nodeEnabled"),this.$element.off("nodeExpanded"),this.$element.off("nodeSelected"),this.$element.off("nodeUnchecked"),this.$element.off("nodeUnselected"),this.$element.off("searchComplete"),this.$element.off("searchCleared")},g.prototype._subscribeEvents=function(){this._unsubscribeEvents(),"function"==typeof this._options.onLoading&&this.$element.on("loading",this._options.onLoading),"function"==typeof this._options.onLoadingFailed&&this.$element.on("loadingFailed",this._options.onLoadingFailed),"function"==typeof this._options.onInitialized&&this.$element.on("initialized",this._options.onInitialized),"function"==typeof this._options.onNodeRendered&&this.$element.on("nodeRendered",this._options.onNodeRendered),"function"==typeof this._options.onRendered&&this.$element.on("rendered",this._options.onRendered),"function"==typeof this._options.onDestroyed&&this.$element.on("destroyed",this._options.onDestroyed),this.$element.on("click",a.proxy(this._clickHandler,this)),"function"==typeof this._options.onNodeChecked&&this.$element.on("nodeChecked",this._options.onNodeChecked),"function"==typeof this._options.onNodeCollapsed&&this.$element.on("nodeCollapsed",this._options.onNodeCollapsed),"function"==typeof this._options.onNodeDisabled&&this.$element.on("nodeDisabled",this._options.onNodeDisabled),"function"==typeof this._options.onNodeEnabled&&this.$element.on("nodeEnabled",this._options.onNodeEnabled),"function"==typeof this._options.onNodeExpanded&&this.$element.on("nodeExpanded",this._options.onNodeExpanded),"function"==typeof this._options.onNodeSelected&&this.$element.on("nodeSelected",this._options.onNodeSelected),"function"==typeof this._options.onNodeUnchecked&&this.$element.on("nodeUnchecked",this._options.onNodeUnchecked),"function"==typeof this._options.onNodeUnselected&&this.$element.on("nodeUnselected",this._options.onNodeUnselected),"function"==typeof this._options.onSearchComplete&&this.$element.on("searchComplete",this._options.onSearchComplete),"function"==typeof this._options.onSearchCleared&&this.$element.on("searchCleared",this._options.onSearchCleared)},g.prototype._triggerEvent=function(b,c,d){d&&!d.silent&&this.$element.trigger(b,a.extend(!0,{},c))},g.prototype._setInitialStates=function(b,c){return this._nodes={},a.when.apply(this,this._setInitialState(b,c)).done(a.proxy(function(){this._orderedNodes=this._sortNodes(),this._inheritCheckboxChanges(),this._triggerEvent("initialized",this._orderedNodes,f.options)},this))},g.prototype._setInitialState=function(b,c,e){if(b.nodes){c+=1,e=e||[];var f=b;return a.each(b.nodes,a.proxy(function(b,g){var h=new a.Deferred;e.push(h.promise()),g.level=c,g.index=b,g.nodeId=f&&f.nodeId?f.nodeId+"."+g.index:c-1+"."+g.index,g.parentId=f.nodeId,g.hasOwnProperty("selectable")||(g.selectable=!0),g.hasOwnProperty("checkable")||(g.checkable=!0),g.state=g.state||{},g.state.hasOwnProperty("checked")||(g.state.checked=!1),this._options.hierarchicalCheck&&"undefined"===g.state.checked&&(g.state.checked=d),g.state.hasOwnProperty("disabled")||(g.state.disabled=!1),g.state.hasOwnProperty("expanded")||(!g.state.disabled&&c<this._options.levels&&g.nodes&&g.nodes.length>0?g.state.expanded=!0:g.state.expanded=!1),g.state.hasOwnProperty("selected")||(g.state.selected=!1),f&&f.state&&f.state.expanded||c<=this._options.levels?g.state.visible=!0:g.state.visible=!1,g.nodes&&(g.nodes.length>0?this._setInitialState(g,c,e):delete g.nodes),this._nodes[g.nodeId]=g,h.resolve()},this)),e}},g.prototype._sortNodes=function(){return a.map(Object.keys(this._nodes).sort(function(a,b){if(a===b)return 0;for(var a=a.split(".").map(function(a){return parseInt(a)}),b=b.split(".").map(function(a){return parseInt(a)}),c=Math.max(a.length,b.length),e=0;e<c;e++){if(a[e]===d)return-1;if(b[e]===d)return 1;if(a[e]-b[e]>0)return 1;if(a[e]-b[e]<0)return-1}}),a.proxy(function(a,b){return this._nodes[a]},this))},g.prototype._clickHandler=function(b){var c=a(b.target),d=this.targetNode(c);if(d&&!d.state.disabled){var e=c.attr("class")?c.attr("class").split(" "):[];e.indexOf("expand-icon")!==-1?this._toggleExpanded(d,a.extend({},f.options)):e.indexOf("check-icon")!==-1?d.checkable&&this._toggleChecked(d,a.extend({},f.options)):d.selectable?this._toggleSelected(d,a.extend({},f.options)):this._toggleExpanded(d,a.extend({},f.options))}},g.prototype.targetNode=function(a){var b=a.closest("li.list-group-item").attr("data-nodeId"),c=this._nodes[b];return c||console.log("Error: node does not exist"),c},g.prototype._toggleExpanded=function(a,b){a&&("function"==typeof this._options.lazyLoad&&a.lazyLoad?this._lazyLoad(a):this._setExpanded(a,!a.state.expanded,b))},g.prototype._lazyLoad=function(a){a.$el.children("span.expand-icon").removeClass(this._options.expandIcon).addClass(this._options.loadingIcon);var b=this;this._options.lazyLoad(a,function(c){b.addNode(c,a)}),delete a.lazyLoad},g.prototype._setExpanded=function(b,c,d){d&&c===b.state.expanded||(c&&b.nodes?(b.state.expanded=!0,b.$el&&b.$el.children("span.expand-icon").removeClass(this._options.expandIcon).removeClass(this._options.loadingIcon).addClass(this._options.collapseIcon),b.nodes&&d&&a.each(b.nodes,a.proxy(function(a,b){this._setVisible(b,!0,d)},this)),this._triggerEvent("nodeExpanded",b,d)):c||(b.state.expanded=!1,b.$el&&b.$el.children("span.expand-icon").removeClass(this._options.collapseIcon).addClass(this._options.expandIcon),b.nodes&&d&&a.each(b.nodes,a.proxy(function(a,b){this._setVisible(b,!1,d),this._setExpanded(b,!1,d)},this)),this._triggerEvent("nodeCollapsed",b,d)))},g.prototype._setVisible=function(a,b,c){c&&b===a.state.visible||(b?(a.state.visible=!0,a.$el&&a.$el.removeClass("node-hidden")):(a.state.visible=!1,a.$el&&a.$el.addClass("node-hidden")))},g.prototype._toggleSelected=function(a,b){if(a)return this._setSelected(a,!a.state.selected,b),this},g.prototype._setSelected=function(b,c,d){if(!d||c!==b.state.selected){if(c)this._options.multiSelect||a.each(this._findNodes("true","state.selected"),a.proxy(function(b,c){this._setSelected(c,!1,a.extend(d,{unselecting:!0}))},this)),b.state.selected=!0,b.$el&&(b.$el.addClass("node-selected"),(b.selectedIcon||this._options.selectedIcon)&&b.$el.children("span.node-icon").removeClass(b.icon||this._options.nodeIcon).addClass(b.selectedIcon||this._options.selectedIcon)),this._triggerEvent("nodeSelected",b,d);else{if(this._options.preventUnselect&&d&&!d.unselecting&&1===this._findNodes("true","state.selected").length)return this._options.allowReselect&&this._triggerEvent("nodeSelected",b,d),this;b.state.selected=!1,b.$el&&(b.$el.removeClass("node-selected"),(b.selectedIcon||this._options.selectedIcon)&&b.$el.children("span.node-icon").removeClass(b.selectedIcon||this._options.selectedIcon).addClass(b.icon||this._options.nodeIcon)),this._triggerEvent("nodeUnselected",b,d)}return this}},g.prototype._inheritCheckboxChanges=function(){this._options.showCheckbox&&this._options.highlightChanges&&(this._checkedNodes=a.grep(this._orderedNodes,function(a){return a.state.checked}))},g.prototype._toggleChecked=function(b,c){if(b){if(this._options.hierarchicalCheck){var e,f=a.extend({},c,{silent:c.silent||!this._options.propagateCheckEvent}),g=b;for(b.state.checked=!b.state.checked;g=this._nodes[g.parentId];)e=g.nodes.reduce(function(a,b){return a===b.state.checked?a:d},g.nodes[0].state.checked),this._setChecked(g,e,f);if(b.nodes&&b.nodes.length>0)for(var h,i=b.nodes.slice();i&&i.length>0;)h=i.pop(),this._setChecked(h,b.state.checked,f),h.nodes&&h.nodes.length>0&&(i=i.concat(h.nodes.slice()));b.state.checked=!b.state.checked}this._setChecked(b,!b.state.checked,c)}},g.prototype._setChecked=function(a,b,c){c&&b===a.state.checked||(this._options.highlightChanges&&a.$el.toggleClass("node-check-changed",this._checkedNodes.indexOf(a)==-1==b),b?(a.state.checked=!0,a.$el&&(a.$el.addClass("node-checked").removeClass("node-checked-partial"),a.$el.children("span.check-icon").removeClass(this._options.uncheckedIcon).removeClass(this._options.partiallyCheckedIcon).addClass(this._options.checkedIcon)),this._triggerEvent("nodeChecked",a,c)):b===d&&this._options.hierarchicalCheck?(a.state.checked=d,a.$el&&(a.$el.addClass("node-checked-partial").removeClass("node-checked"),a.$el.children("span.check-icon").removeClass(this._options.uncheckedIcon).removeClass(this._options.checkedIcon).addClass(this._options.partiallyCheckedIcon)),this._triggerEvent("nodeUnchecked",a,c)):(a.state.checked=!1,a.$el&&(a.$el.removeClass("node-checked node-checked-partial"),a.$el.children("span.check-icon").removeClass(this._options.checkedIcon).removeClass(this._options.partiallyCheckedIcon).addClass(this._options.uncheckedIcon)),this._triggerEvent("nodeUnchecked",a,c)))},g.prototype._setDisabled=function(a,b,c){c&&b===a.state.disabled||(b?(a.state.disabled=!0,c&&!c.keepState&&(this._setSelected(a,!1,c),this._setChecked(a,!1,c),this._setExpanded(a,!1,c)),a.$el&&a.$el.addClass("node-disabled"),this._triggerEvent("nodeDisabled",a,c)):(a.state.disabled=!1,a.$el&&a.$el.removeClass("node-disabled"),this._triggerEvent("nodeEnabled",a,c)))},g.prototype._setSearchResult=function(a,b,c){c&&b===a.searchResult||(b?(a.searchResult=!0,a.$el&&a.$el.addClass("node-result")):(a.searchResult=!1,a.$el&&a.$el.removeClass("node-result")))},g.prototype._render=function(){this._initialized||(this.$wrapper=this._template.tree.clone(),this.$element.empty().addClass(e).append(this.$wrapper),this._injectStyle(),this._initialized=!0);var b;a.each(this._orderedNodes,a.proxy(function(a,c){this._renderNode(c,b),b=c},this)),this._triggerEvent("rendered",this._orderedNodes,f.options)},g.prototype._renderNode=function(b,c){if(b){b.$el?b.$el.empty():b.$el=this._newNodeEl(b,c).addClass("node-"+this._elementId),b.$el.addClass(b["class"]),b.id&&b.$el.attr("id",b.id),b.dataAttr&&a.each(b.dataAttr,function(a,c){b.$el.attr("data-"+a,c)}),b.$el.attr("data-nodeId",b.nodeId),b.tooltip&&b.$el.attr("title",b.tooltip);for(var e=0;e<b.level-1;e++)b.$el.append(this._template.indent.clone());if(b.$el.append(b.nodes||b.lazyLoad?this._template.icon.expand.clone():this._template.icon.empty.clone()),this._options.checkboxFirst?(this._addCheckbox(b),this._addIcon(b),this._addImage(b)):(this._addIcon(b),this._addImage(b),this._addCheckbox(b)),this._options.wrapNodeText){var g=this._template.text.clone();b.$el.append(g),g.append(b.text)}else b.$el.append(b.text);this._options.showTags&&b.tags&&a.each(b.tags,a.proxy(function(a,c){b.$el.append(this._template.badge.clone().addClass(("object"==typeof c?c["class"]:d)||b.tagsClass||this._options.tagsClass).append(("object"==typeof c?c.text:d)||c))},this)),this._setSelected(b,b.state.selected),this._setChecked(b,b.state.checked),this._setSearchResult(b,b.searchResult),this._setExpanded(b,b.state.expanded),this._setDisabled(b,b.state.disabled),this._setVisible(b,b.state.visible),this._triggerEvent("nodeRendered",b,f.options)}},g.prototype._addCheckbox=function(a){!this._options.showCheckbox||a.hideCheckbox!==d&&a.hideCheckbox!==!1||a.$el.append(this._template.icon.check.clone())},g.prototype._addIcon=function(a){!this._options.showIcon||this._options.showImage&&a.image||a.$el.append(this._template.icon.node.clone().addClass(a.icon||this._options.nodeIcon))},g.prototype._addImage=function(a){this._options.showImage&&a.image&&a.$el.append(this._template.image.clone().addClass("node-image").css("background-image","url('"+a.image+"')"))},g.prototype._newNodeEl=function(a,b){var c=this._template.node.clone();return b?b.$el.after(c):this.$wrapper.prepend(c),c},g.prototype._removeNodeEl=function(b){b&&(b.nodes&&a.each(b.nodes,a.proxy(function(a,b){this._removeNodeEl(b)},this)),b.$el.remove())},g.prototype._expandNode=function(b){b.nodes&&a.each(b.nodes.slice(0).reverse(),a.proxy(function(a,c){c.level=b.level+1,this._renderNode(c,b.$el)},this))},g.prototype._injectStyle=function(){this._options.injectStyle&&!c.getElementById(this._styleId)&&a('<style type="text/css" id="'+this._styleId+'"> '+this._buildStyle()+" </style>").appendTo("head")},g.prototype._buildStyle=function(){var b=".node-"+this._elementId+"{";if(this._options.color&&(b+="color:"+this._options.color+";"),this._options.backColor&&(b+="background-color:"+this._options.backColor+";"),this._options.showBorder?this._options.borderColor&&(b+="border:1px solid "+this._options.borderColor+";"):b+="border:none;",b+="}",this._options.onhoverColor&&(b+=".node-"+this._elementId+":not(.node-disabled):hover{background-color:"+this._options.onhoverColor+";}"),this._options.highlightSearchResults&&(this._options.searchResultColor||this._options.searchResultBackColor)){var c="";this._options.searchResultColor&&(c+="color:"+this._options.searchResultColor+";"),this._options.searchResultBackColor&&(c+="background-color:"+this._options.searchResultBackColor+";"),b+=".node-"+this._elementId+".node-result{"+c+"}",b+=".node-"+this._elementId+".node-result:hover{"+c+"}"}if(this._options.highlightSelected&&(this._options.selectedColor||this._options.selectedBackColor)){var c="";this._options.selectedColor&&(c+="color:"+this._options.selectedColor+";"),this._options.selectedBackColor&&(c+="background-color:"+this._options.selectedBackColor+";"),b+=".node-"+this._elementId+".node-selected{"+c+"}",b+=".node-"+this._elementId+".node-selected:hover{"+c+"}"}if(this._options.highlightChanges){var c="color: "+this._options.changedNodeColor+";";b+=".node-"+this._elementId+".node-check-changed{"+c+"}"}return a.each(this._orderedNodes,a.proxy(function(a,c){if(c.color||c.backColor){var d="";c.color&&(d+="color:"+c.color+";"),c.backColor&&(d+="background-color:"+c.backColor+";"),b+=".node-"+this._elementId+'[data-nodeId="'+c.nodeId+'"]{'+d+"}"}if(c.iconColor){var d="color:"+c.iconColor+";";b+=".node-"+this._elementId+'[data-nodeId="'+c.nodeId+'"] .node-icon{'+d+"}"}},this)),this._css+b},g.prototype._template={tree:a('<ul class="list-group"></ul>'),node:a('<li class="list-group-item"></li>'),indent:a('<span class="indent"></span>'),icon:{node:a('<span class="icon node-icon"></span>'),expand:a('<span class="icon expand-icon"></span>'),check:a('<span class="icon check-icon"></span>'),empty:a('<span class="icon"></span>')},image:a('<span class="image"></span>'),badge:a("<span></span>"),text:a('<span class="text"></span>')},g.prototype._css=".treeview .list-group-item{cursor:pointer}.treeview span.indent{margin-left:10px;margin-right:10px}.treeview span.icon{width:12px;margin-right:5px}.treeview .node-disabled{color:silver;cursor:not-allowed}",g.prototype.findNodes=function(a,b){return this._findNodes(a,b)},g.prototype.getNodes=function(){return this._orderedNodes},g.prototype.getParents=function(b){b instanceof Array||(b=[b]);var c=[];return a.each(b,a.proxy(function(a,b){var d=!!b.parentId&&this._nodes[b.parentId];d&&c.push(d)},this)),c},g.prototype.getSiblings=function(b){b instanceof Array||(b=[b]);var c=[];return a.each(b,a.proxy(function(a,b){var d=this.getParents([b]),e=d[0]?d[0].nodes:this._tree;c=e.filter(function(a){return a.nodeId!==b.nodeId})},this)),a.map(c,function(a){return a})},g.prototype.getSelected=function(){return this._findNodes("true","state.selected")},g.prototype.getUnselected=function(){return this._findNodes("false","state.selected")},g.prototype.getExpanded=function(){return this._findNodes("true","state.expanded")},g.prototype.getCollapsed=function(){return this._findNodes("false","state.expanded")},g.prototype.getChecked=function(){return this._findNodes("true","state.checked")},g.prototype.getUnchecked=function(){return this._findNodes("false","state.checked")},g.prototype.getDisabled=function(){return this._findNodes("true","state.disabled")},g.prototype.getEnabled=function(){return this._findNodes("false","state.disabled")},g.prototype.addNode=function(b,c,d,e){b instanceof Array||(b=[b]),c instanceof Array&&(c=c[0]),e=a.extend({},f.options,e);var g;g=c&&c.nodes?c.nodes:c?c.nodes=[]:this._tree,a.each(b,a.proxy(function(a,b){var c="number"==typeof d?d+a:g.length+1;g.splice(c,0,b)},this)),this._setInitialStates({nodes:this._tree},0).done(a.proxy(function(){c&&!c.state.expanded&&this._setExpanded(c,!0,e),this._render()},this))},g.prototype.addNodeAfter=function(b,c,d){b instanceof Array||(b=[b]),c instanceof Array&&(c=c[0]),d=a.extend({},f.options,d),this.addNode(b,this.getParents(c)[0],c.index+1,d)},g.prototype.addNodeBefore=function(b,c,d){b instanceof Array||(b=[b]),c instanceof Array&&(c=c[0]),d=a.extend({},f.options,d),this.addNode(b,this.getParents(c)[0],c.index,d)},g.prototype.removeNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c);var d,e;a.each(b,a.proxy(function(a,b){e=this._nodes[b.parentId],d=e?e.nodes:this._tree,d.splice(b.index,1),this._removeNodeEl(b)},this)),this._setInitialStates({nodes:this._tree},0).done(this._render.bind(this))},g.prototype.updateNode=function(b,c,d){b instanceof Array&&(b=b[0]),d=a.extend({},f.options,d);var e,g=this._nodes[b.parentId];e=g?g.nodes:this._tree,e.splice(b.index,1,c),this._removeNodeEl(b),this._setInitialStates({nodes:this._tree},0).done(this._render.bind(this))},g.prototype.selectNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setSelected(b,!0,c)},this))},g.prototype.unselectNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setSelected(b,!1,c)},this))},g.prototype.toggleNodeSelected=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._toggleSelected(b,c)},this))},g.prototype.collapseAll=function(b){b=a.extend({},f.options,b),b.levels=b.levels||999,this.collapseNode(this._tree,b)},g.prototype.collapseNode=function(b,c){c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setExpanded(b,!1,c)},this))},g.prototype.expandAll=function(b){b=a.extend({},f.options,b),b.levels=b.levels||999,this.expandNode(this._tree,b)},g.prototype.expandNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){b.state.expanded||("function"==typeof this._options.lazyLoad&&b.lazyLoad&&this._lazyLoad(b),this._setExpanded(b,!0,c),b.nodes&&this._expandLevels(b.nodes,c.levels-1,c))},this))},g.prototype._expandLevels=function(b,c,d){b instanceof Array||(b=[b]),d=a.extend({},f.options,d),a.each(b,a.proxy(function(a,b){this._setExpanded(b,c>0,d),b.nodes&&this._expandLevels(b.nodes,c-1,d)},this))},g.prototype.revealNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){for(var d,e=b;d=this.getParents([e])[0];)e=d,this._setExpanded(e,!0,c)},this))},g.prototype.toggleNodeExpanded=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._toggleExpanded(b,c)},this))},g.prototype.checkAll=function(b){b=a.extend({},f.options,b);var c=a.grep(this._orderedNodes,function(a){return!a.state.checked});a.each(c,a.proxy(function(a,c){this._setChecked(c,!0,b)},this))},g.prototype.checkNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setChecked(b,!0,c)},this))},g.prototype.uncheckAll=function(b){b=a.extend({},f.options,b);var c=a.grep(this._orderedNodes,function(a){return a.state.checked||a.state.checked===d});a.each(c,a.proxy(function(a,c){this._setChecked(c,!1,b)},this))},g.prototype.uncheckNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setChecked(b,!1,c)},this))},g.prototype.toggleNodeChecked=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._toggleChecked(b,c)},this))},g.prototype.unmarkCheckboxChanges=function(){this._inheritCheckboxChanges(),a.each(this._nodes,function(a,b){b.$el.removeClass("node-check-changed")})},g.prototype.disableAll=function(b){b=a.extend({},f.options,b);var c=this._findNodes("false","state.disabled");a.each(c,a.proxy(function(a,c){this._setDisabled(c,!0,b)},this))},g.prototype.disableNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setDisabled(b,!0,c)},this))},g.prototype.enableAll=function(b){b=a.extend({},f.options,b);var c=this._findNodes("true","state.disabled");a.each(c,a.proxy(function(a,c){this._setDisabled(c,!1,b)},this))},g.prototype.enableNode=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setDisabled(b,!1,c)},this))},g.prototype.toggleNodeDisabled=function(b,c){b instanceof Array||(b=[b]),c=a.extend({},f.options,c),a.each(b,a.proxy(function(a,b){this._setDisabled(b,!b.state.disabled,c)},this))},g.prototype.search=function(b,c){c=a.extend({},f.searchOptions,c);var d=this._getSearchResults(),e=[];if(b&&b.length>0){c.exactMatch&&(b="^"+b+"$");var g="g";c.ignoreCase&&(g+="i"),e=this._findNodes(b,"text",g)}return a.each(this._diffArray(e,d),a.proxy(function(a,b){this._setSearchResult(b,!1,c)},this)),a.each(this._diffArray(d,e),a.proxy(function(a,b){this._setSearchResult(b,!0,c)},this)),e&&c.revealResults&&this.revealNode(e),this._triggerEvent("searchComplete",e,c),e},g.prototype.clearSearch=function(b){b=a.extend({},{render:!0},b);var c=a.each(this._getSearchResults(),a.proxy(function(a,c){this._setSearchResult(c,!1,b)},this));this._triggerEvent("searchCleared",c,b)},g.prototype._getSearchResults=function(){return this._findNodes("true","searchResult")},g.prototype._diffArray=function(b,c){var d=[];return a.grep(c,function(c){a.inArray(c,b)===-1&&d.push(c)}),d},g.prototype._findNodes=function(b,c,d){return c=c||"text",d=d||"g",a.grep(this._orderedNodes,a.proxy(function(a){var e=this._getNodeValue(a,c);if("string"==typeof e)return e.match(new RegExp(b,d))},this))},g.prototype._getNodeValue=function(a,b){var c=b.indexOf(".");if(c>0){var e=a[b.substring(0,c)],f=b.substring(c+1,b.length);return this._getNodeValue(e,f)}return a.hasOwnProperty(b)&&a[b]!==d?a[b].toString():d};var h=function(a){b.console&&b.console.error(a)};a.fn[e]=function(b,c){var d;if(0==this.length)throw"No element has been found!";return this.each(function(){var f=a.data(this,e);"string"==typeof b?f?a.isFunction(f[b])&&"_"!==b.charAt(0)?(c instanceof Array||(c=[c]),d=f[b].apply(f,c)):h("No such method : "+b):h("Not initialized, can not call method : "+b):"boolean"==typeof b?d=f:a.data(this,e,new g(this,a.extend(!0,{},b)))}),d||this}}(jQuery,window,document);
			/*
 * This combined file was created by the DataTables downloader builder:
 *   https://datatables.net/download
 *
 * To rebuild or modify this file with the latest versions of the included
 * software please visit:
 *   https://datatables.net/download/#bs/dt-1.10.16
 *
 * Included libraries:
 *   DataTables 1.10.16
 */

/*!
 DataTables 1.10.16
 ©2008-2017 SpryMedia Ltd - datatables.net/license
*/
(function(h){"function"===typeof define&&define.amd?define(["jquery"],function(E){return h(E,window,document)}):"object"===typeof exports?module.exports=function(E,G){E||(E=window);G||(G="undefined"!==typeof window?require("jquery"):require("jquery")(E));return h(G,E,E.document)}:h(jQuery,window,document)})(function(h,E,G,k){function X(a){var b,c,d={};h.each(a,function(e){if((b=e.match(/^([^A-Z]+?)([A-Z])/))&&-1!=="a aa ai ao as b fn i m o s ".indexOf(b[1]+" "))c=e.replace(b[0],b[2].toLowerCase()),
d[c]=e,"o"===b[1]&&X(a[e])});a._hungarianMap=d}function I(a,b,c){a._hungarianMap||X(a);var d;h.each(b,function(e){d=a._hungarianMap[e];if(d!==k&&(c||b[d]===k))"o"===d.charAt(0)?(b[d]||(b[d]={}),h.extend(!0,b[d],b[e]),I(a[d],b[d],c)):b[d]=b[e]})}function Ca(a){var b=m.defaults.oLanguage,c=a.sZeroRecords;!a.sEmptyTable&&(c&&"No data available in table"===b.sEmptyTable)&&F(a,a,"sZeroRecords","sEmptyTable");!a.sLoadingRecords&&(c&&"Loading..."===b.sLoadingRecords)&&F(a,a,"sZeroRecords","sLoadingRecords");
a.sInfoThousands&&(a.sThousands=a.sInfoThousands);(a=a.sDecimal)&&cb(a)}function db(a){A(a,"ordering","bSort");A(a,"orderMulti","bSortMulti");A(a,"orderClasses","bSortClasses");A(a,"orderCellsTop","bSortCellsTop");A(a,"order","aaSorting");A(a,"orderFixed","aaSortingFixed");A(a,"paging","bPaginate");A(a,"pagingType","sPaginationType");A(a,"pageLength","iDisplayLength");A(a,"searching","bFilter");"boolean"===typeof a.sScrollX&&(a.sScrollX=a.sScrollX?"100%":"");"boolean"===typeof a.scrollX&&(a.scrollX=
a.scrollX?"100%":"");if(a=a.aoSearchCols)for(var b=0,c=a.length;b<c;b++)a[b]&&I(m.models.oSearch,a[b])}function eb(a){A(a,"orderable","bSortable");A(a,"orderData","aDataSort");A(a,"orderSequence","asSorting");A(a,"orderDataType","sortDataType");var b=a.aDataSort;"number"===typeof b&&!h.isArray(b)&&(a.aDataSort=[b])}function fb(a){if(!m.__browser){var b={};m.__browser=b;var c=h("<div/>").css({position:"fixed",top:0,left:-1*h(E).scrollLeft(),height:1,width:1,overflow:"hidden"}).append(h("<div/>").css({position:"absolute",
top:1,left:1,width:100,overflow:"scroll"}).append(h("<div/>").css({width:"100%",height:10}))).appendTo("body"),d=c.children(),e=d.children();b.barWidth=d[0].offsetWidth-d[0].clientWidth;b.bScrollOversize=100===e[0].offsetWidth&&100!==d[0].clientWidth;b.bScrollbarLeft=1!==Math.round(e.offset().left);b.bBounding=c[0].getBoundingClientRect().width?!0:!1;c.remove()}h.extend(a.oBrowser,m.__browser);a.oScroll.iBarWidth=m.__browser.barWidth}function gb(a,b,c,d,e,f){var g,j=!1;c!==k&&(g=c,j=!0);for(;d!==
e;)a.hasOwnProperty(d)&&(g=j?b(g,a[d],d,a):a[d],j=!0,d+=f);return g}function Da(a,b){var c=m.defaults.column,d=a.aoColumns.length,c=h.extend({},m.models.oColumn,c,{nTh:b?b:G.createElement("th"),sTitle:c.sTitle?c.sTitle:b?b.innerHTML:"",aDataSort:c.aDataSort?c.aDataSort:[d],mData:c.mData?c.mData:d,idx:d});a.aoColumns.push(c);c=a.aoPreSearchCols;c[d]=h.extend({},m.models.oSearch,c[d]);ja(a,d,h(b).data())}function ja(a,b,c){var b=a.aoColumns[b],d=a.oClasses,e=h(b.nTh);if(!b.sWidthOrig){b.sWidthOrig=
e.attr("width")||null;var f=(e.attr("style")||"").match(/width:\s*(\d+[pxem%]+)/);f&&(b.sWidthOrig=f[1])}c!==k&&null!==c&&(eb(c),I(m.defaults.column,c),c.mDataProp!==k&&!c.mData&&(c.mData=c.mDataProp),c.sType&&(b._sManualType=c.sType),c.className&&!c.sClass&&(c.sClass=c.className),c.sClass&&e.addClass(c.sClass),h.extend(b,c),F(b,c,"sWidth","sWidthOrig"),c.iDataSort!==k&&(b.aDataSort=[c.iDataSort]),F(b,c,"aDataSort"));var g=b.mData,j=Q(g),i=b.mRender?Q(b.mRender):null,c=function(a){return"string"===
typeof a&&-1!==a.indexOf("@")};b._bAttrSrc=h.isPlainObject(g)&&(c(g.sort)||c(g.type)||c(g.filter));b._setter=null;b.fnGetData=function(a,b,c){var d=j(a,b,k,c);return i&&b?i(d,b,a,c):d};b.fnSetData=function(a,b,c){return R(g)(a,b,c)};"number"!==typeof g&&(a._rowReadObject=!0);a.oFeatures.bSort||(b.bSortable=!1,e.addClass(d.sSortableNone));a=-1!==h.inArray("asc",b.asSorting);c=-1!==h.inArray("desc",b.asSorting);!b.bSortable||!a&&!c?(b.sSortingClass=d.sSortableNone,b.sSortingClassJUI=""):a&&!c?(b.sSortingClass=
d.sSortableAsc,b.sSortingClassJUI=d.sSortJUIAscAllowed):!a&&c?(b.sSortingClass=d.sSortableDesc,b.sSortingClassJUI=d.sSortJUIDescAllowed):(b.sSortingClass=d.sSortable,b.sSortingClassJUI=d.sSortJUI)}function Y(a){if(!1!==a.oFeatures.bAutoWidth){var b=a.aoColumns;Ea(a);for(var c=0,d=b.length;c<d;c++)b[c].nTh.style.width=b[c].sWidth}b=a.oScroll;(""!==b.sY||""!==b.sX)&&ka(a);r(a,null,"column-sizing",[a])}function Z(a,b){var c=la(a,"bVisible");return"number"===typeof c[b]?c[b]:null}function $(a,b){var c=
la(a,"bVisible"),c=h.inArray(b,c);return-1!==c?c:null}function aa(a){var b=0;h.each(a.aoColumns,function(a,d){d.bVisible&&"none"!==h(d.nTh).css("display")&&b++});return b}function la(a,b){var c=[];h.map(a.aoColumns,function(a,e){a[b]&&c.push(e)});return c}function Fa(a){var b=a.aoColumns,c=a.aoData,d=m.ext.type.detect,e,f,g,j,i,h,l,q,t;e=0;for(f=b.length;e<f;e++)if(l=b[e],t=[],!l.sType&&l._sManualType)l.sType=l._sManualType;else if(!l.sType){g=0;for(j=d.length;g<j;g++){i=0;for(h=c.length;i<h;i++){t[i]===
k&&(t[i]=B(a,i,e,"type"));q=d[g](t[i],a);if(!q&&g!==d.length-1)break;if("html"===q)break}if(q){l.sType=q;break}}l.sType||(l.sType="string")}}function hb(a,b,c,d){var e,f,g,j,i,n,l=a.aoColumns;if(b)for(e=b.length-1;0<=e;e--){n=b[e];var q=n.targets!==k?n.targets:n.aTargets;h.isArray(q)||(q=[q]);f=0;for(g=q.length;f<g;f++)if("number"===typeof q[f]&&0<=q[f]){for(;l.length<=q[f];)Da(a);d(q[f],n)}else if("number"===typeof q[f]&&0>q[f])d(l.length+q[f],n);else if("string"===typeof q[f]){j=0;for(i=l.length;j<
i;j++)("_all"==q[f]||h(l[j].nTh).hasClass(q[f]))&&d(j,n)}}if(c){e=0;for(a=c.length;e<a;e++)d(e,c[e])}}function M(a,b,c,d){var e=a.aoData.length,f=h.extend(!0,{},m.models.oRow,{src:c?"dom":"data",idx:e});f._aData=b;a.aoData.push(f);for(var g=a.aoColumns,j=0,i=g.length;j<i;j++)g[j].sType=null;a.aiDisplayMaster.push(e);b=a.rowIdFn(b);b!==k&&(a.aIds[b]=f);(c||!a.oFeatures.bDeferRender)&&Ga(a,e,c,d);return e}function ma(a,b){var c;b instanceof h||(b=h(b));return b.map(function(b,e){c=Ha(a,e);return M(a,
c.data,e,c.cells)})}function B(a,b,c,d){var e=a.iDraw,f=a.aoColumns[c],g=a.aoData[b]._aData,j=f.sDefaultContent,i=f.fnGetData(g,d,{settings:a,row:b,col:c});if(i===k)return a.iDrawError!=e&&null===j&&(J(a,0,"Requested unknown parameter "+("function"==typeof f.mData?"{function}":"'"+f.mData+"'")+" for row "+b+", column "+c,4),a.iDrawError=e),j;if((i===g||null===i)&&null!==j&&d!==k)i=j;else if("function"===typeof i)return i.call(g);return null===i&&"display"==d?"":i}function ib(a,b,c,d){a.aoColumns[c].fnSetData(a.aoData[b]._aData,
d,{settings:a,row:b,col:c})}function Ia(a){return h.map(a.match(/(\\.|[^\.])+/g)||[""],function(a){return a.replace(/\\\./g,".")})}function Q(a){if(h.isPlainObject(a)){var b={};h.each(a,function(a,c){c&&(b[a]=Q(c))});return function(a,c,f,g){var j=b[c]||b._;return j!==k?j(a,c,f,g):a}}if(null===a)return function(a){return a};if("function"===typeof a)return function(b,c,f,g){return a(b,c,f,g)};if("string"===typeof a&&(-1!==a.indexOf(".")||-1!==a.indexOf("[")||-1!==a.indexOf("("))){var c=function(a,
b,f){var g,j;if(""!==f){j=Ia(f);for(var i=0,n=j.length;i<n;i++){f=j[i].match(ba);g=j[i].match(U);if(f){j[i]=j[i].replace(ba,"");""!==j[i]&&(a=a[j[i]]);g=[];j.splice(0,i+1);j=j.join(".");if(h.isArray(a)){i=0;for(n=a.length;i<n;i++)g.push(c(a[i],b,j))}a=f[0].substring(1,f[0].length-1);a=""===a?g:g.join(a);break}else if(g){j[i]=j[i].replace(U,"");a=a[j[i]]();continue}if(null===a||a[j[i]]===k)return k;a=a[j[i]]}}return a};return function(b,e){return c(b,e,a)}}return function(b){return b[a]}}function R(a){if(h.isPlainObject(a))return R(a._);
if(null===a)return function(){};if("function"===typeof a)return function(b,d,e){a(b,"set",d,e)};if("string"===typeof a&&(-1!==a.indexOf(".")||-1!==a.indexOf("[")||-1!==a.indexOf("("))){var b=function(a,d,e){var e=Ia(e),f;f=e[e.length-1];for(var g,j,i=0,n=e.length-1;i<n;i++){g=e[i].match(ba);j=e[i].match(U);if(g){e[i]=e[i].replace(ba,"");a[e[i]]=[];f=e.slice();f.splice(0,i+1);g=f.join(".");if(h.isArray(d)){j=0;for(n=d.length;j<n;j++)f={},b(f,d[j],g),a[e[i]].push(f)}else a[e[i]]=d;return}j&&(e[i]=e[i].replace(U,
""),a=a[e[i]](d));if(null===a[e[i]]||a[e[i]]===k)a[e[i]]={};a=a[e[i]]}if(f.match(U))a[f.replace(U,"")](d);else a[f.replace(ba,"")]=d};return function(c,d){return b(c,d,a)}}return function(b,d){b[a]=d}}function Ja(a){return D(a.aoData,"_aData")}function na(a){a.aoData.length=0;a.aiDisplayMaster.length=0;a.aiDisplay.length=0;a.aIds={}}function oa(a,b,c){for(var d=-1,e=0,f=a.length;e<f;e++)a[e]==b?d=e:a[e]>b&&a[e]--; -1!=d&&c===k&&a.splice(d,1)}function ca(a,b,c,d){var e=a.aoData[b],f,g=function(c,d){for(;c.childNodes.length;)c.removeChild(c.firstChild);
c.innerHTML=B(a,b,d,"display")};if("dom"===c||(!c||"auto"===c)&&"dom"===e.src)e._aData=Ha(a,e,d,d===k?k:e._aData).data;else{var j=e.anCells;if(j)if(d!==k)g(j[d],d);else{c=0;for(f=j.length;c<f;c++)g(j[c],c)}}e._aSortData=null;e._aFilterData=null;g=a.aoColumns;if(d!==k)g[d].sType=null;else{c=0;for(f=g.length;c<f;c++)g[c].sType=null;Ka(a,e)}}function Ha(a,b,c,d){var e=[],f=b.firstChild,g,j,i=0,n,l=a.aoColumns,q=a._rowReadObject,d=d!==k?d:q?{}:[],t=function(a,b){if("string"===typeof a){var c=a.indexOf("@");
-1!==c&&(c=a.substring(c+1),R(a)(d,b.getAttribute(c)))}},m=function(a){if(c===k||c===i)j=l[i],n=h.trim(a.innerHTML),j&&j._bAttrSrc?(R(j.mData._)(d,n),t(j.mData.sort,a),t(j.mData.type,a),t(j.mData.filter,a)):q?(j._setter||(j._setter=R(j.mData)),j._setter(d,n)):d[i]=n;i++};if(f)for(;f;){g=f.nodeName.toUpperCase();if("TD"==g||"TH"==g)m(f),e.push(f);f=f.nextSibling}else{e=b.anCells;f=0;for(g=e.length;f<g;f++)m(e[f])}if(b=b.firstChild?b:b.nTr)(b=b.getAttribute("id"))&&R(a.rowId)(d,b);return{data:d,cells:e}}
function Ga(a,b,c,d){var e=a.aoData[b],f=e._aData,g=[],j,i,n,l,q;if(null===e.nTr){j=c||G.createElement("tr");e.nTr=j;e.anCells=g;j._DT_RowIndex=b;Ka(a,e);l=0;for(q=a.aoColumns.length;l<q;l++){n=a.aoColumns[l];i=c?d[l]:G.createElement(n.sCellType);i._DT_CellIndex={row:b,column:l};g.push(i);if((!c||n.mRender||n.mData!==l)&&(!h.isPlainObject(n.mData)||n.mData._!==l+".display"))i.innerHTML=B(a,b,l,"display");n.sClass&&(i.className+=" "+n.sClass);n.bVisible&&!c?j.appendChild(i):!n.bVisible&&c&&i.parentNode.removeChild(i);
n.fnCreatedCell&&n.fnCreatedCell.call(a.oInstance,i,B(a,b,l),f,b,l)}r(a,"aoRowCreatedCallback",null,[j,f,b])}e.nTr.setAttribute("role","row")}function Ka(a,b){var c=b.nTr,d=b._aData;if(c){var e=a.rowIdFn(d);e&&(c.id=e);d.DT_RowClass&&(e=d.DT_RowClass.split(" "),b.__rowc=b.__rowc?qa(b.__rowc.concat(e)):e,h(c).removeClass(b.__rowc.join(" ")).addClass(d.DT_RowClass));d.DT_RowAttr&&h(c).attr(d.DT_RowAttr);d.DT_RowData&&h(c).data(d.DT_RowData)}}function jb(a){var b,c,d,e,f,g=a.nTHead,j=a.nTFoot,i=0===
h("th, td",g).length,n=a.oClasses,l=a.aoColumns;i&&(e=h("<tr/>").appendTo(g));b=0;for(c=l.length;b<c;b++)f=l[b],d=h(f.nTh).addClass(f.sClass),i&&d.appendTo(e),a.oFeatures.bSort&&(d.addClass(f.sSortingClass),!1!==f.bSortable&&(d.attr("tabindex",a.iTabIndex).attr("aria-controls",a.sTableId),La(a,f.nTh,b))),f.sTitle!=d[0].innerHTML&&d.html(f.sTitle),Ma(a,"header")(a,d,f,n);i&&da(a.aoHeader,g);h(g).find(">tr").attr("role","row");h(g).find(">tr>th, >tr>td").addClass(n.sHeaderTH);h(j).find(">tr>th, >tr>td").addClass(n.sFooterTH);
if(null!==j){a=a.aoFooter[0];b=0;for(c=a.length;b<c;b++)f=l[b],f.nTf=a[b].cell,f.sClass&&h(f.nTf).addClass(f.sClass)}}function ea(a,b,c){var d,e,f,g=[],j=[],i=a.aoColumns.length,n;if(b){c===k&&(c=!1);d=0;for(e=b.length;d<e;d++){g[d]=b[d].slice();g[d].nTr=b[d].nTr;for(f=i-1;0<=f;f--)!a.aoColumns[f].bVisible&&!c&&g[d].splice(f,1);j.push([])}d=0;for(e=g.length;d<e;d++){if(a=g[d].nTr)for(;f=a.firstChild;)a.removeChild(f);f=0;for(b=g[d].length;f<b;f++)if(n=i=1,j[d][f]===k){a.appendChild(g[d][f].cell);
for(j[d][f]=1;g[d+i]!==k&&g[d][f].cell==g[d+i][f].cell;)j[d+i][f]=1,i++;for(;g[d][f+n]!==k&&g[d][f].cell==g[d][f+n].cell;){for(c=0;c<i;c++)j[d+c][f+n]=1;n++}h(g[d][f].cell).attr("rowspan",i).attr("colspan",n)}}}}function N(a){var b=r(a,"aoPreDrawCallback","preDraw",[a]);if(-1!==h.inArray(!1,b))C(a,!1);else{var b=[],c=0,d=a.asStripeClasses,e=d.length,f=a.oLanguage,g=a.iInitDisplayStart,j="ssp"==y(a),i=a.aiDisplay;a.bDrawing=!0;g!==k&&-1!==g&&(a._iDisplayStart=j?g:g>=a.fnRecordsDisplay()?0:g,a.iInitDisplayStart=
-1);var g=a._iDisplayStart,n=a.fnDisplayEnd();if(a.bDeferLoading)a.bDeferLoading=!1,a.iDraw++,C(a,!1);else if(j){if(!a.bDestroying&&!kb(a))return}else a.iDraw++;if(0!==i.length){f=j?a.aoData.length:n;for(j=j?0:g;j<f;j++){var l=i[j],q=a.aoData[l];null===q.nTr&&Ga(a,l);l=q.nTr;if(0!==e){var t=d[c%e];q._sRowStripe!=t&&(h(l).removeClass(q._sRowStripe).addClass(t),q._sRowStripe=t)}r(a,"aoRowCallback",null,[l,q._aData,c,j]);b.push(l);c++}}else c=f.sZeroRecords,1==a.iDraw&&"ajax"==y(a)?c=f.sLoadingRecords:
f.sEmptyTable&&0===a.fnRecordsTotal()&&(c=f.sEmptyTable),b[0]=h("<tr/>",{"class":e?d[0]:""}).append(h("<td />",{valign:"top",colSpan:aa(a),"class":a.oClasses.sRowEmpty}).html(c))[0];r(a,"aoHeaderCallback","header",[h(a.nTHead).children("tr")[0],Ja(a),g,n,i]);r(a,"aoFooterCallback","footer",[h(a.nTFoot).children("tr")[0],Ja(a),g,n,i]);d=h(a.nTBody);d.children().detach();d.append(h(b));r(a,"aoDrawCallback","draw",[a]);a.bSorted=!1;a.bFiltered=!1;a.bDrawing=!1}}function S(a,b){var c=a.oFeatures,d=c.bFilter;
c.bSort&&lb(a);d?fa(a,a.oPreviousSearch):a.aiDisplay=a.aiDisplayMaster.slice();!0!==b&&(a._iDisplayStart=0);a._drawHold=b;N(a);a._drawHold=!1}function mb(a){var b=a.oClasses,c=h(a.nTable),c=h("<div/>").insertBefore(c),d=a.oFeatures,e=h("<div/>",{id:a.sTableId+"_wrapper","class":b.sWrapper+(a.nTFoot?"":" "+b.sNoFooter)});a.nHolding=c[0];a.nTableWrapper=e[0];a.nTableReinsertBefore=a.nTable.nextSibling;for(var f=a.sDom.split(""),g,j,i,n,l,q,k=0;k<f.length;k++){g=null;j=f[k];if("<"==j){i=h("<div/>")[0];
n=f[k+1];if("'"==n||'"'==n){l="";for(q=2;f[k+q]!=n;)l+=f[k+q],q++;"H"==l?l=b.sJUIHeader:"F"==l&&(l=b.sJUIFooter);-1!=l.indexOf(".")?(n=l.split("."),i.id=n[0].substr(1,n[0].length-1),i.className=n[1]):"#"==l.charAt(0)?i.id=l.substr(1,l.length-1):i.className=l;k+=q}e.append(i);e=h(i)}else if(">"==j)e=e.parent();else if("l"==j&&d.bPaginate&&d.bLengthChange)g=nb(a);else if("f"==j&&d.bFilter)g=ob(a);else if("r"==j&&d.bProcessing)g=pb(a);else if("t"==j)g=qb(a);else if("i"==j&&d.bInfo)g=rb(a);else if("p"==
j&&d.bPaginate)g=sb(a);else if(0!==m.ext.feature.length){i=m.ext.feature;q=0;for(n=i.length;q<n;q++)if(j==i[q].cFeature){g=i[q].fnInit(a);break}}g&&(i=a.aanFeatures,i[j]||(i[j]=[]),i[j].push(g),e.append(g))}c.replaceWith(e);a.nHolding=null}function da(a,b){var c=h(b).children("tr"),d,e,f,g,j,i,n,l,q,k;a.splice(0,a.length);f=0;for(i=c.length;f<i;f++)a.push([]);f=0;for(i=c.length;f<i;f++){d=c[f];for(e=d.firstChild;e;){if("TD"==e.nodeName.toUpperCase()||"TH"==e.nodeName.toUpperCase()){l=1*e.getAttribute("colspan");
q=1*e.getAttribute("rowspan");l=!l||0===l||1===l?1:l;q=!q||0===q||1===q?1:q;g=0;for(j=a[f];j[g];)g++;n=g;k=1===l?!0:!1;for(j=0;j<l;j++)for(g=0;g<q;g++)a[f+g][n+j]={cell:e,unique:k},a[f+g].nTr=d}e=e.nextSibling}}}function ra(a,b,c){var d=[];c||(c=a.aoHeader,b&&(c=[],da(c,b)));for(var b=0,e=c.length;b<e;b++)for(var f=0,g=c[b].length;f<g;f++)if(c[b][f].unique&&(!d[f]||!a.bSortCellsTop))d[f]=c[b][f].cell;return d}function sa(a,b,c){r(a,"aoServerParams","serverParams",[b]);if(b&&h.isArray(b)){var d={},
e=/(.*?)\[\]$/;h.each(b,function(a,b){var c=b.name.match(e);c?(c=c[0],d[c]||(d[c]=[]),d[c].push(b.value)):d[b.name]=b.value});b=d}var f,g=a.ajax,j=a.oInstance,i=function(b){r(a,null,"xhr",[a,b,a.jqXHR]);c(b)};if(h.isPlainObject(g)&&g.data){f=g.data;var n=h.isFunction(f)?f(b,a):f,b=h.isFunction(f)&&n?n:h.extend(!0,b,n);delete g.data}n={data:b,success:function(b){var c=b.error||b.sError;c&&J(a,0,c);a.json=b;i(b)},dataType:"json",cache:!1,type:a.sServerMethod,error:function(b,c){var d=r(a,null,"xhr",
[a,null,a.jqXHR]);-1===h.inArray(!0,d)&&("parsererror"==c?J(a,0,"Invalid JSON response",1):4===b.readyState&&J(a,0,"Ajax error",7));C(a,!1)}};a.oAjaxData=b;r(a,null,"preXhr",[a,b]);a.fnServerData?a.fnServerData.call(j,a.sAjaxSource,h.map(b,function(a,b){return{name:b,value:a}}),i,a):a.sAjaxSource||"string"===typeof g?a.jqXHR=h.ajax(h.extend(n,{url:g||a.sAjaxSource})):h.isFunction(g)?a.jqXHR=g.call(j,b,i,a):(a.jqXHR=h.ajax(h.extend(n,g)),g.data=f)}function kb(a){return a.bAjaxDataGet?(a.iDraw++,C(a,
!0),sa(a,tb(a),function(b){ub(a,b)}),!1):!0}function tb(a){var b=a.aoColumns,c=b.length,d=a.oFeatures,e=a.oPreviousSearch,f=a.aoPreSearchCols,g,j=[],i,n,l,k=V(a);g=a._iDisplayStart;i=!1!==d.bPaginate?a._iDisplayLength:-1;var t=function(a,b){j.push({name:a,value:b})};t("sEcho",a.iDraw);t("iColumns",c);t("sColumns",D(b,"sName").join(","));t("iDisplayStart",g);t("iDisplayLength",i);var pa={draw:a.iDraw,columns:[],order:[],start:g,length:i,search:{value:e.sSearch,regex:e.bRegex}};for(g=0;g<c;g++)n=b[g],
l=f[g],i="function"==typeof n.mData?"function":n.mData,pa.columns.push({data:i,name:n.sName,searchable:n.bSearchable,orderable:n.bSortable,search:{value:l.sSearch,regex:l.bRegex}}),t("mDataProp_"+g,i),d.bFilter&&(t("sSearch_"+g,l.sSearch),t("bRegex_"+g,l.bRegex),t("bSearchable_"+g,n.bSearchable)),d.bSort&&t("bSortable_"+g,n.bSortable);d.bFilter&&(t("sSearch",e.sSearch),t("bRegex",e.bRegex));d.bSort&&(h.each(k,function(a,b){pa.order.push({column:b.col,dir:b.dir});t("iSortCol_"+a,b.col);t("sSortDir_"+
a,b.dir)}),t("iSortingCols",k.length));b=m.ext.legacy.ajax;return null===b?a.sAjaxSource?j:pa:b?j:pa}function ub(a,b){var c=ta(a,b),d=b.sEcho!==k?b.sEcho:b.draw,e=b.iTotalRecords!==k?b.iTotalRecords:b.recordsTotal,f=b.iTotalDisplayRecords!==k?b.iTotalDisplayRecords:b.recordsFiltered;if(d){if(1*d<a.iDraw)return;a.iDraw=1*d}na(a);a._iRecordsTotal=parseInt(e,10);a._iRecordsDisplay=parseInt(f,10);d=0;for(e=c.length;d<e;d++)M(a,c[d]);a.aiDisplay=a.aiDisplayMaster.slice();a.bAjaxDataGet=!1;N(a);a._bInitComplete||
ua(a,b);a.bAjaxDataGet=!0;C(a,!1)}function ta(a,b){var c=h.isPlainObject(a.ajax)&&a.ajax.dataSrc!==k?a.ajax.dataSrc:a.sAjaxDataProp;return"data"===c?b.aaData||b[c]:""!==c?Q(c)(b):b}function ob(a){var b=a.oClasses,c=a.sTableId,d=a.oLanguage,e=a.oPreviousSearch,f=a.aanFeatures,g='<input type="search" class="'+b.sFilterInput+'"/>',j=d.sSearch,j=j.match(/_INPUT_/)?j.replace("_INPUT_",g):j+g,b=h("<div/>",{id:!f.f?c+"_filter":null,"class":b.sFilter}).append(h("<label/>").append(j)),f=function(){var b=!this.value?
"":this.value;b!=e.sSearch&&(fa(a,{sSearch:b,bRegex:e.bRegex,bSmart:e.bSmart,bCaseInsensitive:e.bCaseInsensitive}),a._iDisplayStart=0,N(a))},g=null!==a.searchDelay?a.searchDelay:"ssp"===y(a)?400:0,i=h("input",b).val(e.sSearch).attr("placeholder",d.sSearchPlaceholder).on("keyup.DT search.DT input.DT paste.DT cut.DT",g?Na(f,g):f).on("keypress.DT",function(a){if(13==a.keyCode)return!1}).attr("aria-controls",c);h(a.nTable).on("search.dt.DT",function(b,c){if(a===c)try{i[0]!==G.activeElement&&i.val(e.sSearch)}catch(d){}});
return b[0]}function fa(a,b,c){var d=a.oPreviousSearch,e=a.aoPreSearchCols,f=function(a){d.sSearch=a.sSearch;d.bRegex=a.bRegex;d.bSmart=a.bSmart;d.bCaseInsensitive=a.bCaseInsensitive};Fa(a);if("ssp"!=y(a)){vb(a,b.sSearch,c,b.bEscapeRegex!==k?!b.bEscapeRegex:b.bRegex,b.bSmart,b.bCaseInsensitive);f(b);for(b=0;b<e.length;b++)wb(a,e[b].sSearch,b,e[b].bEscapeRegex!==k?!e[b].bEscapeRegex:e[b].bRegex,e[b].bSmart,e[b].bCaseInsensitive);xb(a)}else f(b);a.bFiltered=!0;r(a,null,"search",[a])}function xb(a){for(var b=
m.ext.search,c=a.aiDisplay,d,e,f=0,g=b.length;f<g;f++){for(var j=[],i=0,n=c.length;i<n;i++)e=c[i],d=a.aoData[e],b[f](a,d._aFilterData,e,d._aData,i)&&j.push(e);c.length=0;h.merge(c,j)}}function wb(a,b,c,d,e,f){if(""!==b){for(var g=[],j=a.aiDisplay,d=Oa(b,d,e,f),e=0;e<j.length;e++)b=a.aoData[j[e]]._aFilterData[c],d.test(b)&&g.push(j[e]);a.aiDisplay=g}}function vb(a,b,c,d,e,f){var d=Oa(b,d,e,f),f=a.oPreviousSearch.sSearch,g=a.aiDisplayMaster,j,e=[];0!==m.ext.search.length&&(c=!0);j=yb(a);if(0>=b.length)a.aiDisplay=
g.slice();else{if(j||c||f.length>b.length||0!==b.indexOf(f)||a.bSorted)a.aiDisplay=g.slice();b=a.aiDisplay;for(c=0;c<b.length;c++)d.test(a.aoData[b[c]]._sFilterRow)&&e.push(b[c]);a.aiDisplay=e}}function Oa(a,b,c,d){a=b?a:Pa(a);c&&(a="^(?=.*?"+h.map(a.match(/"[^"]+"|[^ ]+/g)||[""],function(a){if('"'===a.charAt(0))var b=a.match(/^"(.*)"$/),a=b?b[1]:a;return a.replace('"',"")}).join(")(?=.*?")+").*$");return RegExp(a,d?"i":"")}function yb(a){var b=a.aoColumns,c,d,e,f,g,j,i,h,l=m.ext.type.search;c=!1;
d=0;for(f=a.aoData.length;d<f;d++)if(h=a.aoData[d],!h._aFilterData){j=[];e=0;for(g=b.length;e<g;e++)c=b[e],c.bSearchable?(i=B(a,d,e,"filter"),l[c.sType]&&(i=l[c.sType](i)),null===i&&(i=""),"string"!==typeof i&&i.toString&&(i=i.toString())):i="",i.indexOf&&-1!==i.indexOf("&")&&(va.innerHTML=i,i=Wb?va.textContent:va.innerText),i.replace&&(i=i.replace(/[\r\n]/g,"")),j.push(i);h._aFilterData=j;h._sFilterRow=j.join("  ");c=!0}return c}function zb(a){return{search:a.sSearch,smart:a.bSmart,regex:a.bRegex,
caseInsensitive:a.bCaseInsensitive}}function Ab(a){return{sSearch:a.search,bSmart:a.smart,bRegex:a.regex,bCaseInsensitive:a.caseInsensitive}}function rb(a){var b=a.sTableId,c=a.aanFeatures.i,d=h("<div/>",{"class":a.oClasses.sInfo,id:!c?b+"_info":null});c||(a.aoDrawCallback.push({fn:Bb,sName:"information"}),d.attr("role","status").attr("aria-live","polite"),h(a.nTable).attr("aria-describedby",b+"_info"));return d[0]}function Bb(a){var b=a.aanFeatures.i;if(0!==b.length){var c=a.oLanguage,d=a._iDisplayStart+
1,e=a.fnDisplayEnd(),f=a.fnRecordsTotal(),g=a.fnRecordsDisplay(),j=g?c.sInfo:c.sInfoEmpty;g!==f&&(j+=" "+c.sInfoFiltered);j+=c.sInfoPostFix;j=Cb(a,j);c=c.fnInfoCallback;null!==c&&(j=c.call(a.oInstance,a,d,e,f,g,j));h(b).html(j)}}function Cb(a,b){var c=a.fnFormatNumber,d=a._iDisplayStart+1,e=a._iDisplayLength,f=a.fnRecordsDisplay(),g=-1===e;return b.replace(/_START_/g,c.call(a,d)).replace(/_END_/g,c.call(a,a.fnDisplayEnd())).replace(/_MAX_/g,c.call(a,a.fnRecordsTotal())).replace(/_TOTAL_/g,c.call(a,
f)).replace(/_PAGE_/g,c.call(a,g?1:Math.ceil(d/e))).replace(/_PAGES_/g,c.call(a,g?1:Math.ceil(f/e)))}function ga(a){var b,c,d=a.iInitDisplayStart,e=a.aoColumns,f;c=a.oFeatures;var g=a.bDeferLoading;if(a.bInitialised){mb(a);jb(a);ea(a,a.aoHeader);ea(a,a.aoFooter);C(a,!0);c.bAutoWidth&&Ea(a);b=0;for(c=e.length;b<c;b++)f=e[b],f.sWidth&&(f.nTh.style.width=v(f.sWidth));r(a,null,"preInit",[a]);S(a);e=y(a);if("ssp"!=e||g)"ajax"==e?sa(a,[],function(c){var f=ta(a,c);for(b=0;b<f.length;b++)M(a,f[b]);a.iInitDisplayStart=
d;S(a);C(a,!1);ua(a,c)},a):(C(a,!1),ua(a))}else setTimeout(function(){ga(a)},200)}function ua(a,b){a._bInitComplete=!0;(b||a.oInit.aaData)&&Y(a);r(a,null,"plugin-init",[a,b]);r(a,"aoInitComplete","init",[a,b])}function Qa(a,b){var c=parseInt(b,10);a._iDisplayLength=c;Ra(a);r(a,null,"length",[a,c])}function nb(a){for(var b=a.oClasses,c=a.sTableId,d=a.aLengthMenu,e=h.isArray(d[0]),f=e?d[0]:d,d=e?d[1]:d,e=h("<select/>",{name:c+"_length","aria-controls":c,"class":b.sLengthSelect}),g=0,j=f.length;g<j;g++)e[0][g]=
new Option("number"===typeof d[g]?a.fnFormatNumber(d[g]):d[g],f[g]);var i=h("<div><label/></div>").addClass(b.sLength);a.aanFeatures.l||(i[0].id=c+"_length");i.children().append(a.oLanguage.sLengthMenu.replace("_MENU_",e[0].outerHTML));h("select",i).val(a._iDisplayLength).on("change.DT",function(){Qa(a,h(this).val());N(a)});h(a.nTable).on("length.dt.DT",function(b,c,d){a===c&&h("select",i).val(d)});return i[0]}function sb(a){var b=a.sPaginationType,c=m.ext.pager[b],d="function"===typeof c,e=function(a){N(a)},
b=h("<div/>").addClass(a.oClasses.sPaging+b)[0],f=a.aanFeatures;d||c.fnInit(a,b,e);f.p||(b.id=a.sTableId+"_paginate",a.aoDrawCallback.push({fn:function(a){if(d){var b=a._iDisplayStart,i=a._iDisplayLength,h=a.fnRecordsDisplay(),l=-1===i,b=l?0:Math.ceil(b/i),i=l?1:Math.ceil(h/i),h=c(b,i),k,l=0;for(k=f.p.length;l<k;l++)Ma(a,"pageButton")(a,f.p[l],l,h,b,i)}else c.fnUpdate(a,e)},sName:"pagination"}));return b}function Sa(a,b,c){var d=a._iDisplayStart,e=a._iDisplayLength,f=a.fnRecordsDisplay();0===f||-1===
e?d=0:"number"===typeof b?(d=b*e,d>f&&(d=0)):"first"==b?d=0:"previous"==b?(d=0<=e?d-e:0,0>d&&(d=0)):"next"==b?d+e<f&&(d+=e):"last"==b?d=Math.floor((f-1)/e)*e:J(a,0,"Unknown paging action: "+b,5);b=a._iDisplayStart!==d;a._iDisplayStart=d;b&&(r(a,null,"page",[a]),c&&N(a));return b}function pb(a){return h("<div/>",{id:!a.aanFeatures.r?a.sTableId+"_processing":null,"class":a.oClasses.sProcessing}).html(a.oLanguage.sProcessing).insertBefore(a.nTable)[0]}function C(a,b){a.oFeatures.bProcessing&&h(a.aanFeatures.r).css("display",
b?"block":"none");r(a,null,"processing",[a,b])}function qb(a){var b=h(a.nTable);b.attr("role","grid");var c=a.oScroll;if(""===c.sX&&""===c.sY)return a.nTable;var d=c.sX,e=c.sY,f=a.oClasses,g=b.children("caption"),j=g.length?g[0]._captionSide:null,i=h(b[0].cloneNode(!1)),n=h(b[0].cloneNode(!1)),l=b.children("tfoot");l.length||(l=null);i=h("<div/>",{"class":f.sScrollWrapper}).append(h("<div/>",{"class":f.sScrollHead}).css({overflow:"hidden",position:"relative",border:0,width:d?!d?null:v(d):"100%"}).append(h("<div/>",
{"class":f.sScrollHeadInner}).css({"box-sizing":"content-box",width:c.sXInner||"100%"}).append(i.removeAttr("id").css("margin-left",0).append("top"===j?g:null).append(b.children("thead"))))).append(h("<div/>",{"class":f.sScrollBody}).css({position:"relative",overflow:"auto",width:!d?null:v(d)}).append(b));l&&i.append(h("<div/>",{"class":f.sScrollFoot}).css({overflow:"hidden",border:0,width:d?!d?null:v(d):"100%"}).append(h("<div/>",{"class":f.sScrollFootInner}).append(n.removeAttr("id").css("margin-left",
0).append("bottom"===j?g:null).append(b.children("tfoot")))));var b=i.children(),k=b[0],f=b[1],t=l?b[2]:null;if(d)h(f).on("scroll.DT",function(){var a=this.scrollLeft;k.scrollLeft=a;l&&(t.scrollLeft=a)});h(f).css(e&&c.bCollapse?"max-height":"height",e);a.nScrollHead=k;a.nScrollBody=f;a.nScrollFoot=t;a.aoDrawCallback.push({fn:ka,sName:"scrolling"});return i[0]}function ka(a){var b=a.oScroll,c=b.sX,d=b.sXInner,e=b.sY,b=b.iBarWidth,f=h(a.nScrollHead),g=f[0].style,j=f.children("div"),i=j[0].style,n=j.children("table"),
j=a.nScrollBody,l=h(j),q=j.style,t=h(a.nScrollFoot).children("div"),m=t.children("table"),o=h(a.nTHead),p=h(a.nTable),s=p[0],r=s.style,u=a.nTFoot?h(a.nTFoot):null,x=a.oBrowser,T=x.bScrollOversize,Xb=D(a.aoColumns,"nTh"),O,K,P,w,Ta=[],y=[],z=[],A=[],B,C=function(a){a=a.style;a.paddingTop="0";a.paddingBottom="0";a.borderTopWidth="0";a.borderBottomWidth="0";a.height=0};K=j.scrollHeight>j.clientHeight;if(a.scrollBarVis!==K&&a.scrollBarVis!==k)a.scrollBarVis=K,Y(a);else{a.scrollBarVis=K;p.children("thead, tfoot").remove();
u&&(P=u.clone().prependTo(p),O=u.find("tr"),P=P.find("tr"));w=o.clone().prependTo(p);o=o.find("tr");K=w.find("tr");w.find("th, td").removeAttr("tabindex");c||(q.width="100%",f[0].style.width="100%");h.each(ra(a,w),function(b,c){B=Z(a,b);c.style.width=a.aoColumns[B].sWidth});u&&H(function(a){a.style.width=""},P);f=p.outerWidth();if(""===c){r.width="100%";if(T&&(p.find("tbody").height()>j.offsetHeight||"scroll"==l.css("overflow-y")))r.width=v(p.outerWidth()-b);f=p.outerWidth()}else""!==d&&(r.width=
v(d),f=p.outerWidth());H(C,K);H(function(a){z.push(a.innerHTML);Ta.push(v(h(a).css("width")))},K);H(function(a,b){if(h.inArray(a,Xb)!==-1)a.style.width=Ta[b]},o);h(K).height(0);u&&(H(C,P),H(function(a){A.push(a.innerHTML);y.push(v(h(a).css("width")))},P),H(function(a,b){a.style.width=y[b]},O),h(P).height(0));H(function(a,b){a.innerHTML='<div class="dataTables_sizing" style="height:0;overflow:hidden;">'+z[b]+"</div>";a.style.width=Ta[b]},K);u&&H(function(a,b){a.innerHTML='<div class="dataTables_sizing" style="height:0;overflow:hidden;">'+
A[b]+"</div>";a.style.width=y[b]},P);if(p.outerWidth()<f){O=j.scrollHeight>j.offsetHeight||"scroll"==l.css("overflow-y")?f+b:f;if(T&&(j.scrollHeight>j.offsetHeight||"scroll"==l.css("overflow-y")))r.width=v(O-b);(""===c||""!==d)&&J(a,1,"Possible column misalignment",6)}else O="100%";q.width=v(O);g.width=v(O);u&&(a.nScrollFoot.style.width=v(O));!e&&T&&(q.height=v(s.offsetHeight+b));c=p.outerWidth();n[0].style.width=v(c);i.width=v(c);d=p.height()>j.clientHeight||"scroll"==l.css("overflow-y");e="padding"+
(x.bScrollbarLeft?"Left":"Right");i[e]=d?b+"px":"0px";u&&(m[0].style.width=v(c),t[0].style.width=v(c),t[0].style[e]=d?b+"px":"0px");p.children("colgroup").insertBefore(p.children("thead"));l.scroll();if((a.bSorted||a.bFiltered)&&!a._drawHold)j.scrollTop=0}}function H(a,b,c){for(var d=0,e=0,f=b.length,g,j;e<f;){g=b[e].firstChild;for(j=c?c[e].firstChild:null;g;)1===g.nodeType&&(c?a(g,j,d):a(g,d),d++),g=g.nextSibling,j=c?j.nextSibling:null;e++}}function Ea(a){var b=a.nTable,c=a.aoColumns,d=a.oScroll,
e=d.sY,f=d.sX,g=d.sXInner,j=c.length,i=la(a,"bVisible"),n=h("th",a.nTHead),l=b.getAttribute("width"),k=b.parentNode,t=!1,m,o,p=a.oBrowser,d=p.bScrollOversize;(m=b.style.width)&&-1!==m.indexOf("%")&&(l=m);for(m=0;m<i.length;m++)o=c[i[m]],null!==o.sWidth&&(o.sWidth=Db(o.sWidthOrig,k),t=!0);if(d||!t&&!f&&!e&&j==aa(a)&&j==n.length)for(m=0;m<j;m++)i=Z(a,m),null!==i&&(c[i].sWidth=v(n.eq(m).width()));else{j=h(b).clone().css("visibility","hidden").removeAttr("id");j.find("tbody tr").remove();var s=h("<tr/>").appendTo(j.find("tbody"));
j.find("thead, tfoot").remove();j.append(h(a.nTHead).clone()).append(h(a.nTFoot).clone());j.find("tfoot th, tfoot td").css("width","");n=ra(a,j.find("thead")[0]);for(m=0;m<i.length;m++)o=c[i[m]],n[m].style.width=null!==o.sWidthOrig&&""!==o.sWidthOrig?v(o.sWidthOrig):"",o.sWidthOrig&&f&&h(n[m]).append(h("<div/>").css({width:o.sWidthOrig,margin:0,padding:0,border:0,height:1}));if(a.aoData.length)for(m=0;m<i.length;m++)t=i[m],o=c[t],h(Eb(a,t)).clone(!1).append(o.sContentPadding).appendTo(s);h("[name]",
j).removeAttr("name");o=h("<div/>").css(f||e?{position:"absolute",top:0,left:0,height:1,right:0,overflow:"hidden"}:{}).append(j).appendTo(k);f&&g?j.width(g):f?(j.css("width","auto"),j.removeAttr("width"),j.width()<k.clientWidth&&l&&j.width(k.clientWidth)):e?j.width(k.clientWidth):l&&j.width(l);for(m=e=0;m<i.length;m++)k=h(n[m]),g=k.outerWidth()-k.width(),k=p.bBounding?Math.ceil(n[m].getBoundingClientRect().width):k.outerWidth(),e+=k,c[i[m]].sWidth=v(k-g);b.style.width=v(e);o.remove()}l&&(b.style.width=
v(l));if((l||f)&&!a._reszEvt)b=function(){h(E).on("resize.DT-"+a.sInstance,Na(function(){Y(a)}))},d?setTimeout(b,1E3):b(),a._reszEvt=!0}function Db(a,b){if(!a)return 0;var c=h("<div/>").css("width",v(a)).appendTo(b||G.body),d=c[0].offsetWidth;c.remove();return d}function Eb(a,b){var c=Fb(a,b);if(0>c)return null;var d=a.aoData[c];return!d.nTr?h("<td/>").html(B(a,c,b,"display"))[0]:d.anCells[b]}function Fb(a,b){for(var c,d=-1,e=-1,f=0,g=a.aoData.length;f<g;f++)c=B(a,f,b,"display")+"",c=c.replace(Yb,
""),c=c.replace(/&nbsp;/g," "),c.length>d&&(d=c.length,e=f);return e}function v(a){return null===a?"0px":"number"==typeof a?0>a?"0px":a+"px":a.match(/\d$/)?a+"px":a}function V(a){var b,c,d=[],e=a.aoColumns,f,g,j,i;b=a.aaSortingFixed;c=h.isPlainObject(b);var n=[];f=function(a){a.length&&!h.isArray(a[0])?n.push(a):h.merge(n,a)};h.isArray(b)&&f(b);c&&b.pre&&f(b.pre);f(a.aaSorting);c&&b.post&&f(b.post);for(a=0;a<n.length;a++){i=n[a][0];f=e[i].aDataSort;b=0;for(c=f.length;b<c;b++)g=f[b],j=e[g].sType||
"string",n[a]._idx===k&&(n[a]._idx=h.inArray(n[a][1],e[g].asSorting)),d.push({src:i,col:g,dir:n[a][1],index:n[a]._idx,type:j,formatter:m.ext.type.order[j+"-pre"]})}return d}function lb(a){var b,c,d=[],e=m.ext.type.order,f=a.aoData,g=0,j,i=a.aiDisplayMaster,h;Fa(a);h=V(a);b=0;for(c=h.length;b<c;b++)j=h[b],j.formatter&&g++,Gb(a,j.col);if("ssp"!=y(a)&&0!==h.length){b=0;for(c=i.length;b<c;b++)d[i[b]]=b;g===h.length?i.sort(function(a,b){var c,e,g,j,i=h.length,k=f[a]._aSortData,m=f[b]._aSortData;for(g=
0;g<i;g++)if(j=h[g],c=k[j.col],e=m[j.col],c=c<e?-1:c>e?1:0,0!==c)return"asc"===j.dir?c:-c;c=d[a];e=d[b];return c<e?-1:c>e?1:0}):i.sort(function(a,b){var c,g,j,i,k=h.length,m=f[a]._aSortData,o=f[b]._aSortData;for(j=0;j<k;j++)if(i=h[j],c=m[i.col],g=o[i.col],i=e[i.type+"-"+i.dir]||e["string-"+i.dir],c=i(c,g),0!==c)return c;c=d[a];g=d[b];return c<g?-1:c>g?1:0})}a.bSorted=!0}function Hb(a){for(var b,c,d=a.aoColumns,e=V(a),a=a.oLanguage.oAria,f=0,g=d.length;f<g;f++){c=d[f];var j=c.asSorting;b=c.sTitle.replace(/<.*?>/g,
"");var i=c.nTh;i.removeAttribute("aria-sort");c.bSortable&&(0<e.length&&e[0].col==f?(i.setAttribute("aria-sort","asc"==e[0].dir?"ascending":"descending"),c=j[e[0].index+1]||j[0]):c=j[0],b+="asc"===c?a.sSortAscending:a.sSortDescending);i.setAttribute("aria-label",b)}}function Ua(a,b,c,d){var e=a.aaSorting,f=a.aoColumns[b].asSorting,g=function(a,b){var c=a._idx;c===k&&(c=h.inArray(a[1],f));return c+1<f.length?c+1:b?null:0};"number"===typeof e[0]&&(e=a.aaSorting=[e]);c&&a.oFeatures.bSortMulti?(c=h.inArray(b,
D(e,"0")),-1!==c?(b=g(e[c],!0),null===b&&1===e.length&&(b=0),null===b?e.splice(c,1):(e[c][1]=f[b],e[c]._idx=b)):(e.push([b,f[0],0]),e[e.length-1]._idx=0)):e.length&&e[0][0]==b?(b=g(e[0]),e.length=1,e[0][1]=f[b],e[0]._idx=b):(e.length=0,e.push([b,f[0]]),e[0]._idx=0);S(a);"function"==typeof d&&d(a)}function La(a,b,c,d){var e=a.aoColumns[c];Va(b,{},function(b){!1!==e.bSortable&&(a.oFeatures.bProcessing?(C(a,!0),setTimeout(function(){Ua(a,c,b.shiftKey,d);"ssp"!==y(a)&&C(a,!1)},0)):Ua(a,c,b.shiftKey,d))})}
function wa(a){var b=a.aLastSort,c=a.oClasses.sSortColumn,d=V(a),e=a.oFeatures,f,g;if(e.bSort&&e.bSortClasses){e=0;for(f=b.length;e<f;e++)g=b[e].src,h(D(a.aoData,"anCells",g)).removeClass(c+(2>e?e+1:3));e=0;for(f=d.length;e<f;e++)g=d[e].src,h(D(a.aoData,"anCells",g)).addClass(c+(2>e?e+1:3))}a.aLastSort=d}function Gb(a,b){var c=a.aoColumns[b],d=m.ext.order[c.sSortDataType],e;d&&(e=d.call(a.oInstance,a,b,$(a,b)));for(var f,g=m.ext.type.order[c.sType+"-pre"],j=0,i=a.aoData.length;j<i;j++)if(c=a.aoData[j],
c._aSortData||(c._aSortData=[]),!c._aSortData[b]||d)f=d?e[j]:B(a,j,b,"sort"),c._aSortData[b]=g?g(f):f}function xa(a){if(a.oFeatures.bStateSave&&!a.bDestroying){var b={time:+new Date,start:a._iDisplayStart,length:a._iDisplayLength,order:h.extend(!0,[],a.aaSorting),search:zb(a.oPreviousSearch),columns:h.map(a.aoColumns,function(b,d){return{visible:b.bVisible,search:zb(a.aoPreSearchCols[d])}})};r(a,"aoStateSaveParams","stateSaveParams",[a,b]);a.oSavedState=b;a.fnStateSaveCallback.call(a.oInstance,a,
b)}}function Ib(a,b,c){var d,e,f=a.aoColumns,b=function(b){if(b&&b.time){var g=r(a,"aoStateLoadParams","stateLoadParams",[a,b]);if(-1===h.inArray(!1,g)&&(g=a.iStateDuration,!(0<g&&b.time<+new Date-1E3*g)&&!(b.columns&&f.length!==b.columns.length))){a.oLoadedState=h.extend(!0,{},b);b.start!==k&&(a._iDisplayStart=b.start,a.iInitDisplayStart=b.start);b.length!==k&&(a._iDisplayLength=b.length);b.order!==k&&(a.aaSorting=[],h.each(b.order,function(b,c){a.aaSorting.push(c[0]>=f.length?[0,c[1]]:c)}));b.search!==
k&&h.extend(a.oPreviousSearch,Ab(b.search));if(b.columns){d=0;for(e=b.columns.length;d<e;d++)g=b.columns[d],g.visible!==k&&(f[d].bVisible=g.visible),g.search!==k&&h.extend(a.aoPreSearchCols[d],Ab(g.search))}r(a,"aoStateLoaded","stateLoaded",[a,b])}}c()};if(a.oFeatures.bStateSave){var g=a.fnStateLoadCallback.call(a.oInstance,a,b);g!==k&&b(g)}else c()}function ya(a){var b=m.settings,a=h.inArray(a,D(b,"nTable"));return-1!==a?b[a]:null}function J(a,b,c,d){c="DataTables warning: "+(a?"table id="+a.sTableId+
" - ":"")+c;d&&(c+=". For more information about this error, please see http://datatables.net/tn/"+d);if(b)E.console&&console.log&&console.log(c);else if(b=m.ext,b=b.sErrMode||b.errMode,a&&r(a,null,"error",[a,d,c]),"alert"==b)alert(c);else{if("throw"==b)throw Error(c);"function"==typeof b&&b(a,d,c)}}function F(a,b,c,d){h.isArray(c)?h.each(c,function(c,d){h.isArray(d)?F(a,b,d[0],d[1]):F(a,b,d)}):(d===k&&(d=c),b[c]!==k&&(a[d]=b[c]))}function Jb(a,b,c){var d,e;for(e in b)b.hasOwnProperty(e)&&(d=b[e],
h.isPlainObject(d)?(h.isPlainObject(a[e])||(a[e]={}),h.extend(!0,a[e],d)):a[e]=c&&"data"!==e&&"aaData"!==e&&h.isArray(d)?d.slice():d);return a}function Va(a,b,c){h(a).on("click.DT",b,function(b){a.blur();c(b)}).on("keypress.DT",b,function(a){13===a.which&&(a.preventDefault(),c(a))}).on("selectstart.DT",function(){return!1})}function z(a,b,c,d){c&&a[b].push({fn:c,sName:d})}function r(a,b,c,d){var e=[];b&&(e=h.map(a[b].slice().reverse(),function(b){return b.fn.apply(a.oInstance,d)}));null!==c&&(b=h.Event(c+
".dt"),h(a.nTable).trigger(b,d),e.push(b.result));return e}function Ra(a){var b=a._iDisplayStart,c=a.fnDisplayEnd(),d=a._iDisplayLength;b>=c&&(b=c-d);b-=b%d;if(-1===d||0>b)b=0;a._iDisplayStart=b}function Ma(a,b){var c=a.renderer,d=m.ext.renderer[b];return h.isPlainObject(c)&&c[b]?d[c[b]]||d._:"string"===typeof c?d[c]||d._:d._}function y(a){return a.oFeatures.bServerSide?"ssp":a.ajax||a.sAjaxSource?"ajax":"dom"}function ha(a,b){var c=[],c=Kb.numbers_length,d=Math.floor(c/2);b<=c?c=W(0,b):a<=d?(c=W(0,
c-2),c.push("ellipsis"),c.push(b-1)):(a>=b-1-d?c=W(b-(c-2),b):(c=W(a-d+2,a+d-1),c.push("ellipsis"),c.push(b-1)),c.splice(0,0,"ellipsis"),c.splice(0,0,0));c.DT_el="span";return c}function cb(a){h.each({num:function(b){return za(b,a)},"num-fmt":function(b){return za(b,a,Wa)},"html-num":function(b){return za(b,a,Aa)},"html-num-fmt":function(b){return za(b,a,Aa,Wa)}},function(b,c){x.type.order[b+a+"-pre"]=c;b.match(/^html\-/)&&(x.type.search[b+a]=x.type.search.html)})}function Lb(a){return function(){var b=
[ya(this[m.ext.iApiIndex])].concat(Array.prototype.slice.call(arguments));return m.ext.internal[a].apply(this,b)}}var m=function(a){this.$=function(a,b){return this.api(!0).$(a,b)};this._=function(a,b){return this.api(!0).rows(a,b).data()};this.api=function(a){return a?new s(ya(this[x.iApiIndex])):new s(this)};this.fnAddData=function(a,b){var c=this.api(!0),d=h.isArray(a)&&(h.isArray(a[0])||h.isPlainObject(a[0]))?c.rows.add(a):c.row.add(a);(b===k||b)&&c.draw();return d.flatten().toArray()};this.fnAdjustColumnSizing=
function(a){var b=this.api(!0).columns.adjust(),c=b.settings()[0],d=c.oScroll;a===k||a?b.draw(!1):(""!==d.sX||""!==d.sY)&&ka(c)};this.fnClearTable=function(a){var b=this.api(!0).clear();(a===k||a)&&b.draw()};this.fnClose=function(a){this.api(!0).row(a).child.hide()};this.fnDeleteRow=function(a,b,c){var d=this.api(!0),a=d.rows(a),e=a.settings()[0],h=e.aoData[a[0][0]];a.remove();b&&b.call(this,e,h);(c===k||c)&&d.draw();return h};this.fnDestroy=function(a){this.api(!0).destroy(a)};this.fnDraw=function(a){this.api(!0).draw(a)};
this.fnFilter=function(a,b,c,d,e,h){e=this.api(!0);null===b||b===k?e.search(a,c,d,h):e.column(b).search(a,c,d,h);e.draw()};this.fnGetData=function(a,b){var c=this.api(!0);if(a!==k){var d=a.nodeName?a.nodeName.toLowerCase():"";return b!==k||"td"==d||"th"==d?c.cell(a,b).data():c.row(a).data()||null}return c.data().toArray()};this.fnGetNodes=function(a){var b=this.api(!0);return a!==k?b.row(a).node():b.rows().nodes().flatten().toArray()};this.fnGetPosition=function(a){var b=this.api(!0),c=a.nodeName.toUpperCase();
return"TR"==c?b.row(a).index():"TD"==c||"TH"==c?(a=b.cell(a).index(),[a.row,a.columnVisible,a.column]):null};this.fnIsOpen=function(a){return this.api(!0).row(a).child.isShown()};this.fnOpen=function(a,b,c){return this.api(!0).row(a).child(b,c).show().child()[0]};this.fnPageChange=function(a,b){var c=this.api(!0).page(a);(b===k||b)&&c.draw(!1)};this.fnSetColumnVis=function(a,b,c){a=this.api(!0).column(a).visible(b);(c===k||c)&&a.columns.adjust().draw()};this.fnSettings=function(){return ya(this[x.iApiIndex])};
this.fnSort=function(a){this.api(!0).order(a).draw()};this.fnSortListener=function(a,b,c){this.api(!0).order.listener(a,b,c)};this.fnUpdate=function(a,b,c,d,e){var h=this.api(!0);c===k||null===c?h.row(b).data(a):h.cell(b,c).data(a);(e===k||e)&&h.columns.adjust();(d===k||d)&&h.draw();return 0};this.fnVersionCheck=x.fnVersionCheck;var b=this,c=a===k,d=this.length;c&&(a={});this.oApi=this.internal=x.internal;for(var e in m.ext.internal)e&&(this[e]=Lb(e));this.each(function(){var e={},g=1<d?Jb(e,a,!0):
a,j=0,i,e=this.getAttribute("id"),n=!1,l=m.defaults,q=h(this);if("table"!=this.nodeName.toLowerCase())J(null,0,"Non-table node initialisation ("+this.nodeName+")",2);else{db(l);eb(l.column);I(l,l,!0);I(l.column,l.column,!0);I(l,h.extend(g,q.data()));var t=m.settings,j=0;for(i=t.length;j<i;j++){var o=t[j];if(o.nTable==this||o.nTHead.parentNode==this||o.nTFoot&&o.nTFoot.parentNode==this){var s=g.bRetrieve!==k?g.bRetrieve:l.bRetrieve;if(c||s)return o.oInstance;if(g.bDestroy!==k?g.bDestroy:l.bDestroy){o.oInstance.fnDestroy();
break}else{J(o,0,"Cannot reinitialise DataTable",3);return}}if(o.sTableId==this.id){t.splice(j,1);break}}if(null===e||""===e)this.id=e="DataTables_Table_"+m.ext._unique++;var p=h.extend(!0,{},m.models.oSettings,{sDestroyWidth:q[0].style.width,sInstance:e,sTableId:e});p.nTable=this;p.oApi=b.internal;p.oInit=g;t.push(p);p.oInstance=1===b.length?b:q.dataTable();db(g);g.oLanguage&&Ca(g.oLanguage);g.aLengthMenu&&!g.iDisplayLength&&(g.iDisplayLength=h.isArray(g.aLengthMenu[0])?g.aLengthMenu[0][0]:g.aLengthMenu[0]);
g=Jb(h.extend(!0,{},l),g);F(p.oFeatures,g,"bPaginate bLengthChange bFilter bSort bSortMulti bInfo bProcessing bAutoWidth bSortClasses bServerSide bDeferRender".split(" "));F(p,g,["asStripeClasses","ajax","fnServerData","fnFormatNumber","sServerMethod","aaSorting","aaSortingFixed","aLengthMenu","sPaginationType","sAjaxSource","sAjaxDataProp","iStateDuration","sDom","bSortCellsTop","iTabIndex","fnStateLoadCallback","fnStateSaveCallback","renderer","searchDelay","rowId",["iCookieDuration","iStateDuration"],
["oSearch","oPreviousSearch"],["aoSearchCols","aoPreSearchCols"],["iDisplayLength","_iDisplayLength"]]);F(p.oScroll,g,[["sScrollX","sX"],["sScrollXInner","sXInner"],["sScrollY","sY"],["bScrollCollapse","bCollapse"]]);F(p.oLanguage,g,"fnInfoCallback");z(p,"aoDrawCallback",g.fnDrawCallback,"user");z(p,"aoServerParams",g.fnServerParams,"user");z(p,"aoStateSaveParams",g.fnStateSaveParams,"user");z(p,"aoStateLoadParams",g.fnStateLoadParams,"user");z(p,"aoStateLoaded",g.fnStateLoaded,"user");z(p,"aoRowCallback",
g.fnRowCallback,"user");z(p,"aoRowCreatedCallback",g.fnCreatedRow,"user");z(p,"aoHeaderCallback",g.fnHeaderCallback,"user");z(p,"aoFooterCallback",g.fnFooterCallback,"user");z(p,"aoInitComplete",g.fnInitComplete,"user");z(p,"aoPreDrawCallback",g.fnPreDrawCallback,"user");p.rowIdFn=Q(g.rowId);fb(p);var u=p.oClasses;h.extend(u,m.ext.classes,g.oClasses);q.addClass(u.sTable);p.iInitDisplayStart===k&&(p.iInitDisplayStart=g.iDisplayStart,p._iDisplayStart=g.iDisplayStart);null!==g.iDeferLoading&&(p.bDeferLoading=
!0,e=h.isArray(g.iDeferLoading),p._iRecordsDisplay=e?g.iDeferLoading[0]:g.iDeferLoading,p._iRecordsTotal=e?g.iDeferLoading[1]:g.iDeferLoading);var v=p.oLanguage;h.extend(!0,v,g.oLanguage);v.sUrl&&(h.ajax({dataType:"json",url:v.sUrl,success:function(a){Ca(a);I(l.oLanguage,a);h.extend(true,v,a);ga(p)},error:function(){ga(p)}}),n=!0);null===g.asStripeClasses&&(p.asStripeClasses=[u.sStripeOdd,u.sStripeEven]);var e=p.asStripeClasses,x=q.children("tbody").find("tr").eq(0);-1!==h.inArray(!0,h.map(e,function(a){return x.hasClass(a)}))&&
(h("tbody tr",this).removeClass(e.join(" ")),p.asDestroyStripes=e.slice());e=[];t=this.getElementsByTagName("thead");0!==t.length&&(da(p.aoHeader,t[0]),e=ra(p));if(null===g.aoColumns){t=[];j=0;for(i=e.length;j<i;j++)t.push(null)}else t=g.aoColumns;j=0;for(i=t.length;j<i;j++)Da(p,e?e[j]:null);hb(p,g.aoColumnDefs,t,function(a,b){ja(p,a,b)});if(x.length){var w=function(a,b){return a.getAttribute("data-"+b)!==null?b:null};h(x[0]).children("th, td").each(function(a,b){var c=p.aoColumns[a];if(c.mData===
a){var d=w(b,"sort")||w(b,"order"),e=w(b,"filter")||w(b,"search");if(d!==null||e!==null){c.mData={_:a+".display",sort:d!==null?a+".@data-"+d:k,type:d!==null?a+".@data-"+d:k,filter:e!==null?a+".@data-"+e:k};ja(p,a)}}})}var T=p.oFeatures,e=function(){if(g.aaSorting===k){var a=p.aaSorting;j=0;for(i=a.length;j<i;j++)a[j][1]=p.aoColumns[j].asSorting[0]}wa(p);T.bSort&&z(p,"aoDrawCallback",function(){if(p.bSorted){var a=V(p),b={};h.each(a,function(a,c){b[c.src]=c.dir});r(p,null,"order",[p,a,b]);Hb(p)}});
z(p,"aoDrawCallback",function(){(p.bSorted||y(p)==="ssp"||T.bDeferRender)&&wa(p)},"sc");var a=q.children("caption").each(function(){this._captionSide=h(this).css("caption-side")}),b=q.children("thead");b.length===0&&(b=h("<thead/>").appendTo(q));p.nTHead=b[0];b=q.children("tbody");b.length===0&&(b=h("<tbody/>").appendTo(q));p.nTBody=b[0];b=q.children("tfoot");if(b.length===0&&a.length>0&&(p.oScroll.sX!==""||p.oScroll.sY!==""))b=h("<tfoot/>").appendTo(q);if(b.length===0||b.children().length===0)q.addClass(u.sNoFooter);
else if(b.length>0){p.nTFoot=b[0];da(p.aoFooter,p.nTFoot)}if(g.aaData)for(j=0;j<g.aaData.length;j++)M(p,g.aaData[j]);else(p.bDeferLoading||y(p)=="dom")&&ma(p,h(p.nTBody).children("tr"));p.aiDisplay=p.aiDisplayMaster.slice();p.bInitialised=true;n===false&&ga(p)};g.bStateSave?(T.bStateSave=!0,z(p,"aoDrawCallback",xa,"state_save"),Ib(p,g,e)):e()}});b=null;return this},x,s,o,u,Xa={},Mb=/[\r\n]/g,Aa=/<.*?>/g,Zb=/^\d{2,4}[\.\/\-]\d{1,2}[\.\/\-]\d{1,2}([T ]{1}\d{1,2}[:\.]\d{2}([\.:]\d{2})?)?$/,$b=RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\|\\$|\\^|\\-)",
"g"),Wa=/[',$£€¥%\u2009\u202F\u20BD\u20a9\u20BArfk]/gi,L=function(a){return!a||!0===a||"-"===a?!0:!1},Nb=function(a){var b=parseInt(a,10);return!isNaN(b)&&isFinite(a)?b:null},Ob=function(a,b){Xa[b]||(Xa[b]=RegExp(Pa(b),"g"));return"string"===typeof a&&"."!==b?a.replace(/\./g,"").replace(Xa[b],"."):a},Ya=function(a,b,c){var d="string"===typeof a;if(L(a))return!0;b&&d&&(a=Ob(a,b));c&&d&&(a=a.replace(Wa,""));return!isNaN(parseFloat(a))&&isFinite(a)},Pb=function(a,b,c){return L(a)?!0:!(L(a)||"string"===
typeof a)?null:Ya(a.replace(Aa,""),b,c)?!0:null},D=function(a,b,c){var d=[],e=0,f=a.length;if(c!==k)for(;e<f;e++)a[e]&&a[e][b]&&d.push(a[e][b][c]);else for(;e<f;e++)a[e]&&d.push(a[e][b]);return d},ia=function(a,b,c,d){var e=[],f=0,g=b.length;if(d!==k)for(;f<g;f++)a[b[f]][c]&&e.push(a[b[f]][c][d]);else for(;f<g;f++)e.push(a[b[f]][c]);return e},W=function(a,b){var c=[],d;b===k?(b=0,d=a):(d=b,b=a);for(var e=b;e<d;e++)c.push(e);return c},Qb=function(a){for(var b=[],c=0,d=a.length;c<d;c++)a[c]&&b.push(a[c]);
return b},qa=function(a){var b;a:{if(!(2>a.length)){b=a.slice().sort();for(var c=b[0],d=1,e=b.length;d<e;d++){if(b[d]===c){b=!1;break a}c=b[d]}}b=!0}if(b)return a.slice();b=[];var e=a.length,f,g=0,d=0;a:for(;d<e;d++){c=a[d];for(f=0;f<g;f++)if(b[f]===c)continue a;b.push(c);g++}return b};m.util={throttle:function(a,b){var c=b!==k?b:200,d,e;return function(){var b=this,g=+new Date,j=arguments;d&&g<d+c?(clearTimeout(e),e=setTimeout(function(){d=k;a.apply(b,j)},c)):(d=g,a.apply(b,j))}},escapeRegex:function(a){return a.replace($b,
"\\$1")}};var A=function(a,b,c){a[b]!==k&&(a[c]=a[b])},ba=/\[.*?\]$/,U=/\(\)$/,Pa=m.util.escapeRegex,va=h("<div>")[0],Wb=va.textContent!==k,Yb=/<.*?>/g,Na=m.util.throttle,Rb=[],w=Array.prototype,ac=function(a){var b,c,d=m.settings,e=h.map(d,function(a){return a.nTable});if(a){if(a.nTable&&a.oApi)return[a];if(a.nodeName&&"table"===a.nodeName.toLowerCase())return b=h.inArray(a,e),-1!==b?[d[b]]:null;if(a&&"function"===typeof a.settings)return a.settings().toArray();"string"===typeof a?c=h(a):a instanceof
h&&(c=a)}else return[];if(c)return c.map(function(){b=h.inArray(this,e);return-1!==b?d[b]:null}).toArray()};s=function(a,b){if(!(this instanceof s))return new s(a,b);var c=[],d=function(a){(a=ac(a))&&(c=c.concat(a))};if(h.isArray(a))for(var e=0,f=a.length;e<f;e++)d(a[e]);else d(a);this.context=qa(c);b&&h.merge(this,b);this.selector={rows:null,cols:null,opts:null};s.extend(this,this,Rb)};m.Api=s;h.extend(s.prototype,{any:function(){return 0!==this.count()},concat:w.concat,context:[],count:function(){return this.flatten().length},
each:function(a){for(var b=0,c=this.length;b<c;b++)a.call(this,this[b],b,this);return this},eq:function(a){var b=this.context;return b.length>a?new s(b[a],this[a]):null},filter:function(a){var b=[];if(w.filter)b=w.filter.call(this,a,this);else for(var c=0,d=this.length;c<d;c++)a.call(this,this[c],c,this)&&b.push(this[c]);return new s(this.context,b)},flatten:function(){var a=[];return new s(this.context,a.concat.apply(a,this.toArray()))},join:w.join,indexOf:w.indexOf||function(a,b){for(var c=b||0,
d=this.length;c<d;c++)if(this[c]===a)return c;return-1},iterator:function(a,b,c,d){var e=[],f,g,j,h,n,l=this.context,m,o,u=this.selector;"string"===typeof a&&(d=c,c=b,b=a,a=!1);g=0;for(j=l.length;g<j;g++){var r=new s(l[g]);if("table"===b)f=c.call(r,l[g],g),f!==k&&e.push(f);else if("columns"===b||"rows"===b)f=c.call(r,l[g],this[g],g),f!==k&&e.push(f);else if("column"===b||"column-rows"===b||"row"===b||"cell"===b){o=this[g];"column-rows"===b&&(m=Ba(l[g],u.opts));h=0;for(n=o.length;h<n;h++)f=o[h],f=
"cell"===b?c.call(r,l[g],f.row,f.column,g,h):c.call(r,l[g],f,g,h,m),f!==k&&e.push(f)}}return e.length||d?(a=new s(l,a?e.concat.apply([],e):e),b=a.selector,b.rows=u.rows,b.cols=u.cols,b.opts=u.opts,a):this},lastIndexOf:w.lastIndexOf||function(a,b){return this.indexOf.apply(this.toArray.reverse(),arguments)},length:0,map:function(a){var b=[];if(w.map)b=w.map.call(this,a,this);else for(var c=0,d=this.length;c<d;c++)b.push(a.call(this,this[c],c));return new s(this.context,b)},pluck:function(a){return this.map(function(b){return b[a]})},
pop:w.pop,push:w.push,reduce:w.reduce||function(a,b){return gb(this,a,b,0,this.length,1)},reduceRight:w.reduceRight||function(a,b){return gb(this,a,b,this.length-1,-1,-1)},reverse:w.reverse,selector:null,shift:w.shift,slice:function(){return new s(this.context,this)},sort:w.sort,splice:w.splice,toArray:function(){return w.slice.call(this)},to$:function(){return h(this)},toJQuery:function(){return h(this)},unique:function(){return new s(this.context,qa(this))},unshift:w.unshift});s.extend=function(a,
b,c){if(c.length&&b&&(b instanceof s||b.__dt_wrapper)){var d,e,f,g=function(a,b,c){return function(){var d=b.apply(a,arguments);s.extend(d,d,c.methodExt);return d}};d=0;for(e=c.length;d<e;d++)f=c[d],b[f.name]="function"===typeof f.val?g(a,f.val,f):h.isPlainObject(f.val)?{}:f.val,b[f.name].__dt_wrapper=!0,s.extend(a,b[f.name],f.propExt)}};s.register=o=function(a,b){if(h.isArray(a))for(var c=0,d=a.length;c<d;c++)s.register(a[c],b);else for(var e=a.split("."),f=Rb,g,j,c=0,d=e.length;c<d;c++){g=(j=-1!==
e[c].indexOf("()"))?e[c].replace("()",""):e[c];var i;a:{i=0;for(var n=f.length;i<n;i++)if(f[i].name===g){i=f[i];break a}i=null}i||(i={name:g,val:{},methodExt:[],propExt:[]},f.push(i));c===d-1?i.val=b:f=j?i.methodExt:i.propExt}};s.registerPlural=u=function(a,b,c){s.register(a,c);s.register(b,function(){var a=c.apply(this,arguments);return a===this?this:a instanceof s?a.length?h.isArray(a[0])?new s(a.context,a[0]):a[0]:k:a})};o("tables()",function(a){var b;if(a){b=s;var c=this.context;if("number"===
typeof a)a=[c[a]];else var d=h.map(c,function(a){return a.nTable}),a=h(d).filter(a).map(function(){var a=h.inArray(this,d);return c[a]}).toArray();b=new b(a)}else b=this;return b});o("table()",function(a){var a=this.tables(a),b=a.context;return b.length?new s(b[0]):a});u("tables().nodes()","table().node()",function(){return this.iterator("table",function(a){return a.nTable},1)});u("tables().body()","table().body()",function(){return this.iterator("table",function(a){return a.nTBody},1)});u("tables().header()",
"table().header()",function(){return this.iterator("table",function(a){return a.nTHead},1)});u("tables().footer()","table().footer()",function(){return this.iterator("table",function(a){return a.nTFoot},1)});u("tables().containers()","table().container()",function(){return this.iterator("table",function(a){return a.nTableWrapper},1)});o("draw()",function(a){return this.iterator("table",function(b){"page"===a?N(b):("string"===typeof a&&(a="full-hold"===a?!1:!0),S(b,!1===a))})});o("page()",function(a){return a===
k?this.page.info().page:this.iterator("table",function(b){Sa(b,a)})});o("page.info()",function(){if(0===this.context.length)return k;var a=this.context[0],b=a._iDisplayStart,c=a.oFeatures.bPaginate?a._iDisplayLength:-1,d=a.fnRecordsDisplay(),e=-1===c;return{page:e?0:Math.floor(b/c),pages:e?1:Math.ceil(d/c),start:b,end:a.fnDisplayEnd(),length:c,recordsTotal:a.fnRecordsTotal(),recordsDisplay:d,serverSide:"ssp"===y(a)}});o("page.len()",function(a){return a===k?0!==this.context.length?this.context[0]._iDisplayLength:
k:this.iterator("table",function(b){Qa(b,a)})});var Sb=function(a,b,c){if(c){var d=new s(a);d.one("draw",function(){c(d.ajax.json())})}if("ssp"==y(a))S(a,b);else{C(a,!0);var e=a.jqXHR;e&&4!==e.readyState&&e.abort();sa(a,[],function(c){na(a);for(var c=ta(a,c),d=0,e=c.length;d<e;d++)M(a,c[d]);S(a,b);C(a,!1)})}};o("ajax.json()",function(){var a=this.context;if(0<a.length)return a[0].json});o("ajax.params()",function(){var a=this.context;if(0<a.length)return a[0].oAjaxData});o("ajax.reload()",function(a,
b){return this.iterator("table",function(c){Sb(c,!1===b,a)})});o("ajax.url()",function(a){var b=this.context;if(a===k){if(0===b.length)return k;b=b[0];return b.ajax?h.isPlainObject(b.ajax)?b.ajax.url:b.ajax:b.sAjaxSource}return this.iterator("table",function(b){h.isPlainObject(b.ajax)?b.ajax.url=a:b.ajax=a})});o("ajax.url().load()",function(a,b){return this.iterator("table",function(c){Sb(c,!1===b,a)})});var Za=function(a,b,c,d,e){var f=[],g,j,i,n,l,m;i=typeof b;if(!b||"string"===i||"function"===
i||b.length===k)b=[b];i=0;for(n=b.length;i<n;i++){j=b[i]&&b[i].split&&!b[i].match(/[\[\(:]/)?b[i].split(","):[b[i]];l=0;for(m=j.length;l<m;l++)(g=c("string"===typeof j[l]?h.trim(j[l]):j[l]))&&g.length&&(f=f.concat(g))}a=x.selector[a];if(a.length){i=0;for(n=a.length;i<n;i++)f=a[i](d,e,f)}return qa(f)},$a=function(a){a||(a={});a.filter&&a.search===k&&(a.search=a.filter);return h.extend({search:"none",order:"current",page:"all"},a)},ab=function(a){for(var b=0,c=a.length;b<c;b++)if(0<a[b].length)return a[0]=
a[b],a[0].length=1,a.length=1,a.context=[a.context[b]],a;a.length=0;return a},Ba=function(a,b){var c,d,e,f=[],g=a.aiDisplay;c=a.aiDisplayMaster;var j=b.search;d=b.order;e=b.page;if("ssp"==y(a))return"removed"===j?[]:W(0,c.length);if("current"==e){c=a._iDisplayStart;for(d=a.fnDisplayEnd();c<d;c++)f.push(g[c])}else if("current"==d||"applied"==d)f="none"==j?c.slice():"applied"==j?g.slice():h.map(c,function(a){return-1===h.inArray(a,g)?a:null});else if("index"==d||"original"==d){c=0;for(d=a.aoData.length;c<
d;c++)"none"==j?f.push(c):(e=h.inArray(c,g),(-1===e&&"removed"==j||0<=e&&"applied"==j)&&f.push(c))}return f};o("rows()",function(a,b){a===k?a="":h.isPlainObject(a)&&(b=a,a="");var b=$a(b),c=this.iterator("table",function(c){var e=b,f;return Za("row",a,function(a){var b=Nb(a);if(b!==null&&!e)return[b];f||(f=Ba(c,e));if(b!==null&&h.inArray(b,f)!==-1)return[b];if(a===null||a===k||a==="")return f;if(typeof a==="function")return h.map(f,function(b){var e=c.aoData[b];return a(b,e._aData,e.nTr)?b:null});
b=Qb(ia(c.aoData,f,"nTr"));if(a.nodeName){if(a._DT_RowIndex!==k)return[a._DT_RowIndex];if(a._DT_CellIndex)return[a._DT_CellIndex.row];b=h(a).closest("*[data-dt-row]");return b.length?[b.data("dt-row")]:[]}if(typeof a==="string"&&a.charAt(0)==="#"){var i=c.aIds[a.replace(/^#/,"")];if(i!==k)return[i.idx]}return h(b).filter(a).map(function(){return this._DT_RowIndex}).toArray()},c,e)},1);c.selector.rows=a;c.selector.opts=b;return c});o("rows().nodes()",function(){return this.iterator("row",function(a,
b){return a.aoData[b].nTr||k},1)});o("rows().data()",function(){return this.iterator(!0,"rows",function(a,b){return ia(a.aoData,b,"_aData")},1)});u("rows().cache()","row().cache()",function(a){return this.iterator("row",function(b,c){var d=b.aoData[c];return"search"===a?d._aFilterData:d._aSortData},1)});u("rows().invalidate()","row().invalidate()",function(a){return this.iterator("row",function(b,c){ca(b,c,a)})});u("rows().indexes()","row().index()",function(){return this.iterator("row",function(a,
b){return b},1)});u("rows().ids()","row().id()",function(a){for(var b=[],c=this.context,d=0,e=c.length;d<e;d++)for(var f=0,g=this[d].length;f<g;f++){var h=c[d].rowIdFn(c[d].aoData[this[d][f]]._aData);b.push((!0===a?"#":"")+h)}return new s(c,b)});u("rows().remove()","row().remove()",function(){var a=this;this.iterator("row",function(b,c,d){var e=b.aoData,f=e[c],g,h,i,n,l;e.splice(c,1);g=0;for(h=e.length;g<h;g++)if(i=e[g],l=i.anCells,null!==i.nTr&&(i.nTr._DT_RowIndex=g),null!==l){i=0;for(n=l.length;i<
n;i++)l[i]._DT_CellIndex.row=g}oa(b.aiDisplayMaster,c);oa(b.aiDisplay,c);oa(a[d],c,!1);0<b._iRecordsDisplay&&b._iRecordsDisplay--;Ra(b);c=b.rowIdFn(f._aData);c!==k&&delete b.aIds[c]});this.iterator("table",function(a){for(var c=0,d=a.aoData.length;c<d;c++)a.aoData[c].idx=c});return this});o("rows.add()",function(a){var b=this.iterator("table",function(b){var c,f,g,h=[];f=0;for(g=a.length;f<g;f++)c=a[f],c.nodeName&&"TR"===c.nodeName.toUpperCase()?h.push(ma(b,c)[0]):h.push(M(b,c));return h},1),c=this.rows(-1);
c.pop();h.merge(c,b);return c});o("row()",function(a,b){return ab(this.rows(a,b))});o("row().data()",function(a){var b=this.context;if(a===k)return b.length&&this.length?b[0].aoData[this[0]]._aData:k;b[0].aoData[this[0]]._aData=a;ca(b[0],this[0],"data");return this});o("row().node()",function(){var a=this.context;return a.length&&this.length?a[0].aoData[this[0]].nTr||null:null});o("row.add()",function(a){a instanceof h&&a.length&&(a=a[0]);var b=this.iterator("table",function(b){return a.nodeName&&
"TR"===a.nodeName.toUpperCase()?ma(b,a)[0]:M(b,a)});return this.row(b[0])});var bb=function(a,b){var c=a.context;if(c.length&&(c=c[0].aoData[b!==k?b:a[0]])&&c._details)c._details.remove(),c._detailsShow=k,c._details=k},Tb=function(a,b){var c=a.context;if(c.length&&a.length){var d=c[0].aoData[a[0]];if(d._details){(d._detailsShow=b)?d._details.insertAfter(d.nTr):d._details.detach();var e=c[0],f=new s(e),g=e.aoData;f.off("draw.dt.DT_details column-visibility.dt.DT_details destroy.dt.DT_details");0<D(g,
"_details").length&&(f.on("draw.dt.DT_details",function(a,b){e===b&&f.rows({page:"current"}).eq(0).each(function(a){a=g[a];a._detailsShow&&a._details.insertAfter(a.nTr)})}),f.on("column-visibility.dt.DT_details",function(a,b){if(e===b)for(var c,d=aa(b),f=0,h=g.length;f<h;f++)c=g[f],c._details&&c._details.children("td[colspan]").attr("colspan",d)}),f.on("destroy.dt.DT_details",function(a,b){if(e===b)for(var c=0,d=g.length;c<d;c++)g[c]._details&&bb(f,c)}))}}};o("row().child()",function(a,b){var c=this.context;
if(a===k)return c.length&&this.length?c[0].aoData[this[0]]._details:k;if(!0===a)this.child.show();else if(!1===a)bb(this);else if(c.length&&this.length){var d=c[0],c=c[0].aoData[this[0]],e=[],f=function(a,b){if(h.isArray(a)||a instanceof h)for(var c=0,k=a.length;c<k;c++)f(a[c],b);else a.nodeName&&"tr"===a.nodeName.toLowerCase()?e.push(a):(c=h("<tr><td/></tr>").addClass(b),h("td",c).addClass(b).html(a)[0].colSpan=aa(d),e.push(c[0]))};f(a,b);c._details&&c._details.detach();c._details=h(e);c._detailsShow&&
c._details.insertAfter(c.nTr)}return this});o(["row().child.show()","row().child().show()"],function(){Tb(this,!0);return this});o(["row().child.hide()","row().child().hide()"],function(){Tb(this,!1);return this});o(["row().child.remove()","row().child().remove()"],function(){bb(this);return this});o("row().child.isShown()",function(){var a=this.context;return a.length&&this.length?a[0].aoData[this[0]]._detailsShow||!1:!1});var bc=/^([^:]+):(name|visIdx|visible)$/,Ub=function(a,b,c,d,e){for(var c=
[],d=0,f=e.length;d<f;d++)c.push(B(a,e[d],b));return c};o("columns()",function(a,b){a===k?a="":h.isPlainObject(a)&&(b=a,a="");var b=$a(b),c=this.iterator("table",function(c){var e=a,f=b,g=c.aoColumns,j=D(g,"sName"),i=D(g,"nTh");return Za("column",e,function(a){var b=Nb(a);if(a==="")return W(g.length);if(b!==null)return[b>=0?b:g.length+b];if(typeof a==="function"){var e=Ba(c,f);return h.map(g,function(b,f){return a(f,Ub(c,f,0,0,e),i[f])?f:null})}var k=typeof a==="string"?a.match(bc):"";if(k)switch(k[2]){case "visIdx":case "visible":b=
parseInt(k[1],10);if(b<0){var m=h.map(g,function(a,b){return a.bVisible?b:null});return[m[m.length+b]]}return[Z(c,b)];case "name":return h.map(j,function(a,b){return a===k[1]?b:null});default:return[]}if(a.nodeName&&a._DT_CellIndex)return[a._DT_CellIndex.column];b=h(i).filter(a).map(function(){return h.inArray(this,i)}).toArray();if(b.length||!a.nodeName)return b;b=h(a).closest("*[data-dt-column]");return b.length?[b.data("dt-column")]:[]},c,f)},1);c.selector.cols=a;c.selector.opts=b;return c});u("columns().header()",
"column().header()",function(){return this.iterator("column",function(a,b){return a.aoColumns[b].nTh},1)});u("columns().footer()","column().footer()",function(){return this.iterator("column",function(a,b){return a.aoColumns[b].nTf},1)});u("columns().data()","column().data()",function(){return this.iterator("column-rows",Ub,1)});u("columns().dataSrc()","column().dataSrc()",function(){return this.iterator("column",function(a,b){return a.aoColumns[b].mData},1)});u("columns().cache()","column().cache()",
function(a){return this.iterator("column-rows",function(b,c,d,e,f){return ia(b.aoData,f,"search"===a?"_aFilterData":"_aSortData",c)},1)});u("columns().nodes()","column().nodes()",function(){return this.iterator("column-rows",function(a,b,c,d,e){return ia(a.aoData,e,"anCells",b)},1)});u("columns().visible()","column().visible()",function(a,b){var c=this.iterator("column",function(b,c){if(a===k)return b.aoColumns[c].bVisible;var f=b.aoColumns,g=f[c],j=b.aoData,i,n,l;if(a!==k&&g.bVisible!==a){if(a){var m=
h.inArray(!0,D(f,"bVisible"),c+1);i=0;for(n=j.length;i<n;i++)l=j[i].nTr,f=j[i].anCells,l&&l.insertBefore(f[c],f[m]||null)}else h(D(b.aoData,"anCells",c)).detach();g.bVisible=a;ea(b,b.aoHeader);ea(b,b.aoFooter);xa(b)}});a!==k&&(this.iterator("column",function(c,e){r(c,null,"column-visibility",[c,e,a,b])}),(b===k||b)&&this.columns.adjust());return c});u("columns().indexes()","column().index()",function(a){return this.iterator("column",function(b,c){return"visible"===a?$(b,c):c},1)});o("columns.adjust()",
function(){return this.iterator("table",function(a){Y(a)},1)});o("column.index()",function(a,b){if(0!==this.context.length){var c=this.context[0];if("fromVisible"===a||"toData"===a)return Z(c,b);if("fromData"===a||"toVisible"===a)return $(c,b)}});o("column()",function(a,b){return ab(this.columns(a,b))});o("cells()",function(a,b,c){h.isPlainObject(a)&&(a.row===k?(c=a,a=null):(c=b,b=null));h.isPlainObject(b)&&(c=b,b=null);if(null===b||b===k)return this.iterator("table",function(b){var d=a,e=$a(c),f=
b.aoData,g=Ba(b,e),j=Qb(ia(f,g,"anCells")),i=h([].concat.apply([],j)),l,n=b.aoColumns.length,m,o,u,s,r,v;return Za("cell",d,function(a){var c=typeof a==="function";if(a===null||a===k||c){m=[];o=0;for(u=g.length;o<u;o++){l=g[o];for(s=0;s<n;s++){r={row:l,column:s};if(c){v=f[l];a(r,B(b,l,s),v.anCells?v.anCells[s]:null)&&m.push(r)}else m.push(r)}}return m}if(h.isPlainObject(a))return[a];c=i.filter(a).map(function(a,b){return{row:b._DT_CellIndex.row,column:b._DT_CellIndex.column}}).toArray();if(c.length||
!a.nodeName)return c;v=h(a).closest("*[data-dt-row]");return v.length?[{row:v.data("dt-row"),column:v.data("dt-column")}]:[]},b,e)});var d=this.columns(b,c),e=this.rows(a,c),f,g,j,i,n,l=this.iterator("table",function(a,b){f=[];g=0;for(j=e[b].length;g<j;g++){i=0;for(n=d[b].length;i<n;i++)f.push({row:e[b][g],column:d[b][i]})}return f},1);h.extend(l.selector,{cols:b,rows:a,opts:c});return l});u("cells().nodes()","cell().node()",function(){return this.iterator("cell",function(a,b,c){return(a=a.aoData[b])&&
a.anCells?a.anCells[c]:k},1)});o("cells().data()",function(){return this.iterator("cell",function(a,b,c){return B(a,b,c)},1)});u("cells().cache()","cell().cache()",function(a){a="search"===a?"_aFilterData":"_aSortData";return this.iterator("cell",function(b,c,d){return b.aoData[c][a][d]},1)});u("cells().render()","cell().render()",function(a){return this.iterator("cell",function(b,c,d){return B(b,c,d,a)},1)});u("cells().indexes()","cell().index()",function(){return this.iterator("cell",function(a,
b,c){return{row:b,column:c,columnVisible:$(a,c)}},1)});u("cells().invalidate()","cell().invalidate()",function(a){return this.iterator("cell",function(b,c,d){ca(b,c,a,d)})});o("cell()",function(a,b,c){return ab(this.cells(a,b,c))});o("cell().data()",function(a){var b=this.context,c=this[0];if(a===k)return b.length&&c.length?B(b[0],c[0].row,c[0].column):k;ib(b[0],c[0].row,c[0].column,a);ca(b[0],c[0].row,"data",c[0].column);return this});o("order()",function(a,b){var c=this.context;if(a===k)return 0!==
c.length?c[0].aaSorting:k;"number"===typeof a?a=[[a,b]]:a.length&&!h.isArray(a[0])&&(a=Array.prototype.slice.call(arguments));return this.iterator("table",function(b){b.aaSorting=a.slice()})});o("order.listener()",function(a,b,c){return this.iterator("table",function(d){La(d,a,b,c)})});o("order.fixed()",function(a){if(!a){var b=this.context,b=b.length?b[0].aaSortingFixed:k;return h.isArray(b)?{pre:b}:b}return this.iterator("table",function(b){b.aaSortingFixed=h.extend(!0,{},a)})});o(["columns().order()",
"column().order()"],function(a){var b=this;return this.iterator("table",function(c,d){var e=[];h.each(b[d],function(b,c){e.push([c,a])});c.aaSorting=e})});o("search()",function(a,b,c,d){var e=this.context;return a===k?0!==e.length?e[0].oPreviousSearch.sSearch:k:this.iterator("table",function(e){e.oFeatures.bFilter&&fa(e,h.extend({},e.oPreviousSearch,{sSearch:a+"",bRegex:null===b?!1:b,bSmart:null===c?!0:c,bCaseInsensitive:null===d?!0:d}),1)})});u("columns().search()","column().search()",function(a,
b,c,d){return this.iterator("column",function(e,f){var g=e.aoPreSearchCols;if(a===k)return g[f].sSearch;e.oFeatures.bFilter&&(h.extend(g[f],{sSearch:a+"",bRegex:null===b?!1:b,bSmart:null===c?!0:c,bCaseInsensitive:null===d?!0:d}),fa(e,e.oPreviousSearch,1))})});o("state()",function(){return this.context.length?this.context[0].oSavedState:null});o("state.clear()",function(){return this.iterator("table",function(a){a.fnStateSaveCallback.call(a.oInstance,a,{})})});o("state.loaded()",function(){return this.context.length?
this.context[0].oLoadedState:null});o("state.save()",function(){return this.iterator("table",function(a){xa(a)})});m.versionCheck=m.fnVersionCheck=function(a){for(var b=m.version.split("."),a=a.split("."),c,d,e=0,f=a.length;e<f;e++)if(c=parseInt(b[e],10)||0,d=parseInt(a[e],10)||0,c!==d)return c>d;return!0};m.isDataTable=m.fnIsDataTable=function(a){var b=h(a).get(0),c=!1;if(a instanceof m.Api)return!0;h.each(m.settings,function(a,e){var f=e.nScrollHead?h("table",e.nScrollHead)[0]:null,g=e.nScrollFoot?
h("table",e.nScrollFoot)[0]:null;if(e.nTable===b||f===b||g===b)c=!0});return c};m.tables=m.fnTables=function(a){var b=!1;h.isPlainObject(a)&&(b=a.api,a=a.visible);var c=h.map(m.settings,function(b){if(!a||a&&h(b.nTable).is(":visible"))return b.nTable});return b?new s(c):c};m.camelToHungarian=I;o("$()",function(a,b){var c=this.rows(b).nodes(),c=h(c);return h([].concat(c.filter(a).toArray(),c.find(a).toArray()))});h.each(["on","one","off"],function(a,b){o(b+"()",function(){var a=Array.prototype.slice.call(arguments);
a[0]=h.map(a[0].split(/\s/),function(a){return!a.match(/\.dt\b/)?a+".dt":a}).join(" ");var d=h(this.tables().nodes());d[b].apply(d,a);return this})});o("clear()",function(){return this.iterator("table",function(a){na(a)})});o("settings()",function(){return new s(this.context,this.context)});o("init()",function(){var a=this.context;return a.length?a[0].oInit:null});o("data()",function(){return this.iterator("table",function(a){return D(a.aoData,"_aData")}).flatten()});o("destroy()",function(a){a=a||
!1;return this.iterator("table",function(b){var c=b.nTableWrapper.parentNode,d=b.oClasses,e=b.nTable,f=b.nTBody,g=b.nTHead,j=b.nTFoot,i=h(e),f=h(f),k=h(b.nTableWrapper),l=h.map(b.aoData,function(a){return a.nTr}),o;b.bDestroying=!0;r(b,"aoDestroyCallback","destroy",[b]);a||(new s(b)).columns().visible(!0);k.off(".DT").find(":not(tbody *)").off(".DT");h(E).off(".DT-"+b.sInstance);e!=g.parentNode&&(i.children("thead").detach(),i.append(g));j&&e!=j.parentNode&&(i.children("tfoot").detach(),i.append(j));
b.aaSorting=[];b.aaSortingFixed=[];wa(b);h(l).removeClass(b.asStripeClasses.join(" "));h("th, td",g).removeClass(d.sSortable+" "+d.sSortableAsc+" "+d.sSortableDesc+" "+d.sSortableNone);f.children().detach();f.append(l);g=a?"remove":"detach";i[g]();k[g]();!a&&c&&(c.insertBefore(e,b.nTableReinsertBefore),i.css("width",b.sDestroyWidth).removeClass(d.sTable),(o=b.asDestroyStripes.length)&&f.children().each(function(a){h(this).addClass(b.asDestroyStripes[a%o])}));c=h.inArray(b,m.settings);-1!==c&&m.settings.splice(c,
1)})});h.each(["column","row","cell"],function(a,b){o(b+"s().every()",function(a){var d=this.selector.opts,e=this;return this.iterator(b,function(f,g,h,i,n){a.call(e[b](g,"cell"===b?h:d,"cell"===b?d:k),g,h,i,n)})})});o("i18n()",function(a,b,c){var d=this.context[0],a=Q(a)(d.oLanguage);a===k&&(a=b);c!==k&&h.isPlainObject(a)&&(a=a[c]!==k?a[c]:a._);return a.replace("%d",c)});m.version="1.10.16";m.settings=[];m.models={};m.models.oSearch={bCaseInsensitive:!0,sSearch:"",bRegex:!1,bSmart:!0};m.models.oRow=
{nTr:null,anCells:null,_aData:[],_aSortData:null,_aFilterData:null,_sFilterRow:null,_sRowStripe:"",src:null,idx:-1};m.models.oColumn={idx:null,aDataSort:null,asSorting:null,bSearchable:null,bSortable:null,bVisible:null,_sManualType:null,_bAttrSrc:!1,fnCreatedCell:null,fnGetData:null,fnSetData:null,mData:null,mRender:null,nTh:null,nTf:null,sClass:null,sContentPadding:null,sDefaultContent:null,sName:null,sSortDataType:"std",sSortingClass:null,sSortingClassJUI:null,sTitle:null,sType:null,sWidth:null,
sWidthOrig:null};m.defaults={aaData:null,aaSorting:[[0,"asc"]],aaSortingFixed:[],ajax:null,aLengthMenu:[10,25,50,100],aoColumns:null,aoColumnDefs:null,aoSearchCols:[],asStripeClasses:null,bAutoWidth:!0,bDeferRender:!1,bDestroy:!1,bFilter:!0,bInfo:!0,bLengthChange:!0,bPaginate:!0,bProcessing:!1,bRetrieve:!1,bScrollCollapse:!1,bServerSide:!1,bSort:!0,bSortMulti:!0,bSortCellsTop:!1,bSortClasses:!0,bStateSave:!1,fnCreatedRow:null,fnDrawCallback:null,fnFooterCallback:null,fnFormatNumber:function(a){return a.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
this.oLanguage.sThousands)},fnHeaderCallback:null,fnInfoCallback:null,fnInitComplete:null,fnPreDrawCallback:null,fnRowCallback:null,fnServerData:null,fnServerParams:null,fnStateLoadCallback:function(a){try{return JSON.parse((-1===a.iStateDuration?sessionStorage:localStorage).getItem("DataTables_"+a.sInstance+"_"+location.pathname))}catch(b){}},fnStateLoadParams:null,fnStateLoaded:null,fnStateSaveCallback:function(a,b){try{(-1===a.iStateDuration?sessionStorage:localStorage).setItem("DataTables_"+a.sInstance+
"_"+location.pathname,JSON.stringify(b))}catch(c){}},fnStateSaveParams:null,iStateDuration:7200,iDeferLoading:null,iDisplayLength:10,iDisplayStart:0,iTabIndex:0,oClasses:{},oLanguage:{oAria:{sSortAscending:": activate to sort column ascending",sSortDescending:": activate to sort column descending"},oPaginate:{sFirst:"First",sLast:"Last",sNext:"Next",sPrevious:"Previous"},sEmptyTable:"No data available in table",sInfo:"Showing _START_ to _END_ of _TOTAL_ entries",sInfoEmpty:"Showing 0 to 0 of 0 entries",
sInfoFiltered:"(filtered from _MAX_ total entries)",sInfoPostFix:"",sDecimal:"",sThousands:",",sLengthMenu:"Show _MENU_ entries",sLoadingRecords:"Loading...",sProcessing:"Processing...",sSearch:"Search:",sSearchPlaceholder:"",sUrl:"",sZeroRecords:"No matching records found"},oSearch:h.extend({},m.models.oSearch),sAjaxDataProp:"data",sAjaxSource:null,sDom:"lfrtip",searchDelay:null,sPaginationType:"simple_numbers",sScrollX:"",sScrollXInner:"",sScrollY:"",sServerMethod:"GET",renderer:null,rowId:"DT_RowId"};
X(m.defaults);m.defaults.column={aDataSort:null,iDataSort:-1,asSorting:["asc","desc"],bSearchable:!0,bSortable:!0,bVisible:!0,fnCreatedCell:null,mData:null,mRender:null,sCellType:"td",sClass:"",sContentPadding:"",sDefaultContent:null,sName:"",sSortDataType:"std",sTitle:null,sType:null,sWidth:null};X(m.defaults.column);m.models.oSettings={oFeatures:{bAutoWidth:null,bDeferRender:null,bFilter:null,bInfo:null,bLengthChange:null,bPaginate:null,bProcessing:null,bServerSide:null,bSort:null,bSortMulti:null,
bSortClasses:null,bStateSave:null},oScroll:{bCollapse:null,iBarWidth:0,sX:null,sXInner:null,sY:null},oLanguage:{fnInfoCallback:null},oBrowser:{bScrollOversize:!1,bScrollbarLeft:!1,bBounding:!1,barWidth:0},ajax:null,aanFeatures:[],aoData:[],aiDisplay:[],aiDisplayMaster:[],aIds:{},aoColumns:[],aoHeader:[],aoFooter:[],oPreviousSearch:{},aoPreSearchCols:[],aaSorting:null,aaSortingFixed:[],asStripeClasses:null,asDestroyStripes:[],sDestroyWidth:0,aoRowCallback:[],aoHeaderCallback:[],aoFooterCallback:[],
aoDrawCallback:[],aoRowCreatedCallback:[],aoPreDrawCallback:[],aoInitComplete:[],aoStateSaveParams:[],aoStateLoadParams:[],aoStateLoaded:[],sTableId:"",nTable:null,nTHead:null,nTFoot:null,nTBody:null,nTableWrapper:null,bDeferLoading:!1,bInitialised:!1,aoOpenRows:[],sDom:null,searchDelay:null,sPaginationType:"two_button",iStateDuration:0,aoStateSave:[],aoStateLoad:[],oSavedState:null,oLoadedState:null,sAjaxSource:null,sAjaxDataProp:null,bAjaxDataGet:!0,jqXHR:null,json:k,oAjaxData:k,fnServerData:null,
aoServerParams:[],sServerMethod:null,fnFormatNumber:null,aLengthMenu:null,iDraw:0,bDrawing:!1,iDrawError:-1,_iDisplayLength:10,_iDisplayStart:0,_iRecordsTotal:0,_iRecordsDisplay:0,oClasses:{},bFiltered:!1,bSorted:!1,bSortCellsTop:null,oInit:null,aoDestroyCallback:[],fnRecordsTotal:function(){return"ssp"==y(this)?1*this._iRecordsTotal:this.aiDisplayMaster.length},fnRecordsDisplay:function(){return"ssp"==y(this)?1*this._iRecordsDisplay:this.aiDisplay.length},fnDisplayEnd:function(){var a=this._iDisplayLength,
b=this._iDisplayStart,c=b+a,d=this.aiDisplay.length,e=this.oFeatures,f=e.bPaginate;return e.bServerSide?!1===f||-1===a?b+d:Math.min(b+a,this._iRecordsDisplay):!f||c>d||-1===a?d:c},oInstance:null,sInstance:null,iTabIndex:0,nScrollHead:null,nScrollFoot:null,aLastSort:[],oPlugins:{},rowIdFn:null,rowId:null};m.ext=x={buttons:{},classes:{},build:"bs/dt-1.10.16",errMode:"alert",feature:[],search:[],selector:{cell:[],column:[],row:[]},internal:{},legacy:{ajax:null},pager:{},renderer:{pageButton:{},header:{}},
order:{},type:{detect:[],search:{},order:{}},_unique:0,fnVersionCheck:m.fnVersionCheck,iApiIndex:0,oJUIClasses:{},sVersion:m.version};h.extend(x,{afnFiltering:x.search,aTypes:x.type.detect,ofnSearch:x.type.search,oSort:x.type.order,afnSortData:x.order,aoFeatures:x.feature,oApi:x.internal,oStdClasses:x.classes,oPagination:x.pager});h.extend(m.ext.classes,{sTable:"dataTable",sNoFooter:"no-footer",sPageButton:"paginate_button",sPageButtonActive:"current",sPageButtonDisabled:"disabled",sStripeOdd:"odd",
sStripeEven:"even",sRowEmpty:"dataTables_empty",sWrapper:"dataTables_wrapper",sFilter:"dataTables_filter",sInfo:"dataTables_info",sPaging:"dataTables_paginate paging_",sLength:"dataTables_length",sProcessing:"dataTables_processing",sSortAsc:"sorting_asc",sSortDesc:"sorting_desc",sSortable:"sorting",sSortableAsc:"sorting_asc_disabled",sSortableDesc:"sorting_desc_disabled",sSortableNone:"sorting_disabled",sSortColumn:"sorting_",sFilterInput:"",sLengthSelect:"",sScrollWrapper:"dataTables_scroll",sScrollHead:"dataTables_scrollHead",
sScrollHeadInner:"dataTables_scrollHeadInner",sScrollBody:"dataTables_scrollBody",sScrollFoot:"dataTables_scrollFoot",sScrollFootInner:"dataTables_scrollFootInner",sHeaderTH:"",sFooterTH:"",sSortJUIAsc:"",sSortJUIDesc:"",sSortJUI:"",sSortJUIAscAllowed:"",sSortJUIDescAllowed:"",sSortJUIWrapper:"",sSortIcon:"",sJUIHeader:"",sJUIFooter:""});var Kb=m.ext.pager;h.extend(Kb,{simple:function(){return["previous","next"]},full:function(){return["first","previous","next","last"]},numbers:function(a,b){return[ha(a,
b)]},simple_numbers:function(a,b){return["previous",ha(a,b),"next"]},full_numbers:function(a,b){return["first","previous",ha(a,b),"next","last"]},first_last_numbers:function(a,b){return["first",ha(a,b),"last"]},_numbers:ha,numbers_length:7});h.extend(!0,m.ext.renderer,{pageButton:{_:function(a,b,c,d,e,f){var g=a.oClasses,j=a.oLanguage.oPaginate,i=a.oLanguage.oAria.paginate||{},n,l,m=0,o=function(b,d){var k,s,u,r,v=function(b){Sa(a,b.data.action,true)};k=0;for(s=d.length;k<s;k++){r=d[k];if(h.isArray(r)){u=
h("<"+(r.DT_el||"div")+"/>").appendTo(b);o(u,r)}else{n=null;l="";switch(r){case "ellipsis":b.append('<span class="ellipsis">&#x2026;</span>');break;case "first":n=j.sFirst;l=r+(e>0?"":" "+g.sPageButtonDisabled);break;case "previous":n=j.sPrevious;l=r+(e>0?"":" "+g.sPageButtonDisabled);break;case "next":n=j.sNext;l=r+(e<f-1?"":" "+g.sPageButtonDisabled);break;case "last":n=j.sLast;l=r+(e<f-1?"":" "+g.sPageButtonDisabled);break;default:n=r+1;l=e===r?g.sPageButtonActive:""}if(n!==null){u=h("<a>",{"class":g.sPageButton+
" "+l,"aria-controls":a.sTableId,"aria-label":i[r],"data-dt-idx":m,tabindex:a.iTabIndex,id:c===0&&typeof r==="string"?a.sTableId+"_"+r:null}).html(n).appendTo(b);Va(u,{action:r},v);m++}}}},s;try{s=h(b).find(G.activeElement).data("dt-idx")}catch(u){}o(h(b).empty(),d);s!==k&&h(b).find("[data-dt-idx="+s+"]").focus()}}});h.extend(m.ext.type.detect,[function(a,b){var c=b.oLanguage.sDecimal;return Ya(a,c)?"num"+c:null},function(a){if(a&&!(a instanceof Date)&&!Zb.test(a))return null;var b=Date.parse(a);
return null!==b&&!isNaN(b)||L(a)?"date":null},function(a,b){var c=b.oLanguage.sDecimal;return Ya(a,c,!0)?"num-fmt"+c:null},function(a,b){var c=b.oLanguage.sDecimal;return Pb(a,c)?"html-num"+c:null},function(a,b){var c=b.oLanguage.sDecimal;return Pb(a,c,!0)?"html-num-fmt"+c:null},function(a){return L(a)||"string"===typeof a&&-1!==a.indexOf("<")?"html":null}]);h.extend(m.ext.type.search,{html:function(a){return L(a)?a:"string"===typeof a?a.replace(Mb," ").replace(Aa,""):""},string:function(a){return L(a)?
a:"string"===typeof a?a.replace(Mb," "):a}});var za=function(a,b,c,d){if(0!==a&&(!a||"-"===a))return-Infinity;b&&(a=Ob(a,b));a.replace&&(c&&(a=a.replace(c,"")),d&&(a=a.replace(d,"")));return 1*a};h.extend(x.type.order,{"date-pre":function(a){return Date.parse(a)||-Infinity},"html-pre":function(a){return L(a)?"":a.replace?a.replace(/<.*?>/g,"").toLowerCase():a+""},"string-pre":function(a){return L(a)?"":"string"===typeof a?a.toLowerCase():!a.toString?"":a.toString()},"string-asc":function(a,b){return a<
b?-1:a>b?1:0},"string-desc":function(a,b){return a<b?1:a>b?-1:0}});cb("");h.extend(!0,m.ext.renderer,{header:{_:function(a,b,c,d){h(a.nTable).on("order.dt.DT",function(e,f,g,h){if(a===f){e=c.idx;b.removeClass(c.sSortingClass+" "+d.sSortAsc+" "+d.sSortDesc).addClass(h[e]=="asc"?d.sSortAsc:h[e]=="desc"?d.sSortDesc:c.sSortingClass)}})},jqueryui:function(a,b,c,d){h("<div/>").addClass(d.sSortJUIWrapper).append(b.contents()).append(h("<span/>").addClass(d.sSortIcon+" "+c.sSortingClassJUI)).appendTo(b);
h(a.nTable).on("order.dt.DT",function(e,f,g,h){if(a===f){e=c.idx;b.removeClass(d.sSortAsc+" "+d.sSortDesc).addClass(h[e]=="asc"?d.sSortAsc:h[e]=="desc"?d.sSortDesc:c.sSortingClass);b.find("span."+d.sSortIcon).removeClass(d.sSortJUIAsc+" "+d.sSortJUIDesc+" "+d.sSortJUI+" "+d.sSortJUIAscAllowed+" "+d.sSortJUIDescAllowed).addClass(h[e]=="asc"?d.sSortJUIAsc:h[e]=="desc"?d.sSortJUIDesc:c.sSortingClassJUI)}})}}});var Vb=function(a){return"string"===typeof a?a.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,
"&quot;"):a};m.render={number:function(a,b,c,d,e){return{display:function(f){if("number"!==typeof f&&"string"!==typeof f)return f;var g=0>f?"-":"",h=parseFloat(f);if(isNaN(h))return Vb(f);h=h.toFixed(c);f=Math.abs(h);h=parseInt(f,10);f=c?b+(f-h).toFixed(c).substring(2):"";return g+(d||"")+h.toString().replace(/\B(?=(\d{3})+(?!\d))/g,a)+f+(e||"")}}},text:function(){return{display:Vb}}};h.extend(m.ext.internal,{_fnExternApiFunc:Lb,_fnBuildAjax:sa,_fnAjaxUpdate:kb,_fnAjaxParameters:tb,_fnAjaxUpdateDraw:ub,
_fnAjaxDataSrc:ta,_fnAddColumn:Da,_fnColumnOptions:ja,_fnAdjustColumnSizing:Y,_fnVisibleToColumnIndex:Z,_fnColumnIndexToVisible:$,_fnVisbleColumns:aa,_fnGetColumns:la,_fnColumnTypes:Fa,_fnApplyColumnDefs:hb,_fnHungarianMap:X,_fnCamelToHungarian:I,_fnLanguageCompat:Ca,_fnBrowserDetect:fb,_fnAddData:M,_fnAddTr:ma,_fnNodeToDataIndex:function(a,b){return b._DT_RowIndex!==k?b._DT_RowIndex:null},_fnNodeToColumnIndex:function(a,b,c){return h.inArray(c,a.aoData[b].anCells)},_fnGetCellData:B,_fnSetCellData:ib,
_fnSplitObjNotation:Ia,_fnGetObjectDataFn:Q,_fnSetObjectDataFn:R,_fnGetDataMaster:Ja,_fnClearTable:na,_fnDeleteIndex:oa,_fnInvalidate:ca,_fnGetRowElements:Ha,_fnCreateTr:Ga,_fnBuildHead:jb,_fnDrawHead:ea,_fnDraw:N,_fnReDraw:S,_fnAddOptionsHtml:mb,_fnDetectHeader:da,_fnGetUniqueThs:ra,_fnFeatureHtmlFilter:ob,_fnFilterComplete:fa,_fnFilterCustom:xb,_fnFilterColumn:wb,_fnFilter:vb,_fnFilterCreateSearch:Oa,_fnEscapeRegex:Pa,_fnFilterData:yb,_fnFeatureHtmlInfo:rb,_fnUpdateInfo:Bb,_fnInfoMacros:Cb,_fnInitialise:ga,
_fnInitComplete:ua,_fnLengthChange:Qa,_fnFeatureHtmlLength:nb,_fnFeatureHtmlPaginate:sb,_fnPageChange:Sa,_fnFeatureHtmlProcessing:pb,_fnProcessingDisplay:C,_fnFeatureHtmlTable:qb,_fnScrollDraw:ka,_fnApplyToChildren:H,_fnCalculateColumnWidths:Ea,_fnThrottle:Na,_fnConvertToWidth:Db,_fnGetWidestNode:Eb,_fnGetMaxLenString:Fb,_fnStringToCss:v,_fnSortFlatten:V,_fnSort:lb,_fnSortAria:Hb,_fnSortListener:Ua,_fnSortAttachListener:La,_fnSortingClasses:wa,_fnSortData:Gb,_fnSaveState:xa,_fnLoadState:Ib,_fnSettingsFromNode:ya,
_fnLog:J,_fnMap:F,_fnBindAction:Va,_fnCallbackReg:z,_fnCallbackFire:r,_fnLengthOverflow:Ra,_fnRenderer:Ma,_fnDataSource:y,_fnRowAttributes:Ka,_fnCalculateEnd:function(){}});h.fn.dataTable=m;m.$=h;h.fn.dataTableSettings=m.settings;h.fn.dataTableExt=m.ext;h.fn.DataTable=function(a){return h(this).dataTable(a).api()};h.each(m,function(a,b){h.fn.DataTable[a]=b});return h.fn.dataTable});


/*!
 DataTables Bootstrap 3 integration
 ©2011-2015 SpryMedia Ltd - datatables.net/license
*/
(function(b){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(a){return b(a,window,document)}):"object"===typeof exports?module.exports=function(a,d){a||(a=window);if(!d||!d.fn.dataTable)d=require("datatables.net")(a,d).$;return b(d,a,a.document)}:b(jQuery,window,document)})(function(b,a,d,m){var f=b.fn.dataTable;b.extend(!0,f.defaults,{dom:"<'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",renderer:"bootstrap"});b.extend(f.ext.classes,
{sWrapper:"dataTables_wrapper form-inline dt-bootstrap",sFilterInput:"form-control input-sm",sLengthSelect:"form-control input-sm",sProcessing:"dataTables_processing panel panel-default"});f.ext.renderer.pageButton.bootstrap=function(a,h,r,s,j,n){var o=new f.Api(a),t=a.oClasses,k=a.oLanguage.oPaginate,u=a.oLanguage.oAria.paginate||{},e,g,p=0,q=function(d,f){var l,h,i,c,m=function(a){a.preventDefault();!b(a.currentTarget).hasClass("disabled")&&o.page()!=a.data.action&&o.page(a.data.action).draw("page")};
l=0;for(h=f.length;l<h;l++)if(c=f[l],b.isArray(c))q(d,c);else{g=e="";switch(c){case "ellipsis":e="&#x2026;";g="disabled";break;case "first":e=k.sFirst;g=c+(0<j?"":" disabled");break;case "previous":e=k.sPrevious;g=c+(0<j?"":" disabled");break;case "next":e=k.sNext;g=c+(j<n-1?"":" disabled");break;case "last":e=k.sLast;g=c+(j<n-1?"":" disabled");break;default:e=c+1,g=j===c?"active":""}e&&(i=b("<li>",{"class":t.sPageButton+" "+g,id:0===r&&"string"===typeof c?a.sTableId+"_"+c:null}).append(b("<a>",{href:"#",
"aria-controls":a.sTableId,"aria-label":u[c],"data-dt-idx":p,tabindex:a.iTabIndex}).html(e)).appendTo(d),a.oApi._fnBindAction(i,{action:c},m),p++)}},i;try{i=b(h).find(d.activeElement).data("dt-idx")}catch(v){}q(b(h).empty().html('<ul class="pagination"/>').children("ul"),s);i!==m&&b(h).find("[data-dt-idx="+i+"]").focus()};return f});



			!function(t){function n(e){if(o[e])return o[e].exports;var i=o[e]={exports:{},id:e,loaded:!1};return t[e].call(i.exports,i,i.exports,n),i.loaded=!0,i.exports}var o={};return n.m=t,n.c=o,n.p="",n(0)}([function(t,n,o){window.BootstrapMenu=o(1)},function(t,n,o){"use strict";function e(t){var n=f('<div class="dropdown bootstrapMenu" style="z-index:10000;position:absolute;" />'),o=f('<ul class="dropdown-menu" style="position:static;display:block;font-size:0.9em;" />'),e=[];e[0]=[],p.each(t.options.actionsGroups,function(t,n){e[n+1]=[]});var i=!1;p.each(t.options.actions,function(n,o){var r=!1;p.each(t.options.actionsGroups,function(t,n){p.contains(t,o)&&(e[n+1].push(o),r=!0)}),r===!1&&e[0].push(o),"undefined"!=typeof n.iconClass&&(i=!0)});var r=!0;return p.each(e,function(n){0!=n.length&&(r===!1&&o.append('<li class="divider"></li>'),r=!1,p.each(n,function(n){var e=t.options.actions[n];i===!0?o.append('<li role="presentation" data-action="'+n+'"><a href="#" role="menuitem"><i class="fa fa-fw fa-lg '+(e.iconClass||"")+'"></i> <span class="actionName"></span></a></li>'):o.append('<li role="presentation" data-action="'+n+'"><a href="#" role="menuitem"><span class="actionName"></span></a></li>')}),o.append('<li role="presentation" class="noActionsMessage disabled"><a href="#" role="menuitem"><span>'+t.options.noActionsMessage+"</span></a></li>"))}),n.append(o)}function i(t){var n=null;switch(t.options.menuEvent){case"click":n="click";break;case"right-click":n="contextmenu";break;case"hover":n="mouseenter";break;default:throw new Error("Unknown BootstrapMenu 'menuEvent' option")}t.$container.on(n+t.namespace,t.selector,function(n){var o=f(this);return t.open(o,n),!1})}function r(t){t.$container.off(t.namespace)}function s(t){var n=t.options._actionSelectEvent+t.namespace;t.$menu.on(n,function(n){n.preventDefault(),n.stopPropagation();var o=f(n.target),e=o.closest("[data-action]");if(e&&e.length&&!e.is(".disabled")){var i=e.data("action"),r=t.options.fetchElementData(t.$openTarget);t.options.actions[i].onClick(r),t.close()}})}function c(t){t.$menu.off(t.namespace)}function a(t){switch(t.options.menuEvent){case"click":break;case"right-click":break;case"hover":var n=t.$openTarget.add(t.$menu);n.on("mouseleave"+t.closeNamespace,function(o){var e=o.toElement||o.relatedTarget;t.$openTarget.is(e)||t.$menu.is(e)||(n.off(t.closeNamespace),t.close())});break;default:throw new Error("Unknown BootstrapMenu 'menuEvent' option")}t.$container.on("click"+t.closeNamespace,function(){t.close()})}function u(t){t.$container.off(t.closeNamespace)}var l=o(2),f=o(3);o(4);var p=function(){throw new Error("Custom lodash build for BootstrapMenu. lodash chaining is not included")};p.noop=o(6),p.each=o(7),p.contains=o(34),p.extend=o(42),p.uniqueId=o(49),p.isFunction=o(19);var h={container:"body",fetchElementData:p.noop,menuSource:"mouse",menuPosition:"belowLeft",menuEvent:"right-click",actionsGroups:[],noActionsMessage:"No available actions",_actionSelectEvent:"click"},d=function(t,n){this.selector=t,this.options=p.extend({},h,n),this.namespace=p.uniqueId(".BootstrapMenu_"),this.closeNamespace=p.uniqueId(".BootstrapMenuClose_"),this.init()},v=[];d.prototype.init=function(){this.$container=f(this.options.container),this.$menu=e(this),this.$menuList=this.$menu.children(),this.$menu.hide().appendTo(this.$container),this.$openTarget=null,this.openEvent=null,i(this),s(this),v.push(this)},d.prototype.updatePosition=function(){var t=null,n=null,o=null;switch(this.options.menuSource){case"element":n=this.$openTarget;break;case"mouse":n=this.openEvent;break;default:throw new Error("Unknown BootstrapMenu 'menuSource' option")}switch(this.options.menuPosition){case"belowRight":t="right top",o="right bottom";break;case"belowLeft":t="left top",o="left bottom";break;case"aboveRight":t="right bottom",o="right top";break;case"aboveLeft":t="left bottom",o="left top";break;default:throw new Error("Unknown BootstrapMenu 'menuPosition' option")}this.$menu.css({display:"block"}),this.$menu.css({height:this.$menuList.height(),width:this.$menuList.width()}),this.$menu.position({my:t,at:o,of:n})},d.prototype.open=function(t,n){var o=this;d.closeAll(),this.$openTarget=t,this.openEvent=n;var e=o.options.fetchElementData(o.$openTarget),i=this.$menu.find("[data-action]"),r=this.$menu.find(".noActionsMessage");i.show(),r.hide();var s=0;i.each(function(){var t=f(this),n=t.data("action"),i=o.options.actions[n],r=i.classNames||null;return r&&p.isFunction(r)&&(r=r(e)),t.attr("class",l(r||"")),i.isShown&&i.isShown(e)===!1?void t.hide():(s++,t.find(".actionName").html(p.isFunction(i.name)&&i.name(e)||i.name),void(i.isEnabled&&i.isEnabled(e)===!1&&t.addClass("disabled")))}),0===s&&r.show(),this.updatePosition(),this.$menu.show(),a(this)},d.prototype.close=function(){this.$menu.hide(),u(this)},d.prototype.destroy=function(){this.close(),r(this),c(this)},d.closeAll=function(){p.each(v,function(t){t.close()})},t.exports=d},function(t,n,o){var e,i;/*!
	  Copyright (c) 2016 Jed Watson.
	  Licensed under the MIT License (MIT), see
	  http://jedwatson.github.io/classnames
	*/
!function(){"use strict";function o(){for(var t=[],n=0;n<arguments.length;n++){var e=arguments[n];if(e){var i=typeof e;if("string"===i||"number"===i)t.push(e);else if(Array.isArray(e))t.push(o.apply(null,e));else if("object"===i)for(var s in e)r.call(e,s)&&e[s]&&t.push(s)}}return t.join(" ")}var r={}.hasOwnProperty;"undefined"!=typeof t&&t.exports?t.exports=o:(e=[],i=function(){return o}.apply(n,e),!(void 0!==i&&(t.exports=i)))}()},function(t,n){t.exports=jQuery},function(t,n,o){var e,i,r;/*!
	 * jQuery UI Position 1.12.0
	 * http://jqueryui.com
	 *
	 * Copyright jQuery Foundation and other contributors
	 * Released under the MIT license.
	 * http://jquery.org/license
	 *
	 * http://api.jqueryui.com/position/
	 */
!function(s){i=[o(3),o(5)],e=s,r="function"==typeof e?e.apply(n,i):e,!(void 0!==r&&(t.exports=r))}(function(t){return function(){function n(t,n,o){return[parseFloat(t[0])*(h.test(t[0])?n/100:1),parseFloat(t[1])*(h.test(t[1])?o/100:1)]}function o(n,o){return parseInt(t.css(n,o),10)||0}function e(n){var o=n[0];return 9===o.nodeType?{width:n.width(),height:n.height(),offset:{top:0,left:0}}:t.isWindow(o)?{width:n.width(),height:n.height(),offset:{top:n.scrollTop(),left:n.scrollLeft()}}:o.preventDefault?{width:0,height:0,offset:{top:o.pageY,left:o.pageX}}:{width:n.outerWidth(),height:n.outerHeight(),offset:n.offset()}}var i,r,s=Math.max,c=Math.abs,a=Math.round,u=/left|center|right/,l=/top|center|bottom/,f=/[\+\-]\d+(\.[\d]+)?%?/,p=/^\w+/,h=/%$/,d=t.fn.position;r=function(){var n=t("<div>").css("position","absolute").appendTo("body").offset({top:1.5,left:1.5}),o=1.5===n.offset().top;return n.remove(),r=function(){return o},o},t.position={scrollbarWidth:function(){if(void 0!==i)return i;var n,o,e=t("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),r=e.children()[0];return t("body").append(e),n=r.offsetWidth,e.css("overflow","scroll"),o=r.offsetWidth,n===o&&(o=e[0].clientWidth),e.remove(),i=n-o},getScrollInfo:function(n){var o=n.isWindow||n.isDocument?"":n.element.css("overflow-x"),e=n.isWindow||n.isDocument?"":n.element.css("overflow-y"),i="scroll"===o||"auto"===o&&n.width<n.element[0].scrollWidth,r="scroll"===e||"auto"===e&&n.height<n.element[0].scrollHeight;return{width:r?t.position.scrollbarWidth():0,height:i?t.position.scrollbarWidth():0}},getWithinInfo:function(n){var o=t(n||window),e=t.isWindow(o[0]),i=!!o[0]&&9===o[0].nodeType,r=!e&&!i;return{element:o,isWindow:e,isDocument:i,offset:r?t(n).offset():{left:0,top:0},scrollLeft:o.scrollLeft(),scrollTop:o.scrollTop(),width:o.outerWidth(),height:o.outerHeight()}}},t.fn.position=function(i){if(!i||!i.of)return d.apply(this,arguments);i=t.extend({},i);var h,v,m,g,y,w,x=t(i.of),b=t.position.getWithinInfo(i.within),$=t.position.getScrollInfo(b),W=(i.collision||"flip").split(" "),k={};return w=e(x),x[0].preventDefault&&(i.at="left top"),v=w.width,m=w.height,g=w.offset,y=t.extend({},g),t.each(["my","at"],function(){var t,n,o=(i[this]||"").split(" ");1===o.length&&(o=u.test(o[0])?o.concat(["center"]):l.test(o[0])?["center"].concat(o):["center","center"]),o[0]=u.test(o[0])?o[0]:"center",o[1]=l.test(o[1])?o[1]:"center",t=f.exec(o[0]),n=f.exec(o[1]),k[this]=[t?t[0]:0,n?n[0]:0],i[this]=[p.exec(o[0])[0],p.exec(o[1])[0]]}),1===W.length&&(W[1]=W[0]),"right"===i.at[0]?y.left+=v:"center"===i.at[0]&&(y.left+=v/2),"bottom"===i.at[1]?y.top+=m:"center"===i.at[1]&&(y.top+=m/2),h=n(k.at,v,m),y.left+=h[0],y.top+=h[1],this.each(function(){var e,u,l=t(this),f=l.outerWidth(),p=l.outerHeight(),d=o(this,"marginLeft"),w=o(this,"marginTop"),E=f+d+o(this,"marginRight")+$.width,T=p+w+o(this,"marginBottom")+$.height,j=t.extend({},y),P=n(k.my,l.outerWidth(),l.outerHeight());"right"===i.my[0]?j.left-=f:"center"===i.my[0]&&(j.left-=f/2),"bottom"===i.my[1]?j.top-=p:"center"===i.my[1]&&(j.top-=p/2),j.left+=P[0],j.top+=P[1],r()||(j.left=a(j.left),j.top=a(j.top)),e={marginLeft:d,marginTop:w},t.each(["left","top"],function(n,o){t.ui.position[W[n]]&&t.ui.position[W[n]][o](j,{targetWidth:v,targetHeight:m,elemWidth:f,elemHeight:p,collisionPosition:e,collisionWidth:E,collisionHeight:T,offset:[h[0]+P[0],h[1]+P[1]],my:i.my,at:i.at,within:b,elem:l})}),i.using&&(u=function(t){var n=g.left-j.left,o=n+v-f,e=g.top-j.top,r=e+m-p,a={target:{element:x,left:g.left,top:g.top,width:v,height:m},element:{element:l,left:j.left,top:j.top,width:f,height:p},horizontal:o<0?"left":n>0?"right":"center",vertical:r<0?"top":e>0?"bottom":"middle"};v<f&&c(n+o)<v&&(a.horizontal="center"),m<p&&c(e+r)<m&&(a.vertical="middle"),s(c(n),c(o))>s(c(e),c(r))?a.important="horizontal":a.important="vertical",i.using.call(this,t,a)}),l.offset(t.extend(j,{using:u}))})},t.ui.position={fit:{left:function(t,n){var o,e=n.within,i=e.isWindow?e.scrollLeft:e.offset.left,r=e.width,c=t.left-n.collisionPosition.marginLeft,a=i-c,u=c+n.collisionWidth-r-i;n.collisionWidth>r?a>0&&u<=0?(o=t.left+a+n.collisionWidth-r-i,t.left+=a-o):u>0&&a<=0?t.left=i:a>u?t.left=i+r-n.collisionWidth:t.left=i:a>0?t.left+=a:u>0?t.left-=u:t.left=s(t.left-c,t.left)},top:function(t,n){var o,e=n.within,i=e.isWindow?e.scrollTop:e.offset.top,r=n.within.height,c=t.top-n.collisionPosition.marginTop,a=i-c,u=c+n.collisionHeight-r-i;n.collisionHeight>r?a>0&&u<=0?(o=t.top+a+n.collisionHeight-r-i,t.top+=a-o):u>0&&a<=0?t.top=i:a>u?t.top=i+r-n.collisionHeight:t.top=i:a>0?t.top+=a:u>0?t.top-=u:t.top=s(t.top-c,t.top)}},flip:{left:function(t,n){var o,e,i=n.within,r=i.offset.left+i.scrollLeft,s=i.width,a=i.isWindow?i.scrollLeft:i.offset.left,u=t.left-n.collisionPosition.marginLeft,l=u-a,f=u+n.collisionWidth-s-a,p="left"===n.my[0]?-n.elemWidth:"right"===n.my[0]?n.elemWidth:0,h="left"===n.at[0]?n.targetWidth:"right"===n.at[0]?-n.targetWidth:0,d=-2*n.offset[0];l<0?(o=t.left+p+h+d+n.collisionWidth-s-r,(o<0||o<c(l))&&(t.left+=p+h+d)):f>0&&(e=t.left-n.collisionPosition.marginLeft+p+h+d-a,(e>0||c(e)<f)&&(t.left+=p+h+d))},top:function(t,n){var o,e,i=n.within,r=i.offset.top+i.scrollTop,s=i.height,a=i.isWindow?i.scrollTop:i.offset.top,u=t.top-n.collisionPosition.marginTop,l=u-a,f=u+n.collisionHeight-s-a,p="top"===n.my[1],h=p?-n.elemHeight:"bottom"===n.my[1]?n.elemHeight:0,d="top"===n.at[1]?n.targetHeight:"bottom"===n.at[1]?-n.targetHeight:0,v=-2*n.offset[1];l<0?(e=t.top+h+d+v+n.collisionHeight-s-r,(e<0||e<c(l))&&(t.top+=h+d+v)):f>0&&(o=t.top-n.collisionPosition.marginTop+h+d+v-a,(o>0||c(o)<f)&&(t.top+=h+d+v))}},flipfit:{left:function(){t.ui.position.flip.left.apply(this,arguments),t.ui.position.fit.left.apply(this,arguments)},top:function(){t.ui.position.flip.top.apply(this,arguments),t.ui.position.fit.top.apply(this,arguments)}}}}(),t.ui.position})},function(t,n,o){var e,i,r;!function(s){i=[o(3)],e=s,r="function"==typeof e?e.apply(n,i):e,!(void 0!==r&&(t.exports=r))}(function(t){return t.ui=t.ui||{},t.ui.version="1.12.0"})},function(t,n){function o(){}t.exports=o},function(t,n,o){t.exports=o(8)},function(t,n,o){var e=o(9),i=o(10),r=o(31),s=r(e,i);t.exports=s},function(t,n){function o(t,n){for(var o=-1,e=t.length;++o<e&&n(t[o],o,t)!==!1;);return t}t.exports=o},function(t,n,o){var e=o(11),i=o(30),r=i(e);t.exports=r},function(t,n,o){function e(t,n){return i(t,n,r)}var i=o(12),r=o(16);t.exports=e},function(t,n,o){var e=o(13),i=e();t.exports=i},function(t,n,o){function e(t){return function(n,o,e){for(var r=i(n),s=e(n),c=s.length,a=t?c:-1;t?a--:++a<c;){var u=s[a];if(o(r[u],u,r)===!1)break}return n}}var i=o(14);t.exports=e},function(t,n,o){function e(t){return i(t)?t:Object(t)}var i=o(15);t.exports=e},function(t,n){function o(t){var n=typeof t;return!!t&&("object"==n||"function"==n)}t.exports=o},function(t,n,o){var e=o(17),i=o(21),r=o(15),s=o(25),c=e(Object,"keys"),a=c?function(t){var n=null==t?void 0:t.constructor;return"function"==typeof n&&n.prototype===t||"function"!=typeof t&&i(t)?s(t):r(t)?c(t):[]}:s;t.exports=a},function(t,n,o){function e(t,n){var o=null==t?void 0:t[n];return i(o)?o:void 0}var i=o(18);t.exports=e},function(t,n,o){function e(t){return null!=t&&(i(t)?l.test(a.call(t)):r(t)&&s.test(t))}var i=o(19),r=o(20),s=/^\[object .+?Constructor\]$/,c=Object.prototype,a=Function.prototype.toString,u=c.hasOwnProperty,l=RegExp("^"+a.call(u).replace(/[\\^$.*+?()[\]{}|]/g,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$");t.exports=e},function(t,n,o){function e(t){return i(t)&&c.call(t)==r}var i=o(15),r="[object Function]",s=Object.prototype,c=s.toString;t.exports=e},function(t,n){function o(t){return!!t&&"object"==typeof t}t.exports=o},function(t,n,o){function e(t){return null!=t&&r(i(t))}var i=o(22),r=o(24);t.exports=e},function(t,n,o){var e=o(23),i=e("length");t.exports=i},function(t,n){function o(t){return function(n){return null==n?void 0:n[t]}}t.exports=o},function(t,n){function o(t){return"number"==typeof t&&t>-1&&t%1==0&&t<=e}var e=9007199254740991;t.exports=o},function(t,n,o){function e(t){for(var n=a(t),o=n.length,e=o&&t.length,u=!!e&&c(e)&&(r(t)||i(t)),f=-1,p=[];++f<o;){var h=n[f];(u&&s(h,e)||l.call(t,h))&&p.push(h)}return p}var i=o(26),r=o(27),s=o(28),c=o(24),a=o(29),u=Object.prototype,l=u.hasOwnProperty;t.exports=e},function(t,n,o){function e(t){return r(t)&&i(t)&&c.call(t,"callee")&&!a.call(t,"callee")}var i=o(21),r=o(20),s=Object.prototype,c=s.hasOwnProperty,a=s.propertyIsEnumerable;t.exports=e},function(t,n,o){var e=o(17),i=o(24),r=o(20),s="[object Array]",c=Object.prototype,a=c.toString,u=e(Array,"isArray"),l=u||function(t){return r(t)&&i(t.length)&&a.call(t)==s};t.exports=l},function(t,n){function o(t,n){return t="number"==typeof t||e.test(t)?+t:-1,n=null==n?i:n,t>-1&&t%1==0&&t<n}var e=/^\d+$/,i=9007199254740991;t.exports=o},function(t,n,o){function e(t){if(null==t)return[];a(t)||(t=Object(t));var n=t.length;n=n&&c(n)&&(r(t)||i(t))&&n||0;for(var o=t.constructor,e=-1,u="function"==typeof o&&o.prototype===t,f=Array(n),p=n>0;++e<n;)f[e]=e+"";for(var h in t)p&&s(h,n)||"constructor"==h&&(u||!l.call(t,h))||f.push(h);return f}var i=o(26),r=o(27),s=o(28),c=o(24),a=o(15),u=Object.prototype,l=u.hasOwnProperty;t.exports=e},function(t,n,o){function e(t,n){return function(o,e){var c=o?i(o):0;if(!r(c))return t(o,e);for(var a=n?c:-1,u=s(o);(n?a--:++a<c)&&e(u[a],a,u)!==!1;);return o}}var i=o(22),r=o(24),s=o(14);t.exports=e},function(t,n,o){function e(t,n){return function(o,e,s){return"function"==typeof e&&void 0===s&&r(o)?t(o,e):n(o,i(e,s,3))}}var i=o(32),r=o(27);t.exports=e},function(t,n,o){function e(t,n,o){if("function"!=typeof t)return i;if(void 0===n)return t;switch(o){case 1:return function(o){return t.call(n,o)};case 3:return function(o,e,i){return t.call(n,o,e,i)};case 4:return function(o,e,i,r){return t.call(n,o,e,i,r)};case 5:return function(o,e,i,r,s){return t.call(n,o,e,i,r,s)}}return function(){return t.apply(n,arguments)}}var i=o(33);t.exports=e},function(t,n){function o(t){return t}t.exports=o},function(t,n,o){t.exports=o(35)},function(t,n,o){function e(t,n,o,e){var p=t?r(t):0;return a(p)||(t=l(t),p=t.length),o="number"!=typeof o||e&&c(n,o,e)?0:o<0?f(p+o,0):o||0,"string"==typeof t||!s(t)&&u(t)?o<=p&&t.indexOf(n,o)>-1:!!p&&i(t,n,o)>-1}var i=o(36),r=o(22),s=o(27),c=o(38),a=o(24),u=o(39),l=o(40),f=Math.max;t.exports=e},function(t,n,o){function e(t,n,o){if(n!==n)return i(t,o);for(var e=o-1,r=t.length;++e<r;)if(t[e]===n)return e;return-1}var i=o(37);t.exports=e},function(t,n){function o(t,n,o){for(var e=t.length,i=n+(o?0:-1);o?i--:++i<e;){var r=t[i];if(r!==r)return i}return-1}t.exports=o},function(t,n,o){function e(t,n,o){if(!s(o))return!1;var e=typeof n;if("number"==e?i(o)&&r(n,o.length):"string"==e&&n in o){var c=o[n];return t===t?t===c:c!==c}return!1}var i=o(21),r=o(28),s=o(15);t.exports=e},function(t,n,o){function e(t){return"string"==typeof t||i(t)&&c.call(t)==r}var i=o(20),r="[object String]",s=Object.prototype,c=s.toString;t.exports=e},function(t,n,o){function e(t){return i(t,r(t))}var i=o(41),r=o(16);t.exports=e},function(t,n){function o(t,n){for(var o=-1,e=n.length,i=Array(e);++o<e;)i[o]=t[n[o]];return i}t.exports=o},function(t,n,o){t.exports=o(43)},function(t,n,o){var e=o(44),i=o(45),r=o(47),s=r(function(t,n,o){return o?e(t,n,o):i(t,n)});t.exports=s},function(t,n,o){function e(t,n,o){for(var e=-1,r=i(n),s=r.length;++e<s;){var c=r[e],a=t[c],u=o(a,n[c],c,t,n);(u===u?u===a:a!==a)&&(void 0!==a||c in t)||(t[c]=u)}return t}var i=o(16);t.exports=e},function(t,n,o){function e(t,n){return null==n?t:i(n,r(n),t)}var i=o(46),r=o(16);t.exports=e},function(t,n){function o(t,n,o){o||(o={});for(var e=-1,i=n.length;++e<i;){var r=n[e];o[r]=t[r]}return o}t.exports=o},function(t,n,o){function e(t){return s(function(n,o){var e=-1,s=null==n?0:o.length,c=s>2?o[s-2]:void 0,a=s>2?o[2]:void 0,u=s>1?o[s-1]:void 0;for("function"==typeof c?(c=i(c,u,5),s-=2):(c="function"==typeof u?u:void 0,s-=c?1:0),a&&r(o[0],o[1],a)&&(c=s<3?void 0:c,s=1);++e<s;){var l=o[e];l&&t(n,l,c)}return n})}var i=o(32),r=o(38),s=o(48);t.exports=e},function(t,n){function o(t,n){if("function"!=typeof t)throw new TypeError(e);return n=i(void 0===n?t.length-1:+n||0,0),function(){for(var o=arguments,e=-1,r=i(o.length-n,0),s=Array(r);++e<r;)s[e]=o[n+e];switch(n){case 0:return t.call(this,s);case 1:return t.call(this,o[0],s);case 2:return t.call(this,o[0],o[1],s)}var c=Array(n+1);for(e=-1;++e<n;)c[e]=o[e];return c[n]=s,t.apply(this,c)}}var e="Expected a function",i=Math.max;t.exports=o},function(t,n,o){function e(t){var n=++r;return i(t)+n}var i=o(50),r=0;t.exports=e},function(t,n){function o(t){return null==t?"":t+""}t.exports=o}]);
			(function defineMustache(global,factory){if(typeof exports==="object"&&exports&&typeof exports.nodeName!=="string"){factory(exports)}else if(typeof define==="function"&&define.amd){define(["exports"],factory)}else{global.Mustache={};factory(global.Mustache)}})(this,function mustacheFactory(mustache){var objectToString=Object.prototype.toString;var isArray=Array.isArray||function isArrayPolyfill(object){return objectToString.call(object)==="[object Array]"};function isFunction(object){return typeof object==="function"}function typeStr(obj){return isArray(obj)?"array":typeof obj}function escapeRegExp(string){return string.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")}function hasProperty(obj,propName){return obj!=null&&typeof obj==="object"&&propName in obj}var regExpTest=RegExp.prototype.test;function testRegExp(re,string){return regExpTest.call(re,string)}var nonSpaceRe=/\S/;function isWhitespace(string){return!testRegExp(nonSpaceRe,string)}var entityMap={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;","`":"&#x60;","=":"&#x3D;"};function escapeHtml(string){return String(string).replace(/[&<>"'`=\/]/g,function fromEntityMap(s){return entityMap[s]})}var whiteRe=/\s*/;var spaceRe=/\s+/;var equalsRe=/\s*=/;var curlyRe=/\s*\}/;var tagRe=/#|\^|\/|>|\{|&|=|!/;function parseTemplate(template,tags){if(!template)return[];var sections=[];var tokens=[];var spaces=[];var hasTag=false;var nonSpace=false;function stripSpace(){if(hasTag&&!nonSpace){while(spaces.length)delete tokens[spaces.pop()]}else{spaces=[]}hasTag=false;nonSpace=false}var openingTagRe,closingTagRe,closingCurlyRe;function compileTags(tagsToCompile){if(typeof tagsToCompile==="string")tagsToCompile=tagsToCompile.split(spaceRe,2);if(!isArray(tagsToCompile)||tagsToCompile.length!==2)throw new Error("Invalid tags: "+tagsToCompile);openingTagRe=new RegExp(escapeRegExp(tagsToCompile[0])+"\\s*");closingTagRe=new RegExp("\\s*"+escapeRegExp(tagsToCompile[1]));closingCurlyRe=new RegExp("\\s*"+escapeRegExp("}"+tagsToCompile[1]))}compileTags(tags||mustache.tags);var scanner=new Scanner(template);var start,type,value,chr,token,openSection;while(!scanner.eos()){start=scanner.pos;value=scanner.scanUntil(openingTagRe);if(value){for(var i=0,valueLength=value.length;i<valueLength;++i){chr=value.charAt(i);if(isWhitespace(chr)){spaces.push(tokens.length)}else{nonSpace=true}tokens.push(["text",chr,start,start+1]);start+=1;if(chr==="\n")stripSpace()}}if(!scanner.scan(openingTagRe))break;hasTag=true;type=scanner.scan(tagRe)||"name";scanner.scan(whiteRe);if(type==="="){value=scanner.scanUntil(equalsRe);scanner.scan(equalsRe);scanner.scanUntil(closingTagRe)}else if(type==="{"){value=scanner.scanUntil(closingCurlyRe);scanner.scan(curlyRe);scanner.scanUntil(closingTagRe);type="&"}else{value=scanner.scanUntil(closingTagRe)}if(!scanner.scan(closingTagRe))throw new Error("Unclosed tag at "+scanner.pos);token=[type,value,start,scanner.pos];tokens.push(token);if(type==="#"||type==="^"){sections.push(token)}else if(type==="/"){openSection=sections.pop();if(!openSection)throw new Error('Unopened section "'+value+'" at '+start);if(openSection[1]!==value)throw new Error('Unclosed section "'+openSection[1]+'" at '+start)}else if(type==="name"||type==="{"||type==="&"){nonSpace=true}else if(type==="="){compileTags(value)}}openSection=sections.pop();if(openSection)throw new Error('Unclosed section "'+openSection[1]+'" at '+scanner.pos);return nestTokens(squashTokens(tokens))}function squashTokens(tokens){var squashedTokens=[];var token,lastToken;for(var i=0,numTokens=tokens.length;i<numTokens;++i){token=tokens[i];if(token){if(token[0]==="text"&&lastToken&&lastToken[0]==="text"){lastToken[1]+=token[1];lastToken[3]=token[3]}else{squashedTokens.push(token);lastToken=token}}}return squashedTokens}function nestTokens(tokens){var nestedTokens=[];var collector=nestedTokens;var sections=[];var token,section;for(var i=0,numTokens=tokens.length;i<numTokens;++i){token=tokens[i];switch(token[0]){case"#":case"^":collector.push(token);sections.push(token);collector=token[4]=[];break;case"/":section=sections.pop();section[5]=token[2];collector=sections.length>0?sections[sections.length-1][4]:nestedTokens;break;default:collector.push(token)}}return nestedTokens}function Scanner(string){this.string=string;this.tail=string;this.pos=0}Scanner.prototype.eos=function eos(){return this.tail===""};Scanner.prototype.scan=function scan(re){var match=this.tail.match(re);if(!match||match.index!==0)return"";var string=match[0];this.tail=this.tail.substring(string.length);this.pos+=string.length;return string};Scanner.prototype.scanUntil=function scanUntil(re){var index=this.tail.search(re),match;switch(index){case-1:match=this.tail;this.tail="";break;case 0:match="";break;default:match=this.tail.substring(0,index);this.tail=this.tail.substring(index)}this.pos+=match.length;return match};function Context(view,parentContext){this.view=view;this.cache={".":this.view};this.parent=parentContext}Context.prototype.push=function push(view){return new Context(view,this)};Context.prototype.lookup=function lookup(name){var cache=this.cache;var value;if(cache.hasOwnProperty(name)){value=cache[name]}else{var context=this,names,index,lookupHit=false;while(context){if(name.indexOf(".")>0){value=context.view;names=name.split(".");index=0;while(value!=null&&index<names.length){if(index===names.length-1)lookupHit=hasProperty(value,names[index]);value=value[names[index++]]}}else{value=context.view[name];lookupHit=hasProperty(context.view,name)}if(lookupHit)break;context=context.parent}cache[name]=value}if(isFunction(value))value=value.call(this.view);return value};function Writer(){this.cache={}}Writer.prototype.clearCache=function clearCache(){this.cache={}};Writer.prototype.parse=function parse(template,tags){var cache=this.cache;var tokens=cache[template];if(tokens==null)tokens=cache[template]=parseTemplate(template,tags);return tokens};Writer.prototype.render=function render(template,view,partials){var tokens=this.parse(template);var context=view instanceof Context?view:new Context(view);return this.renderTokens(tokens,context,partials,template)};Writer.prototype.renderTokens=function renderTokens(tokens,context,partials,originalTemplate){var buffer="";var token,symbol,value;for(var i=0,numTokens=tokens.length;i<numTokens;++i){value=undefined;token=tokens[i];symbol=token[0];if(symbol==="#")value=this.renderSection(token,context,partials,originalTemplate);else if(symbol==="^")value=this.renderInverted(token,context,partials,originalTemplate);else if(symbol===">")value=this.renderPartial(token,context,partials,originalTemplate);else if(symbol==="&")value=this.unescapedValue(token,context);else if(symbol==="name")value=this.escapedValue(token,context);else if(symbol==="text")value=this.rawValue(token);if(value!==undefined)buffer+=value}return buffer};Writer.prototype.renderSection=function renderSection(token,context,partials,originalTemplate){var self=this;var buffer="";var value=context.lookup(token[1]);function subRender(template){return self.render(template,context,partials)}if(!value)return;if(isArray(value)){for(var j=0,valueLength=value.length;j<valueLength;++j){buffer+=this.renderTokens(token[4],context.push(value[j]),partials,originalTemplate)}}else if(typeof value==="object"||typeof value==="string"||typeof value==="number"){buffer+=this.renderTokens(token[4],context.push(value),partials,originalTemplate)}else if(isFunction(value)){if(typeof originalTemplate!=="string")throw new Error("Cannot use higher-order sections without the original template");value=value.call(context.view,originalTemplate.slice(token[3],token[5]),subRender);if(value!=null)buffer+=value}else{buffer+=this.renderTokens(token[4],context,partials,originalTemplate)}return buffer};Writer.prototype.renderInverted=function renderInverted(token,context,partials,originalTemplate){var value=context.lookup(token[1]);if(!value||isArray(value)&&value.length===0)return this.renderTokens(token[4],context,partials,originalTemplate)};Writer.prototype.renderPartial=function renderPartial(token,context,partials){if(!partials)return;var value=isFunction(partials)?partials(token[1]):partials[token[1]];if(value!=null)return this.renderTokens(this.parse(value),context,partials,value)};Writer.prototype.unescapedValue=function unescapedValue(token,context){var value=context.lookup(token[1]);if(value!=null)return value};Writer.prototype.escapedValue=function escapedValue(token,context){var value=context.lookup(token[1]);if(value!=null)return mustache.escape(value)};Writer.prototype.rawValue=function rawValue(token){return token[1]};mustache.name="mustache.js";mustache.version="2.3.0";mustache.tags=["{{","}}"];var defaultWriter=new Writer;mustache.clearCache=function clearCache(){return defaultWriter.clearCache()};mustache.parse=function parse(template,tags){return defaultWriter.parse(template,tags)};mustache.render=function render(template,view,partials){if(typeof template!=="string"){throw new TypeError('Invalid template! Template should be a "string" '+'but "'+typeStr(template)+'" was given as the first '+"argument for mustache#render(template, view, partials)")}return defaultWriter.render(template,view,partials)};mustache.to_html=function to_html(template,view,partials,send){var result=mustache.render(template,view,partials);if(isFunction(send)){send(result)}else{return result}};mustache.escape=escapeHtml;mustache.Scanner=Scanner;mustache.Context=Context;mustache.Writer=Writer;return mustache});

			(function(){function o(n){var i=e;n&&(e[n]||(e[n]={}),i=e[n]);if(!i.define||!i.define.packaged)t.original=i.define,i.define=t,i.define.packaged=!0;if(!i.require||!i.require.packaged)r.original=i.require,i.require=r,i.require.packaged=!0}var ACE_NAMESPACE = "ace",e=function(){return this}();!e&&typeof window!="undefined"&&(e=window);if(!ACE_NAMESPACE&&typeof requirejs!="undefined")return;var t=function(e,n,r){if(typeof e!="string"){t.original?t.original.apply(this,arguments):(console.error("dropping module because define wasn't a string."),console.trace());return}arguments.length==2&&(r=n),t.modules[e]||(t.payloads[e]=r,t.modules[e]=null)};t.modules={},t.payloads={};var n=function(e,t,n){if(typeof t=="string"){var i=s(e,t);if(i!=undefined)return n&&n(),i}else if(Object.prototype.toString.call(t)==="[object Array]"){var o=[];for(var u=0,a=t.length;u<a;++u){var f=s(e,t[u]);if(f==undefined&&r.original)return;o.push(f)}return n&&n.apply(null,o)||!0}},r=function(e,t){var i=n("",e,t);return i==undefined&&r.original?r.original.apply(this,arguments):i},i=function(e,t){if(t.indexOf("!")!==-1){var n=t.split("!");return i(e,n[0])+"!"+i(e,n[1])}if(t.charAt(0)=="."){var r=e.split("/").slice(0,-1).join("/");t=r+"/"+t;while(t.indexOf(".")!==-1&&s!=t){var s=t;t=t.replace(/\/\.\//,"/").replace(/[^\/]+\/\.\.\//,"")}}return t},s=function(e,r){r=i(e,r);var s=t.modules[r];if(!s){s=t.payloads[r];if(typeof s=="function"){var o={},u={id:r,uri:"",exports:o,packaged:!0},a=function(e,t){return n(r,e,t)},f=s(a,o,u);o=f||u.exports,t.modules[r]=o,delete t.payloads[r]}s=t.modules[r]=o||s}return s};o(ACE_NAMESPACE)})(),ace.define("ace/lib/regexp",["require","exports","module"],function(e,t,n){"use strict";function o(e){return(e.global?"g":"")+(e.ignoreCase?"i":"")+(e.multiline?"m":"")+(e.extended?"x":"")+(e.sticky?"y":"")}function u(e,t,n){if(Array.prototype.indexOf)return e.indexOf(t,n);for(var r=n||0;r<e.length;r++)if(e[r]===t)return r;return-1}var r={exec:RegExp.prototype.exec,test:RegExp.prototype.test,match:String.prototype.match,replace:String.prototype.replace,split:String.prototype.split},i=r.exec.call(/()??/,"")[1]===undefined,s=function(){var e=/^/g;return r.test.call(e,""),!e.lastIndex}();if(s&&i)return;RegExp.prototype.exec=function(e){var t=r.exec.apply(this,arguments),n,a;if(typeof e=="string"&&t){!i&&t.length>1&&u(t,"")>-1&&(a=RegExp(this.source,r.replace.call(o(this),"g","")),r.replace.call(e.slice(t.index),a,function(){for(var e=1;e<arguments.length-2;e++)arguments[e]===undefined&&(t[e]=undefined)}));if(this._xregexp&&this._xregexp.captureNames)for(var f=1;f<t.length;f++)n=this._xregexp.captureNames[f-1],n&&(t[n]=t[f]);!s&&this.global&&!t[0].length&&this.lastIndex>t.index&&this.lastIndex--}return t},s||(RegExp.prototype.test=function(e){var t=r.exec.call(this,e);return t&&this.global&&!t[0].length&&this.lastIndex>t.index&&this.lastIndex--,!!t})}),ace.define("ace/lib/es5-shim",["require","exports","module"],function(e,t,n){function r(){}function w(e){try{return Object.defineProperty(e,"sentinel",{}),"sentinel"in e}catch(t){}}function H(e){return e=+e,e!==e?e=0:e!==0&&e!==1/0&&e!==-1/0&&(e=(e>0||-1)*Math.floor(Math.abs(e))),e}function B(e){var t=typeof e;return e===null||t==="undefined"||t==="boolean"||t==="number"||t==="string"}function j(e){var t,n,r;if(B(e))return e;n=e.valueOf;if(typeof n=="function"){t=n.call(e);if(B(t))return t}r=e.toString;if(typeof r=="function"){t=r.call(e);if(B(t))return t}throw new TypeError}Function.prototype.bind||(Function.prototype.bind=function(t){var n=this;if(typeof n!="function")throw new TypeError("Function.prototype.bind called on incompatible "+n);var i=u.call(arguments,1),s=function(){if(this instanceof s){var e=n.apply(this,i.concat(u.call(arguments)));return Object(e)===e?e:this}return n.apply(t,i.concat(u.call(arguments)))};return n.prototype&&(r.prototype=n.prototype,s.prototype=new r,r.prototype=null),s});var i=Function.prototype.call,s=Array.prototype,o=Object.prototype,u=s.slice,a=i.bind(o.toString),f=i.bind(o.hasOwnProperty),l,c,h,p,d;if(d=f(o,"__defineGetter__"))l=i.bind(o.__defineGetter__),c=i.bind(o.__defineSetter__),h=i.bind(o.__lookupGetter__),p=i.bind(o.__lookupSetter__);if([1,2].splice(0).length!=2)if(!function(){function e(e){var t=new Array(e+2);return t[0]=t[1]=0,t}var t=[],n;t.splice.apply(t,e(20)),t.splice.apply(t,e(26)),n=t.length,t.splice(5,0,"XXX"),n+1==t.length;if(n+1==t.length)return!0}())Array.prototype.splice=function(e,t){var n=this.length;e>0?e>n&&(e=n):e==void 0?e=0:e<0&&(e=Math.max(n+e,0)),e+t<n||(t=n-e);var r=this.slice(e,e+t),i=u.call(arguments,2),s=i.length;if(e===n)s&&this.push.apply(this,i);else{var o=Math.min(t,n-e),a=e+o,f=a+s-o,l=n-a,c=n-o;if(f<a)for(var h=0;h<l;++h)this[f+h]=this[a+h];else if(f>a)for(h=l;h--;)this[f+h]=this[a+h];if(s&&e===c)this.length=c,this.push.apply(this,i);else{this.length=c+s;for(h=0;h<s;++h)this[e+h]=i[h]}}return r};else{var v=Array.prototype.splice;Array.prototype.splice=function(e,t){return arguments.length?v.apply(this,[e===void 0?0:e,t===void 0?this.length-e:t].concat(u.call(arguments,2))):[]}}Array.isArray||(Array.isArray=function(t){return a(t)=="[object Array]"});var m=Object("a"),g=m[0]!="a"||!(0 in m);Array.prototype.forEach||(Array.prototype.forEach=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=arguments[1],s=-1,o=r.length>>>0;if(a(t)!="[object Function]")throw new TypeError;while(++s<o)s in r&&t.call(i,r[s],s,n)}),Array.prototype.map||(Array.prototype.map=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=r.length>>>0,s=Array(i),o=arguments[1];if(a(t)!="[object Function]")throw new TypeError(t+" is not a function");for(var u=0;u<i;u++)u in r&&(s[u]=t.call(o,r[u],u,n));return s}),Array.prototype.filter||(Array.prototype.filter=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=r.length>>>0,s=[],o,u=arguments[1];if(a(t)!="[object Function]")throw new TypeError(t+" is not a function");for(var f=0;f<i;f++)f in r&&(o=r[f],t.call(u,o,f,n)&&s.push(o));return s}),Array.prototype.every||(Array.prototype.every=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=r.length>>>0,s=arguments[1];if(a(t)!="[object Function]")throw new TypeError(t+" is not a function");for(var o=0;o<i;o++)if(o in r&&!t.call(s,r[o],o,n))return!1;return!0}),Array.prototype.some||(Array.prototype.some=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=r.length>>>0,s=arguments[1];if(a(t)!="[object Function]")throw new TypeError(t+" is not a function");for(var o=0;o<i;o++)if(o in r&&t.call(s,r[o],o,n))return!0;return!1}),Array.prototype.reduce||(Array.prototype.reduce=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=r.length>>>0;if(a(t)!="[object Function]")throw new TypeError(t+" is not a function");if(!i&&arguments.length==1)throw new TypeError("reduce of empty array with no initial value");var s=0,o;if(arguments.length>=2)o=arguments[1];else do{if(s in r){o=r[s++];break}if(++s>=i)throw new TypeError("reduce of empty array with no initial value")}while(!0);for(;s<i;s++)s in r&&(o=t.call(void 0,o,r[s],s,n));return o}),Array.prototype.reduceRight||(Array.prototype.reduceRight=function(t){var n=F(this),r=g&&a(this)=="[object String]"?this.split(""):n,i=r.length>>>0;if(a(t)!="[object Function]")throw new TypeError(t+" is not a function");if(!i&&arguments.length==1)throw new TypeError("reduceRight of empty array with no initial value");var s,o=i-1;if(arguments.length>=2)s=arguments[1];else do{if(o in r){s=r[o--];break}if(--o<0)throw new TypeError("reduceRight of empty array with no initial value")}while(!0);do o in this&&(s=t.call(void 0,s,r[o],o,n));while(o--);return s});if(!Array.prototype.indexOf||[0,1].indexOf(1,2)!=-1)Array.prototype.indexOf=function(t){var n=g&&a(this)=="[object String]"?this.split(""):F(this),r=n.length>>>0;if(!r)return-1;var i=0;arguments.length>1&&(i=H(arguments[1])),i=i>=0?i:Math.max(0,r+i);for(;i<r;i++)if(i in n&&n[i]===t)return i;return-1};if(!Array.prototype.lastIndexOf||[0,1].lastIndexOf(0,-3)!=-1)Array.prototype.lastIndexOf=function(t){var n=g&&a(this)=="[object String]"?this.split(""):F(this),r=n.length>>>0;if(!r)return-1;var i=r-1;arguments.length>1&&(i=Math.min(i,H(arguments[1]))),i=i>=0?i:r-Math.abs(i);for(;i>=0;i--)if(i in n&&t===n[i])return i;return-1};Object.getPrototypeOf||(Object.getPrototypeOf=function(t){return t.__proto__||(t.constructor?t.constructor.prototype:o)});if(!Object.getOwnPropertyDescriptor){var y="Object.getOwnPropertyDescriptor called on a non-object: ";Object.getOwnPropertyDescriptor=function(t,n){if(typeof t!="object"&&typeof t!="function"||t===null)throw new TypeError(y+t);if(!f(t,n))return;var r,i,s;r={enumerable:!0,configurable:!0};if(d){var u=t.__proto__;t.__proto__=o;var i=h(t,n),s=p(t,n);t.__proto__=u;if(i||s)return i&&(r.get=i),s&&(r.set=s),r}return r.value=t[n],r}}Object.getOwnPropertyNames||(Object.getOwnPropertyNames=function(t){return Object.keys(t)});if(!Object.create){var b;Object.prototype.__proto__===null?b=function(){return{__proto__:null}}:b=function(){var e={};for(var t in e)e[t]=null;return e.constructor=e.hasOwnProperty=e.propertyIsEnumerable=e.isPrototypeOf=e.toLocaleString=e.toString=e.valueOf=e.__proto__=null,e},Object.create=function(t,n){var r;if(t===null)r=b();else{if(typeof t!="object")throw new TypeError("typeof prototype["+typeof t+"] != 'object'");var i=function(){};i.prototype=t,r=new i,r.__proto__=t}return n!==void 0&&Object.defineProperties(r,n),r}}if(Object.defineProperty){var E=w({}),S=typeof document=="undefined"||w(document.createElement("div"));if(!E||!S)var x=Object.defineProperty}if(!Object.defineProperty||x){var T="Property description must be an object: ",N="Object.defineProperty called on non-object: ",C="getters & setters can not be defined on this javascript engine";Object.defineProperty=function(t,n,r){if(typeof t!="object"&&typeof t!="function"||t===null)throw new TypeError(N+t);if(typeof r!="object"&&typeof r!="function"||r===null)throw new TypeError(T+r);if(x)try{return x.call(Object,t,n,r)}catch(i){}if(f(r,"value"))if(d&&(h(t,n)||p(t,n))){var s=t.__proto__;t.__proto__=o,delete t[n],t[n]=r.value,t.__proto__=s}else t[n]=r.value;else{if(!d)throw new TypeError(C);f(r,"get")&&l(t,n,r.get),f(r,"set")&&c(t,n,r.set)}return t}}Object.defineProperties||(Object.defineProperties=function(t,n){for(var r in n)f(n,r)&&Object.defineProperty(t,r,n[r]);return t}),Object.seal||(Object.seal=function(t){return t}),Object.freeze||(Object.freeze=function(t){return t});try{Object.freeze(function(){})}catch(k){Object.freeze=function(t){return function(n){return typeof n=="function"?n:t(n)}}(Object.freeze)}Object.preventExtensions||(Object.preventExtensions=function(t){return t}),Object.isSealed||(Object.isSealed=function(t){return!1}),Object.isFrozen||(Object.isFrozen=function(t){return!1}),Object.isExtensible||(Object.isExtensible=function(t){if(Object(t)===t)throw new TypeError;var n="";while(f(t,n))n+="?";t[n]=!0;var r=f(t,n);return delete t[n],r});if(!Object.keys){var L=!0,A=["toString","toLocaleString","valueOf","hasOwnProperty","isPrototypeOf","propertyIsEnumerable","constructor"],O=A.length;for(var M in{toString:null})L=!1;Object.keys=function I(e){if(typeof e!="object"&&typeof e!="function"||e===null)throw new TypeError("Object.keys called on a non-object");var I=[];for(var t in e)f(e,t)&&I.push(t);if(L)for(var n=0,r=O;n<r;n++){var i=A[n];f(e,i)&&I.push(i)}return I}}Date.now||(Date.now=function(){return(new Date).getTime()});var _="	\n\x0b\f\r \u00a0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029\ufeff";if(!String.prototype.trim||_.trim()){_="["+_+"]";var D=new RegExp("^"+_+_+"*"),P=new RegExp(_+_+"*$");String.prototype.trim=function(){return String(this).replace(D,"").replace(P,"")}}var F=function(e){if(e==null)throw new TypeError("can't convert "+e+" to object");return Object(e)}}),ace.define("ace/lib/fixoldbrowsers",["require","exports","module","ace/lib/regexp","ace/lib/es5-shim"],function(e,t,n){"use strict";e("./regexp"),e("./es5-shim")}),ace.define("ace/lib/dom",["require","exports","module"],function(e,t,n){"use strict";var r="http://www.w3.org/1999/xhtml";t.buildDom=function i(e,t,n){if(typeof e=="string"&&e){var r=document.createTextNode(e);return t&&t.appendChild(r),r}if(!Array.isArray(e))return e;if(typeof e[0]!="string"||!e[0]){var s=[];for(var o=0;o<e.length;o++){var u=i(e[o],t,n);u&&s.push(u)}return s}var a=document.createElement(e[0]),f=e[1],l=1;f&&typeof f=="object"&&!Array.isArray(f)&&(l=2);for(var o=l;o<e.length;o++)i(e[o],a,n);return l==2&&Object.keys(f).forEach(function(e){var t=f[e];e==="class"?a.className=Array.isArray(t)?t.join(" "):t:typeof t=="function"||e=="value"?a[e]=t:e==="ref"?n&&(n[t]=a):t!=null&&a.setAttribute(e,t)}),t&&t.appendChild(a),a},t.getDocumentHead=function(e){return e||(e=document),e.head||e.getElementsByTagName("head")[0]||e.documentElement},t.createElement=function(e,t){return document.createElementNS?document.createElementNS(t||r,e):document.createElement(e)},t.hasCssClass=function(e,t){var n=(e.className+"").split(/\s+/g);return n.indexOf(t)!==-1},t.addCssClass=function(e,n){t.hasCssClass(e,n)||(e.className+=" "+n)},t.removeCssClass=function(e,t){var n=e.className.split(/\s+/g);for(;;){var r=n.indexOf(t);if(r==-1)break;n.splice(r,1)}e.className=n.join(" ")},t.toggleCssClass=function(e,t){var n=e.className.split(/\s+/g),r=!0;for(;;){var i=n.indexOf(t);if(i==-1)break;r=!1,n.splice(i,1)}return r&&n.push(t),e.className=n.join(" "),r},t.setCssClass=function(e,n,r){r?t.addCssClass(e,n):t.removeCssClass(e,n)},t.hasCssString=function(e,t){var n=0,r;t=t||document;if(t.createStyleSheet&&(r=t.styleSheets)){while(n<r.length)if(r[n++].owningElement.id===e)return!0}else if(r=t.getElementsByTagName("style"))while(n<r.length)if(r[n++].id===e)return!0;return!1},t.importCssString=function(n,r,i){i=i||document;if(r&&t.hasCssString(r,i))return null;var s;r&&(n+="\n/*# sourceURL=ace/css/"+r+" */"),i.createStyleSheet?(s=i.createStyleSheet(),s.cssText=n,r&&(s.owningElement.id=r)):(s=t.createElement("style"),s.appendChild(i.createTextNode(n)),r&&(s.id=r),t.getDocumentHead(i).appendChild(s))},t.importCssStylsheet=function(e,n){if(n.createStyleSheet)n.createStyleSheet(e);else{var r=t.createElement("link");r.rel="stylesheet",r.href=e,t.getDocumentHead(n).appendChild(r)}},t.getInnerWidth=function(e){return parseInt(t.computedStyle(e,"paddingLeft"),10)+parseInt(t.computedStyle(e,"paddingRight"),10)+e.clientWidth},t.getInnerHeight=function(e){return parseInt(t.computedStyle(e,"paddingTop"),10)+parseInt(t.computedStyle(e,"paddingBottom"),10)+e.clientHeight},t.scrollbarWidth=function(e){var n=t.createElement("ace_inner");n.style.width="100%",n.style.minWidth="0px",n.style.height="200px",n.style.display="block";var r=t.createElement("ace_outer"),i=r.style;i.position="absolute",i.left="-10000px",i.overflow="hidden",i.width="200px",i.minWidth="0px",i.height="150px",i.display="block",r.appendChild(n);var s=e.documentElement;s.appendChild(r);var o=n.offsetWidth;i.overflow="scroll";var u=n.offsetWidth;return o==u&&(u=r.clientWidth),s.removeChild(r),o-u};if(typeof document=="undefined"){t.importCssString=function(){};return}window.pageYOffset!==undefined?(t.getPageScrollTop=function(){return window.pageYOffset},t.getPageScrollLeft=function(){return window.pageXOffset}):(t.getPageScrollTop=function(){return document.body.scrollTop},t.getPageScrollLeft=function(){return document.body.scrollLeft}),window.getComputedStyle?t.computedStyle=function(e,t){return t?(window.getComputedStyle(e,"")||{})[t]||"":window.getComputedStyle(e,"")||{}}:t.computedStyle=function(e,t){return t?e.currentStyle[t]:e.currentStyle},t.setInnerHtml=function(e,t){var n=e.cloneNode(!1);return n.innerHTML=t,e.parentNode.replaceChild(n,e),n},"textContent"in document.documentElement?(t.setInnerText=function(e,t){e.textContent=t},t.getInnerText=function(e){return e.textContent}):(t.setInnerText=function(e,t){e.innerText=t},t.getInnerText=function(e){return e.innerText}),t.getParentWindow=function(e){return e.defaultView||e.parentWindow}}),ace.define("ace/lib/oop",["require","exports","module"],function(e,t,n){"use strict";t.inherits=function(e,t){e.super_=t,e.prototype=Object.create(t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}})},t.mixin=function(e,t){for(var n in t)e[n]=t[n];return e},t.implement=function(e,n){t.mixin(e,n)}}),ace.define("ace/lib/keys",["require","exports","module","ace/lib/fixoldbrowsers","ace/lib/oop"],function(e,t,n){"use strict";e("./fixoldbrowsers");var r=e("./oop"),i=function(){var e={MODIFIER_KEYS:{16:"Shift",17:"Ctrl",18:"Alt",224:"Meta"},KEY_MODS:{ctrl:1,alt:2,option:2,shift:4,"super":8,meta:8,command:8,cmd:8},FUNCTION_KEYS:{8:"Backspace",9:"Tab",13:"Return",19:"Pause",27:"Esc",32:"Space",33:"PageUp",34:"PageDown",35:"End",36:"Home",37:"Left",38:"Up",39:"Right",40:"Down",44:"Print",45:"Insert",46:"Delete",96:"Numpad0",97:"Numpad1",98:"Numpad2",99:"Numpad3",100:"Numpad4",101:"Numpad5",102:"Numpad6",103:"Numpad7",104:"Numpad8",105:"Numpad9","-13":"NumpadEnter",112:"F1",113:"F2",114:"F3",115:"F4",116:"F5",117:"F6",118:"F7",119:"F8",120:"F9",121:"F10",122:"F11",123:"F12",144:"Numlock",145:"Scrolllock"},PRINTABLE_KEYS:{32:" ",48:"0",49:"1",50:"2",51:"3",52:"4",53:"5",54:"6",55:"7",56:"8",57:"9",59:";",61:"=",65:"a",66:"b",67:"c",68:"d",69:"e",70:"f",71:"g",72:"h",73:"i",74:"j",75:"k",76:"l",77:"m",78:"n",79:"o",80:"p",81:"q",82:"r",83:"s",84:"t",85:"u",86:"v",87:"w",88:"x",89:"y",90:"z",107:"+",109:"-",110:".",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'",111:"/",106:"*"}},t,n;for(n in e.FUNCTION_KEYS)t=e.FUNCTION_KEYS[n].toLowerCase(),e[t]=parseInt(n,10);for(n in e.PRINTABLE_KEYS)t=e.PRINTABLE_KEYS[n].toLowerCase(),e[t]=parseInt(n,10);return r.mixin(e,e.MODIFIER_KEYS),r.mixin(e,e.PRINTABLE_KEYS),r.mixin(e,e.FUNCTION_KEYS),e.enter=e["return"],e.escape=e.esc,e.del=e["delete"],e[173]="-",function(){var t=["cmd","ctrl","alt","shift"];for(var n=Math.pow(2,t.length);n--;)e.KEY_MODS[n]=t.filter(function(t){return n&e.KEY_MODS[t]}).join("-")+"-"}(),e.KEY_MODS[0]="",e.KEY_MODS[-1]="input-",e}();r.mixin(t,i),t.keyCodeToString=function(e){var t=i[e];return typeof t!="string"&&(t=String.fromCharCode(e)),t.toLowerCase()}}),ace.define("ace/lib/useragent",["require","exports","module"],function(e,t,n){"use strict";t.OS={LINUX:"LINUX",MAC:"MAC",WINDOWS:"WINDOWS"},t.getOS=function(){return t.isMac?t.OS.MAC:t.isLinux?t.OS.LINUX:t.OS.WINDOWS};if(typeof navigator!="object")return;var r=(navigator.platform.match(/mac|win|linux/i)||["other"])[0].toLowerCase(),i=navigator.userAgent;t.isWin=r=="win",t.isMac=r=="mac",t.isLinux=r=="linux",t.isIE=navigator.appName=="Microsoft Internet Explorer"||navigator.appName.indexOf("MSAppHost")>=0?parseFloat((i.match(/(?:MSIE |Trident\/[0-9]+[\.0-9]+;.*rv:)([0-9]+[\.0-9]+)/)||[])[1]):parseFloat((i.match(/(?:Trident\/[0-9]+[\.0-9]+;.*rv:)([0-9]+[\.0-9]+)/)||[])[1]),t.isOldIE=t.isIE&&t.isIE<9,t.isGecko=t.isMozilla=i.match(/ Gecko\/\d+/),t.isOpera=window.opera&&Object.prototype.toString.call(window.opera)=="[object Opera]",t.isWebKit=parseFloat(i.split("WebKit/")[1])||undefined,t.isChrome=parseFloat(i.split(" Chrome/")[1])||undefined,t.isAIR=i.indexOf("AdobeAIR")>=0,t.isIPad=i.indexOf("iPad")>=0,t.isChromeOS=i.indexOf(" CrOS ")>=0,t.isIOS=/iPad|iPhone|iPod/.test(i)&&!window.MSStream,t.isIOS&&(t.isMac=!0)}),ace.define("ace/lib/event",["require","exports","module","ace/lib/keys","ace/lib/useragent"],function(e,t,n){"use strict";function a(e,t,n){var a=u(t);if(!i.isMac&&s){t.getModifierState&&(t.getModifierState("OS")||t.getModifierState("Win"))&&(a|=8);if(s.altGr){if((3&a)==3)return;s.altGr=0}if(n===18||n===17){var f="location"in t?t.location:t.keyLocation;if(n===17&&f===1)s[n]==1&&(o=t.timeStamp);else if(n===18&&a===3&&f===2){var l=t.timeStamp-o;l<50&&(s.altGr=!0)}}}n in r.MODIFIER_KEYS&&(n=-1),a&8&&n>=91&&n<=93&&(n=-1);if(!a&&n===13){var f="location"in t?t.location:t.keyLocation;if(f===3){e(t,a,-n);if(t.defaultPrevented)return}}if(i.isChromeOS&&a&8){e(t,a,n);if(t.defaultPrevented)return;a&=-9}return!!a||n in r.FUNCTION_KEYS||n in r.PRINTABLE_KEYS?e(t,a,n):!1}function f(){s=Object.create(null)}var r=e("./keys"),i=e("./useragent"),s=null,o=0;t.addListener=function(e,t,n){if(e.addEventListener)return e.addEventListener(t,n,!1);if(e.attachEvent){var r=function(){n.call(e,window.event)};n._wrapper=r,e.attachEvent("on"+t,r)}},t.removeListener=function(e,t,n){if(e.removeEventListener)return e.removeEventListener(t,n,!1);e.detachEvent&&e.detachEvent("on"+t,n._wrapper||n)},t.stopEvent=function(e){return t.stopPropagation(e),t.preventDefault(e),!1},t.stopPropagation=function(e){e.stopPropagation?e.stopPropagation():e.cancelBubble=!0},t.preventDefault=function(e){e.preventDefault?e.preventDefault():e.returnValue=!1},t.getButton=function(e){return e.type=="dblclick"?0:e.type=="contextmenu"||i.isMac&&e.ctrlKey&&!e.altKey&&!e.shiftKey?2:e.preventDefault?e.button:{1:0,2:2,4:1}[e.button]},t.capture=function(e,n,r){function i(e){n&&n(e),r&&r(e),t.removeListener(document,"mousemove",n,!0),t.removeListener(document,"mouseup",i,!0),t.removeListener(document,"dragstart",i,!0)}return t.addListener(document,"mousemove",n,!0),t.addListener(document,"mouseup",i,!0),t.addListener(document,"dragstart",i,!0),i},t.addTouchMoveListener=function(e,n){var r,i;t.addListener(e,"touchstart",function(e){var t=e.touches,n=t[0];r=n.clientX,i=n.clientY}),t.addListener(e,"touchmove",function(e){var t=e.touches;if(t.length>1)return;var s=t[0];e.wheelX=r-s.clientX,e.wheelY=i-s.clientY,r=s.clientX,i=s.clientY,n(e)})},t.addMouseWheelListener=function(e,n){"onmousewheel"in e?t.addListener(e,"mousewheel",function(e){var t=8;e.wheelDeltaX!==undefined?(e.wheelX=-e.wheelDeltaX/t,e.wheelY=-e.wheelDeltaY/t):(e.wheelX=0,e.wheelY=-e.wheelDelta/t),n(e)}):"onwheel"in e?t.addListener(e,"wheel",function(e){var t=.35;switch(e.deltaMode){case e.DOM_DELTA_PIXEL:e.wheelX=e.deltaX*t||0,e.wheelY=e.deltaY*t||0;break;case e.DOM_DELTA_LINE:case e.DOM_DELTA_PAGE:e.wheelX=(e.deltaX||0)*5,e.wheelY=(e.deltaY||0)*5}n(e)}):t.addListener(e,"DOMMouseScroll",function(e){e.axis&&e.axis==e.HORIZONTAL_AXIS?(e.wheelX=(e.detail||0)*5,e.wheelY=0):(e.wheelX=0,e.wheelY=(e.detail||0)*5),n(e)})},t.addMultiMouseDownListener=function(e,n,r,s){function c(e){t.getButton(e)!==0?o=0:e.detail>1?(o++,o>4&&(o=1)):o=1;if(i.isIE){var c=Math.abs(e.clientX-u)>5||Math.abs(e.clientY-a)>5;if(!f||c)o=1;f&&clearTimeout(f),f=setTimeout(function(){f=null},n[o-1]||600),o==1&&(u=e.clientX,a=e.clientY)}e._clicks=o,r[s]("mousedown",e);if(o>4)o=0;else if(o>1)return r[s](l[o],e)}function h(e){o=2,f&&clearTimeout(f),f=setTimeout(function(){f=null},n[o-1]||600),r[s]("mousedown",e),r[s](l[o],e)}var o=0,u,a,f,l={2:"dblclick",3:"tripleclick",4:"quadclick"};Array.isArray(e)||(e=[e]),e.forEach(function(e){t.addListener(e,"mousedown",c),i.isOldIE&&t.addListener(e,"dblclick",h)})};var u=!i.isMac||!i.isOpera||"KeyboardEvent"in window?function(e){return 0|(e.ctrlKey?1:0)|(e.altKey?2:0)|(e.shiftKey?4:0)|(e.metaKey?8:0)}:function(e){return 0|(e.metaKey?1:0)|(e.altKey?2:0)|(e.shiftKey?4:0)|(e.ctrlKey?8:0)};t.getModifierString=function(e){return r.KEY_MODS[u(e)]},t.addCommandKeyListener=function(e,n){var r=t.addListener;if(i.isOldGecko||i.isOpera&&!("KeyboardEvent"in window)){var o=null;r(e,"keydown",function(e){o=e.keyCode}),r(e,"keypress",function(e){return a(n,e,o)})}else{var u=null;r(e,"keydown",function(e){s[e.keyCode]=(s[e.keyCode]||0)+1;var t=a(n,e,e.keyCode);return u=e.defaultPrevented,t}),r(e,"keypress",function(e){u&&(e.ctrlKey||e.altKey||e.shiftKey||e.metaKey)&&(t.stopEvent(e),u=null)}),r(e,"keyup",function(e){s[e.keyCode]=null}),s||(f(),r(window,"focus",f))}};if(typeof window=="object"&&window.postMessage&&!i.isOldIE){var l=1;t.nextTick=function(e,n){n=n||window;var r="zero-timeout-message-"+l;t.addListener(n,"message",function i(s){s.data==r&&(t.stopPropagation(s),t.removeListener(n,"message",i),e())}),n.postMessage(r,"*")}}t.nextFrame=typeof window=="object"&&(window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||window.msRequestAnimationFrame||window.oRequestAnimationFrame),t.nextFrame?t.nextFrame=t.nextFrame.bind(window):t.nextFrame=function(e){setTimeout(e,17)}}),ace.define("ace/range",["require","exports","module"],function(e,t,n){"use strict";var r=function(e,t){return e.row-t.row||e.column-t.column},i=function(e,t,n,r){this.start={row:e,column:t},this.end={row:n,column:r}};(function(){this.isEqual=function(e){return this.start.row===e.start.row&&this.end.row===e.end.row&&this.start.column===e.start.column&&this.end.column===e.end.column},this.toString=function(){return"Range: ["+this.start.row+"/"+this.start.column+"] -> ["+this.end.row+"/"+this.end.column+"]"},this.contains=function(e,t){return this.compare(e,t)==0},this.compareRange=function(e){var t,n=e.end,r=e.start;return t=this.compare(n.row,n.column),t==1?(t=this.compare(r.row,r.column),t==1?2:t==0?1:0):t==-1?-2:(t=this.compare(r.row,r.column),t==-1?-1:t==1?42:0)},this.comparePoint=function(e){return this.compare(e.row,e.column)},this.containsRange=function(e){return this.comparePoint(e.start)==0&&this.comparePoint(e.end)==0},this.intersects=function(e){var t=this.compareRange(e);return t==-1||t==0||t==1},this.isEnd=function(e,t){return this.end.row==e&&this.end.column==t},this.isStart=function(e,t){return this.start.row==e&&this.start.column==t},this.setStart=function(e,t){typeof e=="object"?(this.start.column=e.column,this.start.row=e.row):(this.start.row=e,this.start.column=t)},this.setEnd=function(e,t){typeof e=="object"?(this.end.column=e.column,this.end.row=e.row):(this.end.row=e,this.end.column=t)},this.inside=function(e,t){return this.compare(e,t)==0?this.isEnd(e,t)||this.isStart(e,t)?!1:!0:!1},this.insideStart=function(e,t){return this.compare(e,t)==0?this.isEnd(e,t)?!1:!0:!1},this.insideEnd=function(e,t){return this.compare(e,t)==0?this.isStart(e,t)?!1:!0:!1},this.compare=function(e,t){return!this.isMultiLine()&&e===this.start.row?t<this.start.column?-1:t>this.end.column?1:0:e<this.start.row?-1:e>this.end.row?1:this.start.row===e?t>=this.start.column?0:-1:this.end.row===e?t<=this.end.column?0:1:0},this.compareStart=function(e,t){return this.start.row==e&&this.start.column==t?-1:this.compare(e,t)},this.compareEnd=function(e,t){return this.end.row==e&&this.end.column==t?1:this.compare(e,t)},this.compareInside=function(e,t){return this.end.row==e&&this.end.column==t?1:this.start.row==e&&this.start.column==t?-1:this.compare(e,t)},this.clipRows=function(e,t){if(this.end.row>t)var n={row:t+1,column:0};else if(this.end.row<e)var n={row:e,column:0};if(this.start.row>t)var r={row:t+1,column:0};else if(this.start.row<e)var r={row:e,column:0};return i.fromPoints(r||this.start,n||this.end)},this.extend=function(e,t){var n=this.compare(e,t);if(n==0)return this;if(n==-1)var r={row:e,column:t};else var s={row:e,column:t};return i.fromPoints(r||this.start,s||this.end)},this.isEmpty=function(){return this.start.row===this.end.row&&this.start.column===this.end.column},this.isMultiLine=function(){return this.start.row!==this.end.row},this.clone=function(){return i.fromPoints(this.start,this.end)},this.collapseRows=function(){return this.end.column==0?new i(this.start.row,0,Math.max(this.start.row,this.end.row-1),0):new i(this.start.row,0,this.end.row,0)},this.toScreenRange=function(e){var t=e.documentToScreenPosition(this.start),n=e.documentToScreenPosition(this.end);return new i(t.row,t.column,n.row,n.column)},this.moveBy=function(e,t){this.start.row+=e,this.start.column+=t,this.end.row+=e,this.end.column+=t}}).call(i.prototype),i.fromPoints=function(e,t){return new i(e.row,e.column,t.row,t.column)},i.comparePoints=r,i.comparePoints=function(e,t){return e.row-t.row||e.column-t.column},t.Range=i}),ace.define("ace/lib/lang",["require","exports","module"],function(e,t,n){"use strict";t.last=function(e){return e[e.length-1]},t.stringReverse=function(e){return e.split("").reverse().join("")},t.stringRepeat=function(e,t){var n="";while(t>0){t&1&&(n+=e);if(t>>=1)e+=e}return n};var r=/^\s\s*/,i=/\s\s*$/;t.stringTrimLeft=function(e){return e.replace(r,"")},t.stringTrimRight=function(e){return e.replace(i,"")},t.copyObject=function(e){var t={};for(var n in e)t[n]=e[n];return t},t.copyArray=function(e){var t=[];for(var n=0,r=e.length;n<r;n++)e[n]&&typeof e[n]=="object"?t[n]=this.copyObject(e[n]):t[n]=e[n];return t},t.deepCopy=function s(e){if(typeof e!="object"||!e)return e;var t;if(Array.isArray(e)){t=[];for(var n=0;n<e.length;n++)t[n]=s(e[n]);return t}if(Object.prototype.toString.call(e)!=="[object Object]")return e;t={};for(var n in e)t[n]=s(e[n]);return t},t.arrayToMap=function(e){var t={};for(var n=0;n<e.length;n++)t[e[n]]=1;return t},t.createMap=function(e){var t=Object.create(null);for(var n in e)t[n]=e[n];return t},t.arrayRemove=function(e,t){for(var n=0;n<=e.length;n++)t===e[n]&&e.splice(n,1)},t.escapeRegExp=function(e){return e.replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1")},t.escapeHTML=function(e){return e.replace(/&/g,"&#38;").replace(/"/g,"&#34;").replace(/'/g,"&#39;").replace(/</g,"&#60;")},t.getMatchOffsets=function(e,t){var n=[];return e.replace(t,function(e){n.push({offset:arguments[arguments.length-2],length:e.length})}),n},t.deferredCall=function(e){var t=null,n=function(){t=null,e()},r=function(e){return r.cancel(),t=setTimeout(n,e||0),r};return r.schedule=r,r.call=function(){return this.cancel(),e(),r},r.cancel=function(){return clearTimeout(t),t=null,r},r.isPending=function(){return t},r},t.delayedCall=function(e,t){var n=null,r=function(){n=null,e()},i=function(e){n==null&&(n=setTimeout(r,e||t))};return i.delay=function(e){n&&clearTimeout(n),n=setTimeout(r,e||t)},i.schedule=i,i.call=function(){this.cancel(),e()},i.cancel=function(){n&&clearTimeout(n),n=null},i.isPending=function(){return n},i}}),ace.define("ace/keyboard/textinput_ios",["require","exports","module","ace/lib/event","ace/lib/useragent","ace/lib/dom","ace/lib/lang","ace/lib/keys"],function(e,t,n){"use strict";var r=e("../lib/event"),i=e("../lib/useragent"),s=e("../lib/dom"),o=e("../lib/lang"),u=e("../lib/keys"),a=u.KEY_MODS,f=i.isChrome<18,l=i.isIE,c=function(e,t){function x(e){if(m)return;m=!0;if(k)t=0,n=e?0:c.value.length-1;else var t=4,n=5;try{c.setSelectionRange(t,n)}catch(r){}m=!1}function T(){if(m)return;c.value=h,i.isWebKit&&S.schedule()}function R(){clearTimeout(q),q=setTimeout(function(){g&&(c.style.cssText=g,g=""),t.renderer.$keepTextAreaAtCursor==null&&(t.renderer.$keepTextAreaAtCursor=!0,t.renderer.$moveTextAreaToCursor())},0)}var n=this,c=s.createElement("textarea");c.className=i.isIOS?"ace_text-input ace_text-input-ios":"ace_text-input",i.isTouchPad&&c.setAttribute("x-palm-disable-auto-cap",!0),c.setAttribute("wrap","off"),c.setAttribute("autocorrect","off"),c.setAttribute("autocapitalize","off"),c.setAttribute("spellcheck",!1),c.style.opacity="0",e.insertBefore(c,e.firstChild);var h="\n aaaa a\n",p=!1,d=!1,v=!1,m=!1,g="",y=!0;try{var b=document.activeElement===c}catch(w){}r.addListener(c,"blur",function(e){t.onBlur(e),b=!1}),r.addListener(c,"focus",function(e){b=!0,t.onFocus(e),x()}),this.focus=function(){if(g)return c.focus();c.style.position="fixed",c.focus()},this.blur=function(){c.blur()},this.isFocused=function(){return b};var E=o.delayedCall(function(){b&&x(y)}),S=o.delayedCall(function(){m||(c.value=h,b&&x())});i.isWebKit||t.addEventListener("changeSelection",function(){t.selection.isEmpty()!=y&&(y=!y,E.schedule())}),T(),b&&t.onFocus();var N=function(e){return e.selectionStart===0&&e.selectionEnd===e.value.length},C=function(e){N(c)?(t.selectAll(),x()):k&&x(t.selection.isEmpty())},k=null;this.setInputHandler=function(e){k=e},this.getInputHandler=function(){return k};var L=!1,A=function(e){if(c.selectionStart===4&&c.selectionEnd===5)return;k&&(e=k(e),k=null),v?(x(),e&&t.onPaste(e),v=!1):e==h.substr(0)&&c.selectionStart===4?L?t.execCommand("del",{source:"ace"}):t.execCommand("backspace",{source:"ace"}):p||(e.substring(0,9)==h&&e.length>h.length?e=e.substr(9):e.substr(0,4)==h.substr(0,4)?e=e.substr(4,e.length-h.length+1):e.charAt(e.length-1)==h.charAt(0)&&(e=e.slice(0,-1)),e!=h.charAt(0)&&e.charAt(e.length-1)==h.charAt(0)&&(e=e.slice(0,-1)),e&&t.onTextInput(e)),p&&(p=!1),L&&(L=!1)},O=function(e){if(m)return;var t=c.value;A(t),T()},M=function(e,t,n){var r=e.clipboardData||window.clipboardData;if(!r||f)return;var i=l||n?"Text":"text/plain";try{return t?r.setData(i,t)!==!1:r.getData(i)}catch(e){if(!n)return M(e,t,!0)}},_=function(e,n){var s=t.getCopyText();if(!s)return r.preventDefault(e);M(e,s)?(i.isIOS&&(d=n,c.value="\n aa"+s+"a a\n",c.setSelectionRange(4,4+s.length),p={value:s}),n?t.onCut():t.onCopy(),i.isIOS||r.preventDefault(e)):(p=!0,c.value=s,c.select(),setTimeout(function(){p=!1,T(),x(),n?t.onCut():t.onCopy()}))},D=function(e){_(e,!0)},P=function(e){_(e,!1)},H=function(e){var n=M(e);typeof n=="string"?(n&&t.onPaste(n,e),i.isIE&&setTimeout(x),r.preventDefault(e)):(c.value="",v=!0)};r.addCommandKeyListener(c,t.onCommandKey.bind(t)),r.addListener(c,"select",C),r.addListener(c,"input",O),r.addListener(c,"cut",D),r.addListener(c,"copy",P),r.addListener(c,"paste",H);var B=function(e){if(m||!t.onCompositionStart||t.$readOnly)return;m={},m.canUndo=t.session.$undoManager,t.onCompositionStart(),setTimeout(j,0),t.on("mousedown",F),m.canUndo&&!t.selection.isEmpty()&&(t.insert(""),t.session.markUndoGroup(),t.selection.clearSelection()),t.session.markUndoGroup()},j=function(){if(!m||!t.onCompositionUpdate||t.$readOnly)return;var e=c.value.replace(/\x01/g,"");if(m.lastValue===e)return;t.onCompositionUpdate(e),m.lastValue&&t.undo(),m.canUndo&&(m.lastValue=e);if(m.lastValue){var n=t.selection.getRange();t.insert(m.lastValue),t.session.markUndoGroup(),m.range=t.selection.getRange(),t.selection.setRange(n),t.selection.clearSelection()}},F=function(e){if(!t.onCompositionEnd||t.$readOnly)return;var n=m;m=!1;var r=setTimeout(function(){r=null;var e=c.value.replace(/\x01/g,"");if(m)return;e==n.lastValue?T():!n.lastValue&&e&&(T(),A(e))});k=function(i){return r&&clearTimeout(r),i=i.replace(/\x01/g,""),i==n.lastValue?"":(n.lastValue&&r&&t.undo(),i)},t.onCompositionEnd(),t.removeListener("mousedown",F),e.type=="compositionend"&&n.range&&t.selection.setRange(n.range);var s=!!i.isChrome&&i.isChrome>=53||!!i.isWebKit&&i.isWebKit>=603;s&&O()},I=o.delayedCall(j,50);r.addListener(c,"compositionstart",B),r.addListener(c,"compositionupdate",function(){I.schedule()}),r.addListener(c,"keyup",function(){I.schedule()}),r.addListener(c,"keydown",function(){I.schedule()}),r.addListener(c,"compositionend",F),this.getElement=function(){return c},this.setReadOnly=function(e){c.readOnly=e},this.onContextMenu=function(e){L=!0,x(t.selection.isEmpty()),t._emit("nativecontextmenu",{target:t,domEvent:e}),this.moveToMouse(e,!0)},this.moveToMouse=function(e,n){g||(g=c.style.cssText),c.style.cssText=(n?"z-index:100000;":"")+"height:"+c.style.height+";"+(i.isIE?"opacity:0.1;":"");var o=t.container.getBoundingClientRect(),u=s.computedStyle(t.container),a=o.top+(parseInt(u.borderTopWidth)||0),f=o.left+(parseInt(o.borderLeftWidth)||0),l=o.bottom-a-c.clientHeight-2,h=function(e){c.style.left=e.clientX-f-2+"px",c.style.top=Math.min(e.clientY-a-2,l)+"px"};h(e);if(e.type!="mousedown")return;t.renderer.$keepTextAreaAtCursor&&(t.renderer.$keepTextAreaAtCursor=null),clearTimeout(q),i.isWin&&r.capture(t.container,h,R)},this.onContextMenuClose=R;var q,U=function(e){t.textInput.onContextMenu(e),R()};r.addListener(c,"mouseup",U),r.addListener(c,"mousedown",function(e){e.preventDefault(),R()}),r.addListener(t.renderer.scroller,"contextmenu",U),r.addListener(c,"contextmenu",U);if(i.isIOS){var z=null,W=!1;e.addEventListener("keydown",function(e){z&&clearTimeout(z),W=!0}),e.addEventListener("keyup",function(e){z=setTimeout(function(){W=!1},100)});var X=function(e){if(document.activeElement!==c)return;if(W)return;if(d)return setTimeout(function(){d=!1},100);var n=c.selectionStart,r=c.selectionEnd;c.setSelectionRange(4,5);if(n==r)switch(n){case 0:t.onCommandKey(null,0,u.up);break;case 1:t.onCommandKey(null,0,u.home);break;case 2:t.onCommandKey(null,a.option,u.left);break;case 4:t.onCommandKey(null,0,u.left);break;case 5:t.onCommandKey(null,0,u.right);break;case 7:t.onCommandKey(null,a.option,u.right);break;case 8:t.onCommandKey(null,0,u.end);break;case 9:t.onCommandKey(null,0,u.down)}else{switch(r){case 6:t.onCommandKey(null,a.shift,u.right);break;case 7:t.onCommandKey(null,a.shift|a.option,u.right);break;case 8:t.onCommandKey(null,a.shift,u.end);break;case 9:t.onCommandKey(null,a.shift,u.down)}switch(n){case 0:t.onCommandKey(null,a.shift,u.up);break;case 1:t.onCommandKey(null,a.shift,u.home);break;case 2:t.onCommandKey(null,a.shift|a.option,u.left);break;case 3:t.onCommandKey(null,a.shift,u.left)}}};document.addEventListener("selectionchange",X),t.on("destroy",function(){document.removeEventListener("selectionchange",X)})}};t.TextInput=c}),ace.define("ace/keyboard/textinput",["require","exports","module","ace/lib/event","ace/lib/useragent","ace/lib/dom","ace/lib/lang","ace/keyboard/textinput_ios"],function(e,t,n){"use strict";var r=e("../lib/event"),i=e("../lib/useragent"),s=e("../lib/dom"),o=e("../lib/lang"),u=i.isChrome<18,a=i.isIE,f=i.isChrome>63,l=e("./textinput_ios").TextInput,c=function(e,t){function T(e){e=y?!1:e;if(v)return;v=!0;if(L)var t=0,r=e?0:n.value.length-1;else var t=e?2:1,r=2;try{n.setSelectionRange(t,r)}catch(i){}v=!1}function N(){if(v)return;n.value=c,i.isWebKit&&x.schedule()}function U(){clearTimeout(R),R=setTimeout(function(){m&&(n.style.cssText=m,m=""),t.renderer.$keepTextAreaAtCursor==null&&(t.renderer.$keepTextAreaAtCursor=!0,t.renderer.$moveTextAreaToCursor())},0)}if(i.isIOS)return l.call(this,e,t);var n=s.createElement("textarea");n.className="ace_text-input",n.setAttribute("wrap","off"),n.setAttribute("autocorrect","off"),n.setAttribute("autocapitalize","off"),n.setAttribute("spellcheck",!1),n.style.opacity="0",e.insertBefore(n,e.firstChild);var c=i.isIE?"\x01\x01":"\u2028\u2028",h=i.isIE?/\x01/g:/\u2028/g,p=!1,d=!1,v=!1,m="",g=!0,y=!1,b=!1;try{var w=document.activeElement===n}catch(E){}r.addListener(n,"blur",function(e){t.onBlur(e),w=!1}),r.addListener(n,"focus",function(e){w=!0,t.onFocus(e),T()}),this.$focusScroll=!1,this.focus=function(){if(m||f||this.$focusScroll=="browser")return n.focus({preventScroll:!0});var e=n.style.top;n.style.position="fixed",n.style.top="0px";var t=n.getBoundingClientRect().top!=0,r=[];if(t){var i=n.parentElement;while(i)r.push(i),i.setAttribute("ace_nocontext",!0),i=i.parentElement}n.focus({preventScroll:!0}),t&&r.forEach(function(e){e.removeAttribute("ace_nocontext")}),setTimeout(function(){n.style.position="",n.style.top=="0px"&&(n.style.top=e)},0)},this.blur=function(){n.blur()},this.isFocused=function(){return w};var S=o.delayedCall(function(){w&&T(g)}),x=o.delayedCall(function(){v||(n.value=c,w&&T())});i.isWebKit||t.addEventListener("changeSelection",function(){t.selection.isEmpty()!=g&&(g=!g,S.schedule())}),N(),w&&t.onFocus();var C=function(e){return e.selectionStart===0&&e.selectionEnd===e.value.length},k=function(e){p?p=!1:C(n)?(t.selectAll(),T()):L&&T(t.selection.isEmpty())},L=null;this.setInputHandler=function(e){L=e},this.getInputHandler=function(){return L};var A=!1,O=function(e){L&&(e=L(e),L=null),d?(T(),e&&t.onPaste(e),d=!1):e==c.charAt(0)?A?t.execCommand("del",{source:"ace"}):t.execCommand("backspace",{source:"ace"}):(e.substring(0,2)==c?e=e.substr(2):e.charAt(0)==c.charAt(0)?e=e.substr(1):e.charAt(e.length-1)==c.charAt(0)&&(e=e.slice(0,-1)),e.charAt(e.length-1)==c.charAt(0)&&(e=e.slice(0,-1)),e&&t.onTextInput(e)),A&&(A=!1)},M=function(e){if(v)return;var t=n.value;O(t),N()},_=function(e,t,n){var r=e.clipboardData||window.clipboardData;if(!r||u)return;var i=a||n?"Text":"text/plain";try{return t?r.setData(i,t)!==!1:r.getData(i)}catch(e){if(!n)return _(e,t,!0)}},D=function(e,i){var s=t.getCopyText();if(!s)return r.preventDefault(e);_(e,s)?(i?t.onCut():t.onCopy(),r.preventDefault(e)):(p=!0,n.value=s,n.select(),setTimeout(function(){p=!1,N(),T(),i?t.onCut():t.onCopy()}))},P=function(e){D(e,!0)},H=function(e){D(e,!1)},B=function(e){var s=_(e);typeof s=="string"?(s&&t.onPaste(s,e),i.isIE&&setTimeout(T),r.preventDefault(e)):(n.value="",d=!0)};r.addCommandKeyListener(n,t.onCommandKey.bind(t)),r.addListener(n,"select",k),r.addListener(n,"input",M),r.addListener(n,"cut",P),r.addListener(n,"copy",H),r.addListener(n,"paste",B),(!("oncut"in n)||!("oncopy"in n)||!("onpaste"in n))&&r.addListener(e,"keydown",function(e){if(i.isMac&&!e.metaKey||!e.ctrlKey)return;switch(e.keyCode){case 67:H(e);break;case 86:B(e);break;case 88:P(e)}});var j=function(e){if(v||!t.onCompositionStart||t.$readOnly)return;v={},v.canUndo=t.session.$undoManager,t.onCompositionStart(),setTimeout(F,0),t.on("mousedown",I),v.canUndo&&!t.selection.isEmpty()&&(t.insert(""),t.session.markUndoGroup(),t.selection.clearSelection()),t.session.markUndoGroup()},F=function(){if(!v||!t.onCompositionUpdate||t.$readOnly)return;var e=n.value.replace(h,"");if(v.lastValue===e)return;t.onCompositionUpdate(e),v.lastValue&&t.undo(),v.canUndo&&(v.lastValue=e);if(v.lastValue){var r=t.selection.getRange();t.insert(v.lastValue),t.session.markUndoGroup(),v.range=t.selection.getRange(),t.selection.setRange(r),t.selection.clearSelection()}},I=function(e){if(!t.onCompositionEnd||t.$readOnly)return;var r=v;v=!1;var s=setTimeout(function(){s=null;var e=n.value.replace(h,"");if(v)return;e==r.lastValue?N():!r.lastValue&&e&&(N(),O(e))});L=function(n){return s&&clearTimeout(s),n=n.replace(h,""),n==r.lastValue?"":(r.lastValue&&s&&t.undo(),n)},t.onCompositionEnd(),t.removeListener("mousedown",I),e.type=="compositionend"&&r.range&&t.selection.setRange(r.range);var o=i.isIE||i.isChrome&&i.isChrome>=53||i.isWebKit&&i.isWebKit>=603;o&&M()},q=o.delayedCall(F,50);r.addListener(n,"compositionstart",j),r.addListener(n,"compositionupdate",function(){q.schedule()}),r.addListener(n,"keyup",function(){q.schedule()}),r.addListener(n,"keydown",function(){q.schedule()}),r.addListener(n,"compositionend",I),this.getElement=function(){return n},this.setCommandMode=function(e){b=e,n.readOnly=!1},this.setReadOnly=function(e){b||(n.readOnly=e)},this.setCopyWithEmptySelection=function(e){y=e},this.onContextMenu=function(e){A=!0,T(t.selection.isEmpty()),t._emit("nativecontextmenu",{target:t,domEvent:e}),this.moveToMouse(e,!0)},this.moveToMouse=function(e,o){m||(m=n.style.cssText),n.style.cssText=(o?"z-index:100000;":"")+"height:"+n.style.height+";"+(i.isIE?"opacity:0.1;":"");var u=t.container.getBoundingClientRect(),a=s.computedStyle(t.container),f=u.top+(parseInt(a.borderTopWidth)||0),l=u.left+(parseInt(u.borderLeftWidth)||0),c=u.bottom-f-n.clientHeight-2,h=function(e){n.style.left=e.clientX-l-2+"px",n.style.top=Math.min(e.clientY-f-2,c)+"px"};h(e);if(e.type!="mousedown")return;t.renderer.$keepTextAreaAtCursor&&(t.renderer.$keepTextAreaAtCursor=null),clearTimeout(R),i.isWin&&r.capture(t.container,h,U)},this.onContextMenuClose=U;var R,z=function(e){t.textInput.onContextMenu(e),U()};r.addListener(n,"mouseup",z),r.addListener(n,"mousedown",function(e){e.preventDefault(),U()}),r.addListener(t.renderer.scroller,"contextmenu",z),r.addListener(n,"contextmenu",z)};t.TextInput=c}),ace.define("ace/mouse/default_handlers",["require","exports","module","ace/lib/dom","ace/lib/event","ace/lib/useragent"],function(e,t,n){"use strict";function a(e){e.$clickSelection=null;var t=e.editor;t.setDefaultHandler("mousedown",this.onMouseDown.bind(e)),t.setDefaultHandler("dblclick",this.onDoubleClick.bind(e)),t.setDefaultHandler("tripleclick",this.onTripleClick.bind(e)),t.setDefaultHandler("quadclick",this.onQuadClick.bind(e)),t.setDefaultHandler("mousewheel",this.onMouseWheel.bind(e)),t.setDefaultHandler("touchmove",this.onTouchMove.bind(e));var n=["select","startSelect","selectEnd","selectAllEnd","selectByWordsEnd","selectByLinesEnd","dragWait","dragWaitEnd","focusWait"];n.forEach(function(t){e[t]=this[t]},this),e.selectByLines=this.extendSelectionBy.bind(e,"getLineRange"),e.selectByWords=this.extendSelectionBy.bind(e,"getWordRange")}function f(e,t,n,r){return Math.sqrt(Math.pow(n-e,2)+Math.pow(r-t,2))}function l(e,t){if(e.start.row==e.end.row)var n=2*t.column-e.start.column-e.end.column;else if(e.start.row==e.end.row-1&&!e.start.column&&!e.end.column)var n=t.column-4;else var n=2*t.row-e.start.row-e.end.row;return n<0?{cursor:e.start,anchor:e.end}:{cursor:e.end,anchor:e.start}}var r=e("../lib/dom"),i=e("../lib/event"),s=e("../lib/useragent"),o=0,u=250;(function(){this.onMouseDown=function(e){var t=e.inSelection(),n=e.getDocumentPosition();this.mousedownEvent=e;var r=this.editor,i=e.getButton();if(i!==0){var o=r.getSelectionRange(),u=o.isEmpty();(u||i==1)&&r.selection.moveToPosition(n),i==2&&(r.textInput.onContextMenu(e.domEvent),s.isMozilla||e.preventDefault());return}this.mousedownEvent.time=Date.now();if(t&&!r.isFocused()){r.focus();if(this.$focusTimeout&&!this.$clickSelection&&!r.inMultiSelectMode){this.setState("focusWait"),this.captureMouse(e);return}}return this.captureMouse(e),this.startSelect(n,e.domEvent._clicks>1),e.preventDefault()},this.startSelect=function(e,t){e=e||this.editor.renderer.screenToTextCoordinates(this.x,this.y);var n=this.editor;this.mousedownEvent.getShiftKey()?n.selection.selectToPosition(e):t||n.selection.moveToPosition(e),t||this.select(),n.renderer.scroller.setCapture&&n.renderer.scroller.setCapture(),n.setStyle("ace_selecting"),this.setState("select")},this.select=function(){var e,t=this.editor,n=t.renderer.screenToTextCoordinates(this.x,this.y);if(this.$clickSelection){var r=this.$clickSelection.comparePoint(n);if(r==-1)e=this.$clickSelection.end;else if(r==1)e=this.$clickSelection.start;else{var i=l(this.$clickSelection,n);n=i.cursor,e=i.anchor}t.selection.setSelectionAnchor(e.row,e.column)}t.selection.selectToPosition(n),t.renderer.scrollCursorIntoView()},this.extendSelectionBy=function(e){var t,n=this.editor,r=n.renderer.screenToTextCoordinates(this.x,this.y),i=n.selection[e](r.row,r.column);if(this.$clickSelection){var s=this.$clickSelection.comparePoint(i.start),o=this.$clickSelection.comparePoint(i.end);if(s==-1&&o<=0){t=this.$clickSelection.end;if(i.end.row!=r.row||i.end.column!=r.column)r=i.start}else if(o==1&&s>=0){t=this.$clickSelection.start;if(i.start.row!=r.row||i.start.column!=r.column)r=i.end}else if(s==-1&&o==1)r=i.end,t=i.start;else{var u=l(this.$clickSelection,r);r=u.cursor,t=u.anchor}n.selection.setSelectionAnchor(t.row,t.column)}n.selection.selectToPosition(r),n.renderer.scrollCursorIntoView()},this.selectEnd=this.selectAllEnd=this.selectByWordsEnd=this.selectByLinesEnd=function(){this.$clickSelection=null,this.editor.unsetStyle("ace_selecting"),this.editor.renderer.scroller.releaseCapture&&this.editor.renderer.scroller.releaseCapture()},this.focusWait=function(){var e=f(this.mousedownEvent.x,this.mousedownEvent.y,this.x,this.y),t=Date.now();(e>o||t-this.mousedownEvent.time>this.$focusTimeout)&&this.startSelect(this.mousedownEvent.getDocumentPosition())},this.onDoubleClick=function(e){var t=e.getDocumentPosition(),n=this.editor,r=n.session,i=r.getBracketRange(t);i?(i.isEmpty()&&(i.start.column--,i.end.column++),this.setState("select")):(i=n.selection.getWordRange(t.row,t.column),this.setState("selectByWords")),this.$clickSelection=i,this.select()},this.onTripleClick=function(e){var t=e.getDocumentPosition(),n=this.editor;this.setState("selectByLines");var r=n.getSelectionRange();r.isMultiLine()&&r.contains(t.row,t.column)?(this.$clickSelection=n.selection.getLineRange(r.start.row),this.$clickSelection.end=n.selection.getLineRange(r.end.row).end):this.$clickSelection=n.selection.getLineRange(t.row),this.select()},this.onQuadClick=function(e){var t=this.editor;t.selectAll(),this.$clickSelection=t.getSelectionRange(),this.setState("selectAll")},this.onMouseWheel=function(e){if(e.getAccelKey())return;e.getShiftKey()&&e.wheelY&&!e.wheelX&&(e.wheelX=e.wheelY,e.wheelY=0);var t=this.editor;this.$lastScroll||(this.$lastScroll={t:0,vx:0,vy:0,allowed:0});var n=this.$lastScroll,r=e.domEvent.timeStamp,i=r-n.t,s=i?e.wheelX/i:n.vx,o=i?e.wheelY/i:n.vy;i<u&&(s=(s+n.vx)/2,o=(o+n.vy)/2);var a=Math.abs(s/o),f=!1;a>=1&&t.renderer.isScrollableBy(e.wheelX*e.speed,0)&&(f=!0),a<=1&&t.renderer.isScrollableBy(0,e.wheelY*e.speed)&&(f=!0);if(f)n.allowed=r;else if(r-n.allowed<u){var l=Math.abs(s)<=1.1*Math.abs(n.vx)&&Math.abs(o)<=1.1*Math.abs(n.vy);l?(f=!0,n.allowed=r):n.allowed=0}n.t=r,n.vx=s,n.vy=o;if(f)return t.renderer.scrollBy(e.wheelX*e.speed,e.wheelY*e.speed),e.stop()},this.onTouchMove=function(e){this.editor._emit("mousewheel",e)}}).call(a.prototype),t.DefaultHandlers=a}),ace.define("ace/tooltip",["require","exports","module","ace/lib/oop","ace/lib/dom"],function(e,t,n){"use strict";function s(e){this.isOpen=!1,this.$element=null,this.$parentNode=e}var r=e("./lib/oop"),i=e("./lib/dom");(function(){this.$init=function(){return this.$element=i.createElement("div"),this.$element.className="ace_tooltip",this.$element.style.display="none",this.$parentNode.appendChild(this.$element),this.$element},this.getElement=function(){return this.$element||this.$init()},this.setText=function(e){i.setInnerText(this.getElement(),e)},this.setHtml=function(e){this.getElement().innerHTML=e},this.setPosition=function(e,t){this.getElement().style.left=e+"px",this.getElement().style.top=t+"px"},this.setClassName=function(e){i.addCssClass(this.getElement(),e)},this.show=function(e,t,n){e!=null&&this.setText(e),t!=null&&n!=null&&this.setPosition(t,n),this.isOpen||(this.getElement().style.display="block",this.isOpen=!0)},this.hide=function(){this.isOpen&&(this.getElement().style.display="none",this.isOpen=!1)},this.getHeight=function(){return this.getElement().offsetHeight},this.getWidth=function(){return this.getElement().offsetWidth},this.destroy=function(){this.isOpen=!1,this.$element&&this.$element.parentNode&&this.$element.parentNode.removeChild(this.$element)}}).call(s.prototype),t.Tooltip=s}),ace.define("ace/mouse/default_gutter_handler",["require","exports","module","ace/lib/dom","ace/lib/oop","ace/lib/event","ace/tooltip"],function(e,t,n){"use strict";function u(e){function l(){var r=u.getDocumentPosition().row,s=n.$annotations[r];if(!s)return c();var o=t.session.getLength();if(r==o){var a=t.renderer.pixelToScreenCoordinates(0,u.y).row,l=u.$pos;if(a>t.session.documentToScreenRow(l.row,l.column))return c()}if(f==s)return;f=s.text.join("<br/>"),i.setHtml(f),i.show(),t._signal("showGutterTooltip",i),t.on("mousewheel",c);if(e.$tooltipFollowsMouse)h(u);else{var p=u.domEvent.target,d=p.getBoundingClientRect(),v=i.getElement().style;v.left=d.right+"px",v.top=d.bottom+"px"}}function c(){o&&(o=clearTimeout(o)),f&&(i.hide(),f=null,t._signal("hideGutterTooltip",i),t.removeEventListener("mousewheel",c))}function h(e){i.setPosition(e.x,e.y)}var t=e.editor,n=t.renderer.$gutterLayer,i=new a(t.container);e.editor.setDefaultHandler("guttermousedown",function(r){if(!t.isFocused()||r.getButton()!=0)return;var i=n.getRegion(r);if(i=="foldWidgets")return;var s=r.getDocumentPosition().row,o=t.session.selection;if(r.getShiftKey())o.selectTo(s,0);else{if(r.domEvent.detail==2)return t.selectAll(),r.preventDefault();e.$clickSelection=t.selection.getLineRange(s)}return e.setState("selectByLines"),e.captureMouse(r),r.preventDefault()});var o,u,f;e.editor.setDefaultHandler("guttermousemove",function(t){var n=t.domEvent.target||t.domEvent.srcElement;if(r.hasCssClass(n,"ace_fold-widget"))return c();f&&e.$tooltipFollowsMouse&&h(t),u=t;if(o)return;o=setTimeout(function(){o=null,u&&!e.isMousePressed?l():c()},50)}),s.addListener(t.renderer.$gutter,"mouseout",function(e){u=null;if(!f||o)return;o=setTimeout(function(){o=null,c()},50)}),t.on("changeSession",c)}function a(e){o.call(this,e)}var r=e("../lib/dom"),i=e("../lib/oop"),s=e("../lib/event"),o=e("../tooltip").Tooltip;i.inherits(a,o),function(){this.setPosition=function(e,t){var n=window.innerWidth||document.documentElement.clientWidth,r=window.innerHeight||document.documentElement.clientHeight,i=this.getWidth(),s=this.getHeight();e+=15,t+=15,e+i>n&&(e-=e+i-n),t+s>r&&(t-=20+s),o.prototype.setPosition.call(this,e,t)}}.call(a.prototype),t.GutterHandler=u}),ace.define("ace/mouse/mouse_event",["require","exports","module","ace/lib/event","ace/lib/useragent"],function(e,t,n){"use strict";var r=e("../lib/event"),i=e("../lib/useragent"),s=t.MouseEvent=function(e,t){this.domEvent=e,this.editor=t,this.x=this.clientX=e.clientX,this.y=this.clientY=e.clientY,this.$pos=null,this.$inSelection=null,this.propagationStopped=!1,this.defaultPrevented=!1};(function(){this.stopPropagation=function(){r.stopPropagation(this.domEvent),this.propagationStopped=!0},this.preventDefault=function(){r.preventDefault(this.domEvent),this.defaultPrevented=!0},this.stop=function(){this.stopPropagation(),this.preventDefault()},this.getDocumentPosition=function(){return this.$pos?this.$pos:(this.$pos=this.editor.renderer.screenToTextCoordinates(this.clientX,this.clientY),this.$pos)},this.inSelection=function(){if(this.$inSelection!==null)return this.$inSelection;var e=this.editor,t=e.getSelectionRange();if(t.isEmpty())this.$inSelection=!1;else{var n=this.getDocumentPosition();this.$inSelection=t.contains(n.row,n.column)}return this.$inSelection},this.getButton=function(){return r.getButton(this.domEvent)},this.getShiftKey=function(){return this.domEvent.shiftKey},this.getAccelKey=i.isMac?function(){return this.domEvent.metaKey}:function(){return this.domEvent.ctrlKey}}).call(s.prototype)}),ace.define("ace/mouse/dragdrop_handler",["require","exports","module","ace/lib/dom","ace/lib/event","ace/lib/useragent"],function(e,t,n){"use strict";function f(e){function T(e,n){var r=Date.now(),i=!n||e.row!=n.row,s=!n||e.column!=n.column;if(!S||i||s)t.moveCursorToPosition(e),S=r,x={x:p,y:d};else{var o=l(x.x,x.y,p,d);o>a?S=null:r-S>=u&&(t.renderer.scrollCursorIntoView(),S=null)}}function N(e,n){var r=Date.now(),i=t.renderer.layerConfig.lineHeight,s=t.renderer.layerConfig.characterWidth,u=t.renderer.scroller.getBoundingClientRect(),a={x:{left:p-u.left,right:u.right-p},y:{top:d-u.top,bottom:u.bottom-d}},f=Math.min(a.x.left,a.x.right),l=Math.min(a.y.top,a.y.bottom),c={row:e.row,column:e.column};f/s<=2&&(c.column+=a.x.left<a.x.right?-3:2),l/i<=1&&(c.row+=a.y.top<a.y.bottom?-1:1);var h=e.row!=c.row,v=e.column!=c.column,m=!n||e.row!=n.row;h||v&&!m?E?r-E>=o&&t.renderer.scrollCursorIntoView(c):E=r:E=null}function C(){var e=g;g=t.renderer.screenToTextCoordinates(p,d),T(g,e),N(g,e)}function k(){m=t.selection.toOrientedRange(),h=t.session.addMarker(m,"ace_selection",t.getSelectionStyle()),t.clearSelection(),t.isFocused()&&t.renderer.$cursorLayer.setBlinking(!1),clearInterval(v),C(),v=setInterval(C,20),y=0,i.addListener(document,"mousemove",O)}function L(){clearInterval(v),t.session.removeMarker(h),h=null,t.selection.fromOrientedRange(m),t.isFocused()&&!w&&t.renderer.$cursorLayer.setBlinking(!t.getReadOnly()),m=null,g=null,y=0,E=null,S=null,i.removeListener(document,"mousemove",O)}function O(){A==null&&(A=setTimeout(function(){A!=null&&h&&L()},20))}function M(e){var t=e.types;return!t||Array.prototype.some.call(t,function(e){return e=="text/plain"||e=="Text"})}function _(e){var t=["copy","copymove","all","uninitialized"],n=["move","copymove","linkmove","all","uninitialized"],r=s.isMac?e.altKey:e.ctrlKey,i="uninitialized";try{i=e.dataTransfer.effectAllowed.toLowerCase()}catch(e){}var o="none";return r&&t.indexOf(i)>=0?o="copy":n.indexOf(i)>=0?o="move":t.indexOf(i)>=0&&(o="copy"),o}var t=e.editor,n=r.createElement("img");n.src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==",s.isOpera&&(n.style.cssText="width:1px;height:1px;position:fixed;top:0;left:0;z-index:2147483647;opacity:0;");var f=["dragWait","dragWaitEnd","startDrag","dragReadyEnd","onMouseDrag"];f.forEach(function(t){e[t]=this[t]},this),t.addEventListener("mousedown",this.onMouseDown.bind(e));var c=t.container,h,p,d,v,m,g,y=0,b,w,E,S,x;this.onDragStart=function(e){if(this.cancelDrag||!c.draggable){var r=this;return setTimeout(function(){r.startSelect(),r.captureMouse(e)},0),e.preventDefault()}m=t.getSelectionRange();var i=e.dataTransfer;i.effectAllowed=t.getReadOnly()?"copy":"copyMove",s.isOpera&&(t.container.appendChild(n),n.scrollTop=0),i.setDragImage&&i.setDragImage(n,0,0),s.isOpera&&t.container.removeChild(n),i.clearData(),i.setData("Text",t.session.getTextRange()),w=!0,this.setState("drag")},this.onDragEnd=function(e){c.draggable=!1,w=!1,this.setState(null);if(!t.getReadOnly()){var n=e.dataTransfer.dropEffect;!b&&n=="move"&&t.session.remove(t.getSelectionRange()),t.renderer.$cursorLayer.setBlinking(!0)}this.editor.unsetStyle("ace_dragging"),this.editor.renderer.setCursorStyle("")},this.onDragEnter=function(e){if(t.getReadOnly()||!M(e.dataTransfer))return;return p=e.clientX,d=e.clientY,h||k(),y++,e.dataTransfer.dropEffect=b=_(e),i.preventDefault(e)},this.onDragOver=function(e){if(t.getReadOnly()||!M(e.dataTransfer))return;return p=e.clientX,d=e.clientY,h||(k(),y++),A!==null&&(A=null),e.dataTransfer.dropEffect=b=_(e),i.preventDefault(e)},this.onDragLeave=function(e){y--;if(y<=0&&h)return L(),b=null,i.preventDefault(e)},this.onDrop=function(e){if(!g)return;var n=e.dataTransfer;if(w)switch(b){case"move":m.contains(g.row,g.column)?m={start:g,end:g}:m=t.moveText(m,g);break;case"copy":m=t.moveText(m,g,!0)}else{var r=n.getData("Text");m={start:g,end:t.session.insert(g,r)},t.focus(),b=null}return L(),i.preventDefault(e)},i.addListener(c,"dragstart",this.onDragStart.bind(e)),i.addListener(c,"dragend",this.onDragEnd.bind(e)),i.addListener(c,"dragenter",this.onDragEnter.bind(e)),i.addListener(c,"dragover",this.onDragOver.bind(e)),i.addListener(c,"dragleave",this.onDragLeave.bind(e)),i.addListener(c,"drop",this.onDrop.bind(e));var A=null}function l(e,t,n,r){return Math.sqrt(Math.pow(n-e,2)+Math.pow(r-t,2))}var r=e("../lib/dom"),i=e("../lib/event"),s=e("../lib/useragent"),o=200,u=200,a=5;(function(){this.dragWait=function(){var e=Date.now()-this.mousedownEvent.time;e>this.editor.getDragDelay()&&this.startDrag()},this.dragWaitEnd=function(){var e=this.editor.container;e.draggable=!1,this.startSelect(this.mousedownEvent.getDocumentPosition()),this.selectEnd()},this.dragReadyEnd=function(e){this.editor.renderer.$cursorLayer.setBlinking(!this.editor.getReadOnly()),this.editor.unsetStyle("ace_dragging"),this.editor.renderer.setCursorStyle(""),this.dragWaitEnd()},this.startDrag=function(){this.cancelDrag=!1;var e=this.editor,t=e.container;t.draggable=!0,e.renderer.$cursorLayer.setBlinking(!1),e.setStyle("ace_dragging");var n=s.isWin?"default":"move";e.renderer.setCursorStyle(n),this.setState("dragReady")},this.onMouseDrag=function(e){var t=this.editor.container;if(s.isIE&&this.state=="dragReady"){var n=l(this.mousedownEvent.x,this.mousedownEvent.y,this.x,this.y);n>3&&t.dragDrop()}if(this.state==="dragWait"){var n=l(this.mousedownEvent.x,this.mousedownEvent.y,this.x,this.y);n>0&&(t.draggable=!1,this.startSelect(this.mousedownEvent.getDocumentPosition()))}},this.onMouseDown=function(e){if(!this.$dragEnabled)return;this.mousedownEvent=e;var t=this.editor,n=e.inSelection(),r=e.getButton(),i=e.domEvent.detail||1;if(i===1&&r===0&&n){if(e.editor.inMultiSelectMode&&(e.getAccelKey()||e.getShiftKey()))return;this.mousedownEvent.time=Date.now();var o=e.domEvent.target||e.domEvent.srcElement;"unselectable"in o&&(o.unselectable="on");if(t.getDragDelay()){if(s.isWebKit){this.cancelDrag=!0;var u=t.container;u.draggable=!0}this.setState("dragWait")}else this.startDrag();this.captureMouse(e,this.onMouseDrag.bind(this)),e.defaultPrevented=!0}}}).call(f.prototype),t.DragdropHandler=f}),ace.define("ace/lib/net",["require","exports","module","ace/lib/dom"],function(e,t,n){"use strict";var r=e("./dom");t.get=function(e,t){var n=new XMLHttpRequest;n.open("GET",e,!0),n.onreadystatechange=function(){n.readyState===4&&t(n.responseText)},n.send(null)},t.loadScript=function(e,t){var n=r.getDocumentHead(),i=document.createElement("script");i.src=e,n.appendChild(i),i.onload=i.onreadystatechange=function(e,n){if(n||!i.readyState||i.readyState=="loaded"||i.readyState=="complete")i=i.onload=i.onreadystatechange=null,n||t()}},t.qualifyURL=function(e){var t=document.createElement("a");return t.href=e,t.href}}),ace.define("ace/lib/event_emitter",["require","exports","module"],function(e,t,n){"use strict";var r={},i=function(){this.propagationStopped=!0},s=function(){this.defaultPrevented=!0};r._emit=r._dispatchEvent=function(e,t){this._eventRegistry||(this._eventRegistry={}),this._defaultHandlers||(this._defaultHandlers={});var n=this._eventRegistry[e]||[],r=this._defaultHandlers[e];if(!n.length&&!r)return;if(typeof t!="object"||!t)t={};t.type||(t.type=e),t.stopPropagation||(t.stopPropagation=i),t.preventDefault||(t.preventDefault=s),n=n.slice();for(var o=0;o<n.length;o++){n[o](t,this);if(t.propagationStopped)break}if(r&&!t.defaultPrevented)return r(t,this)},r._signal=function(e,t){var n=(this._eventRegistry||{})[e];if(!n)return;n=n.slice();for(var r=0;r<n.length;r++)n[r](t,this)},r.once=function(e,t){var n=this;t&&this.addEventListener(e,function r(){n.removeEventListener(e,r),t.apply(null,arguments)})},r.setDefaultHandler=function(e,t){var n=this._defaultHandlers;n||(n=this._defaultHandlers={_disabled_:{}});if(n[e]){var r=n[e],i=n._disabled_[e];i||(n._disabled_[e]=i=[]),i.push(r);var s=i.indexOf(t);s!=-1&&i.splice(s,1)}n[e]=t},r.removeDefaultHandler=function(e,t){var n=this._defaultHandlers;if(!n)return;var r=n._disabled_[e];if(n[e]==t){var i=n[e];r&&this.setDefaultHandler(e,r.pop())}else if(r){var s=r.indexOf(t);s!=-1&&r.splice(s,1)}},r.on=r.addEventListener=function(e,t,n){this._eventRegistry=this._eventRegistry||{};var r=this._eventRegistry[e];return r||(r=this._eventRegistry[e]=[]),r.indexOf(t)==-1&&r[n?"unshift":"push"](t),t},r.off=r.removeListener=r.removeEventListener=function(e,t){this._eventRegistry=this._eventRegistry||{};var n=this._eventRegistry[e];if(!n)return;var r=n.indexOf(t);r!==-1&&n.splice(r,1)},r.removeAllListeners=function(e){this._eventRegistry&&(this._eventRegistry[e]=[])},t.EventEmitter=r}),ace.define("ace/lib/app_config",["require","exports","module","ace/lib/oop","ace/lib/event_emitter"],function(e,t,n){"no use strict";function o(e){typeof console!="undefined"&&console.warn&&console.warn.apply(console,arguments)}function u(e,t){var n=new Error(e);n.data=t,typeof console=="object"&&console.error&&console.error(n),setTimeout(function(){throw n})}var r=e("./oop"),i=e("./event_emitter").EventEmitter,s={setOptions:function(e){Object.keys(e).forEach(function(t){this.setOption(t,e[t])},this)},getOptions:function(e){var t={};if(!e){var n=this.$options;e=Object.keys(n).filter(function(e){return!n[e].hidden})}else Array.isArray(e)||(t=e,e=Object.keys(t));return e.forEach(function(e){t[e]=this.getOption(e)},this),t},setOption:function(e,t){if(this["$"+e]===t)return;var n=this.$options[e];if(!n)return o('misspelled option "'+e+'"');if(n.forwardTo)return this[n.forwardTo]&&this[n.forwardTo].setOption(e,t);n.handlesSet||(this["$"+e]=t),n&&n.set&&n.set.call(this,t)},getOption:function(e){var t=this.$options[e];return t?t.forwardTo?this[t.forwardTo]&&this[t.forwardTo].getOption(e):t&&t.get?t.get.call(this):this["$"+e]:o('misspelled option "'+e+'"')}},a=function(){this.$defaultOptions={}};(function(){r.implement(this,i),this.defineOptions=function(e,t,n){return e.$options||(this.$defaultOptions[t]=e.$options={}),Object.keys(n).forEach(function(t){var r=n[t];typeof r=="string"&&(r={forwardTo:r}),r.name||(r.name=t),e.$options[r.name]=r,"initialValue"in r&&(e["$"+r.name]=r.initialValue)}),r.implement(e,s),this},this.resetOptions=function(e){Object.keys(e.$options).forEach(function(t){var n=e.$options[t];"value"in n&&e.setOption(t,n.value)})},this.setDefaultValue=function(e,t,n){var r=this.$defaultOptions[e]||(this.$defaultOptions[e]={});r[t]&&(r.forwardTo?this.setDefaultValue(r.forwardTo,t,n):r[t].value=n)},this.setDefaultValues=function(e,t){Object.keys(t).forEach(function(n){this.setDefaultValue(e,n,t[n])},this)},this.warn=o,this.reportError=u}).call(a.prototype),t.AppConfig=a}),ace.define("ace/config",["require","exports","module","ace/lib/lang","ace/lib/oop","ace/lib/net","ace/lib/app_config"],function(e,t,n){"no use strict";function f(r){if(!u||!u.document)return;a.packaged=r||e.packaged||n.packaged||u.define&&define.packaged;var i={},s="",o=document.currentScript||document._currentScript,f=o&&o.ownerDocument||document,c=f.getElementsByTagName("script");for(var h=0;h<c.length;h++){var p=c[h],d=p.src||p.getAttribute("src");if(!d)continue;var v=p.attributes;for(var m=0,g=v.length;m<g;m++){var y=v[m];y.name.indexOf("data-ace-")===0&&(i[l(y.name.replace(/^data-ace-/,""))]=y.value)}var b=d.match(/^(.*)\/ace(\-\w+)?\.js(\?|$)/);b&&(s=b[1])}s&&(i.base=i.base||s,i.packaged=!0),i.basePath=i.base,i.workerPath=i.workerPath||i.base,i.modePath=i.modePath||i.base,i.themePath=i.themePath||i.base,delete i.base;for(var w in i)typeof i[w]!="undefined"&&t.set(w,i[w])}function l(e){return e.replace(/-(.)/g,function(e,t){return t.toUpperCase()})}var r=e("./lib/lang"),i=e("./lib/oop"),s=e("./lib/net"),o=e("./lib/app_config").AppConfig;n.exports=t=new o;var u=function(){return this||typeof window!="undefined"&&window}(),a={packaged:!1,workerPath:null,modePath:null,themePath:null,basePath:"",suffix:".js",$moduleUrls:{}};t.get=function(e){if(!a.hasOwnProperty(e))throw new Error("Unknown config key: "+e);return a[e]},t.set=function(e,t){if(!a.hasOwnProperty(e))throw new Error("Unknown config key: "+e);a[e]=t},t.all=function(){return r.copyObject(a)},t.moduleUrl=function(e,t){if(a.$moduleUrls[e])return a.$moduleUrls[e];var n=e.split("/");t=t||n[n.length-2]||"";var r=t=="snippets"?"/":"-",i=n[n.length-1];if(t=="worker"&&r=="-"){var s=new RegExp("^"+t+"[\\-_]|[\\-_]"+t+"$","g");i=i.replace(s,"")}(!i||i==t)&&n.length>1&&(i=n[n.length-2]);var o=a[t+"Path"];return o==null?o=a.basePath:r=="/"&&(t=r=""),o&&o.slice(-1)!="/"&&(o+="/"),o+t+r+i+this.get("suffix")},t.setModuleUrl=function(e,t){return a.$moduleUrls[e]=t},t.$loading={},t.loadModule=function(n,r){var i,o;Array.isArray(n)&&(o=n[0],n=n[1]);try{i=e(n)}catch(u){}if(i&&!t.$loading[n])return r&&r(i);t.$loading[n]||(t.$loading[n]=[]),t.$loading[n].push(r);if(t.$loading[n].length>1)return;var a=function(){e([n],function(e){t._emit("load.module",{name:n,module:e});var r=t.$loading[n];t.$loading[n]=null,r.forEach(function(t){t&&t(e)})})};if(!t.get("packaged"))return a();s.loadScript(t.moduleUrl(n,o),a)},t.init=f}),ace.define("ace/mouse/mouse_handler",["require","exports","module","ace/lib/event","ace/lib/useragent","ace/mouse/default_handlers","ace/mouse/default_gutter_handler","ace/mouse/mouse_event","ace/mouse/dragdrop_handler","ace/config"],function(e,t,n){"use strict";var r=e("../lib/event"),i=e("../lib/useragent"),s=e("./default_handlers").DefaultHandlers,o=e("./default_gutter_handler").GutterHandler,u=e("./mouse_event").MouseEvent,a=e("./dragdrop_handler").DragdropHandler,f=e("../config"),l=function(e){var t=this;this.editor=e,new s(this),new o(this),new a(this);var n=function(t){var n=!document.hasFocus||!document.hasFocus()||!e.isFocused()&&document.activeElement==(e.textInput&&e.textInput.getElement());n&&window.focus(),e.focus()},u=e.renderer.getMouseEventTarget();r.addListener(u,"click",this.onMouseEvent.bind(this,"click")),r.addListener(u,"mousemove",this.onMouseMove.bind(this,"mousemove")),r.addMultiMouseDownListener([u,e.renderer.scrollBarV&&e.renderer.scrollBarV.inner,e.renderer.scrollBarH&&e.renderer.scrollBarH.inner,e.textInput&&e.textInput.getElement()].filter(Boolean),[400,300,250],this,"onMouseEvent"),r.addMouseWheelListener(e.container,this.onMouseWheel.bind(this,"mousewheel")),r.addTouchMoveListener(e.container,this.onTouchMove.bind(this,"touchmove"));var f=e.renderer.$gutter;r.addListener(f,"mousedown",this.onMouseEvent.bind(this,"guttermousedown")),r.addListener(f,"click",this.onMouseEvent.bind(this,"gutterclick")),r.addListener(f,"dblclick",this.onMouseEvent.bind(this,"gutterdblclick")),r.addListener(f,"mousemove",this.onMouseEvent.bind(this,"guttermousemove")),r.addListener(u,"mousedown",n),r.addListener(f,"mousedown",n),i.isIE&&e.renderer.scrollBarV&&(r.addListener(e.renderer.scrollBarV.element,"mousedown",n),r.addListener(e.renderer.scrollBarH.element,"mousedown",n)),e.on("mousemove",function(n){if(t.state||t.$dragDelay||!t.$dragEnabled)return;var r=e.renderer.screenToTextCoordinates(n.x,n.y),i=e.session.selection.getRange(),s=e.renderer;!i.isEmpty()&&i.insideStart(r.row,r.column)?s.setCursorStyle("default"):s.setCursorStyle("")})};(function(){this.onMouseEvent=function(e,t){this.editor._emit(e,new u(t,this.editor))},this.onMouseMove=function(e,t){var n=this.editor._eventRegistry&&this.editor._eventRegistry.mousemove;if(!n||!n.length)return;this.editor._emit(e,new u(t,this.editor))},this.onMouseWheel=function(e,t){var n=new u(t,this.editor);n.speed=this.$scrollSpeed*2,n.wheelX=t.wheelX,n.wheelY=t.wheelY,this.editor._emit(e,n)},this.onTouchMove=function(e,t){var n=new u(t,this.editor);n.speed=1,n.wheelX=t.wheelX,n.wheelY=t.wheelY,this.editor._emit(e,n)},this.setState=function(e){this.state=e},this.captureMouse=function(e,t){this.x=e.x,this.y=e.y,this.isMousePressed=!0;var n=this.editor,s=this.editor.renderer;s.$keepTextAreaAtCursor&&(s.$keepTextAreaAtCursor=null);var o=this,a=function(e){if(!e)return;if(i.isWebKit&&!e.which&&o.releaseMouse)return o.releaseMouse();o.x=e.clientX,o.y=e.clientY,t&&t(e),o.mouseEvent=new u(e,o.editor),o.$mouseMoved=!0},f=function(e){n.off("beforeEndOperation",c),clearInterval(h),l(),o[o.state+"End"]&&o[o.state+"End"](e),o.state="",s.$keepTextAreaAtCursor==null&&(s.$keepTextAreaAtCursor=!0,s.$moveTextAreaToCursor()),o.isMousePressed=!1,o.$onCaptureMouseMove=o.releaseMouse=null,e&&o.onMouseEvent("mouseup",e)},l=function(){o[o.state]&&o[o.state](),o.$mouseMoved=!1};if(i.isOldIE&&e.domEvent.type=="dblclick")return setTimeout(function(){f(e)});var c=function(e){n.curOp.command.name&&n.curOp.selectionChanged&&(o[o.state+"End"]&&o[o.state+"End"](),o.state="",o.releaseMouse())};n.on("beforeEndOperation",c),o.$onCaptureMouseMove=a,o.releaseMouse=r.capture(this.editor.container,a,f);var h=setInterval(l,20)},this.releaseMouse=null,this.cancelContextMenu=function(){var e=function(t){if(t&&t.domEvent&&t.domEvent.type!="contextmenu")return;this.editor.off("nativecontextmenu",e),t&&t.domEvent&&r.stopEvent(t.domEvent)}.bind(this);setTimeout(e,10),this.editor.on("nativecontextmenu",e)}}).call(l.prototype),f.defineOptions(l.prototype,"mouseHandler",{scrollSpeed:{initialValue:2},dragDelay:{initialValue:i.isMac?150:0},dragEnabled:{initialValue:!0},focusTimeout:{initialValue:0},tooltipFollowsMouse:{initialValue:!0}}),t.MouseHandler=l}),ace.define("ace/mouse/fold_handler",["require","exports","module","ace/lib/dom"],function(e,t,n){"use strict";function i(e){e.on("click",function(t){var n=t.getDocumentPosition(),i=e.session,s=i.getFoldAt(n.row,n.column,1);s&&(t.getAccelKey()?i.removeFold(s):i.expandFold(s),t.stop());var o=t.domEvent&&t.domEvent.target;o&&r.hasCssClass(o,"ace_inline_button")&&r.hasCssClass(o,"ace_toggle_wrap")&&(i.setOption("wrap",!0),e.renderer.scrollCursorIntoView())}),e.on("gutterclick",function(t){var n=e.renderer.$gutterLayer.getRegion(t);if(n=="foldWidgets"){var r=t.getDocumentPosition().row,i=e.session;i.foldWidgets&&i.foldWidgets[r]&&e.session.onFoldWidgetClick(r,t),e.isFocused()||e.focus(),t.stop()}}),e.on("gutterdblclick",function(t){var n=e.renderer.$gutterLayer.getRegion(t);if(n=="foldWidgets"){var r=t.getDocumentPosition().row,i=e.session,s=i.getParentFoldRangeData(r,!0),o=s.range||s.firstRange;if(o){r=o.start.row;var u=i.getFoldAt(r,i.getLine(r).length,1);u?i.removeFold(u):(i.addFold("...",o),e.renderer.scrollCursorIntoView({row:o.start.row,column:0}))}t.stop()}})}var r=e("../lib/dom");t.FoldHandler=i}),ace.define("ace/keyboard/keybinding",["require","exports","module","ace/lib/keys","ace/lib/event"],function(e,t,n){"use strict";var r=e("../lib/keys"),i=e("../lib/event"),s=function(e){this.$editor=e,this.$data={editor:e},this.$handlers=[],this.setDefaultHandler(e.commands)};(function(){this.setDefaultHandler=function(e){this.removeKeyboardHandler(this.$defaultHandler),this.$defaultHandler=e,this.addKeyboardHandler(e,0)},this.setKeyboardHandler=function(e){var t=this.$handlers;if(t[t.length-1]==e)return;while(t[t.length-1]&&t[t.length-1]!=this.$defaultHandler)this.removeKeyboardHandler(t[t.length-1]);this.addKeyboardHandler(e,1)},this.addKeyboardHandler=function(e,t){if(!e)return;typeof e=="function"&&!e.handleKeyboard&&(e.handleKeyboard=e);var n=this.$handlers.indexOf(e);n!=-1&&this.$handlers.splice(n,1),t==undefined?this.$handlers.push(e):this.$handlers.splice(t,0,e),n==-1&&e.attach&&e.attach(this.$editor)},this.removeKeyboardHandler=function(e){var t=this.$handlers.indexOf(e);return t==-1?!1:(this.$handlers.splice(t,1),e.detach&&e.detach(this.$editor),!0)},this.getKeyboardHandler=function(){return this.$handlers[this.$handlers.length-1]},this.getStatusText=function(){var e=this.$data,t=e.editor;return this.$handlers.map(function(n){return n.getStatusText&&n.getStatusText(t,e)||""}).filter(Boolean).join(" ")},this.$callKeyboardHandlers=function(e,t,n,r){var s,o=!1,u=this.$editor.commands;for(var a=this.$handlers.length;a--;){s=this.$handlers[a].handleKeyboard(this.$data,e,t,n,r);if(!s||!s.command)continue;s.command=="null"?o=!0:o=u.exec(s.command,this.$editor,s.args,r),o&&r&&e!=-1&&s.passEvent!=1&&s.command.passEvent!=1&&i.stopEvent(r);if(o)break}return!o&&e==-1&&(s={command:"insertstring"},o=u.exec("insertstring",this.$editor,t)),o&&this.$editor._signal&&this.$editor._signal("keyboardActivity",s),o},this.onCommandKey=function(e,t,n){var i=r.keyCodeToString(n);this.$callKeyboardHandlers(t,i,n,e)},this.onTextInput=function(e){this.$callKeyboardHandlers(-1,e)}}).call(s.prototype),t.KeyBinding=s}),ace.define("ace/lib/bidiutil",["require","exports","module"],function(e,t,n){"use strict";function F(e,t,n,r){var i=s?d:p,c=null,h=null,v=null,m=0,g=null,y=null,b=-1,w=null,E=null,T=[];if(!r)for(w=0,r=[];w<n;w++)r[w]=R(e[w]);o=s,u=!1,a=!1,f=!1,l=!1;for(E=0;E<n;E++){c=m,T[E]=h=q(e,r,T,E),m=i[c][h],g=m&240,m&=15,t[E]=v=i[m][5];if(g>0)if(g==16){for(w=b;w<E;w++)t[w]=1;b=-1}else b=-1;y=i[m][6];if(y)b==-1&&(b=E);else if(b>-1){for(w=b;w<E;w++)t[w]=v;b=-1}r[E]==S&&(t[E]=0),o|=v}if(l)for(w=0;w<n;w++)if(r[w]==x){t[w]=s;for(var C=w-1;C>=0;C--){if(r[C]!=N)break;t[C]=s}}}function I(e,t,n){if(o<e)return;if(e==1&&s==m&&!f){n.reverse();return}var r=n.length,i=0,u,a,l,c;while(i<r){if(t[i]>=e){u=i+1;while(u<r&&t[u]>=e)u++;for(a=i,l=u-1;a<l;a++,l--)c=n[a],n[a]=n[l],n[l]=c;i=u}i++}}function q(e,t,n,r){var i=t[r],o,c,h,p;switch(i){case g:case y:u=!1;case E:case w:return i;case b:return u?w:b;case T:return u=!0,a=!0,y;case N:return E;case C:if(r<1||r+1>=t.length||(o=n[r-1])!=b&&o!=w||(c=t[r+1])!=b&&c!=w)return E;return u&&(c=w),c==o?c:E;case k:o=r>0?n[r-1]:S;if(o==b&&r+1<t.length&&t[r+1]==b)return b;return E;case L:if(r>0&&n[r-1]==b)return b;if(u)return E;p=r+1,h=t.length;while(p<h&&t[p]==L)p++;if(p<h&&t[p]==b)return b;return E;case A:h=t.length,p=r+1;while(p<h&&t[p]==A)p++;if(p<h){var d=e[r],v=d>=1425&&d<=2303||d==64286;o=t[p];if(v&&(o==y||o==T))return y}if(r<1||(o=t[r-1])==S)return E;return n[r-1];case S:return u=!1,f=!0,s;case x:return l=!0,E;case O:case M:case D:case P:case _:u=!1;case H:return E}}function R(e){var t=e.charCodeAt(0),n=t>>8;return n==0?t>191?g:B[t]:n==5?/[\u0591-\u05f4]/.test(e)?y:g:n==6?/[\u0610-\u061a\u064b-\u065f\u06d6-\u06e4\u06e7-\u06ed]/.test(e)?A:/[\u0660-\u0669\u066b-\u066c]/.test(e)?w:t==1642?L:/[\u06f0-\u06f9]/.test(e)?b:T:n==32&&t<=8287?j[t&255]:n==254?t>=65136?T:E:E}function U(e){return e>="\u064b"&&e<="\u0655"}var r=["\u0621","\u0641"],i=["\u063a","\u064a"],s=0,o=0,u=!1,a=!1,f=!1,l=!1,c=!1,h=!1,p=[[0,3,0,1,0,0,0],[0,3,0,1,2,2,0],[0,3,0,17,2,0,1],[0,3,5,5,4,1,0],[0,3,21,21,4,0,1],[0,3,5,5,4,2,0]],d=[[2,0,1,1,0,1,0],[2,0,1,1,0,2,0],[2,0,2,1,3,2,0],[2,0,2,33,3,1,1]],v=0,m=1,g=0,y=1,b=2,w=3,E=4,S=5,x=6,T=7,N=8,C=9,k=10,L=11,A=12,O=13,M=14,_=15,D=16,P=17,H=18,B=[H,H,H,H,H,H,H,H,H,x,S,x,N,S,H,H,H,H,H,H,H,H,H,H,H,H,H,H,S,S,S,x,N,E,E,L,L,L,E,E,E,E,E,k,C,k,C,C,b,b,b,b,b,b,b,b,b,b,C,E,E,E,E,E,E,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,E,E,E,E,E,E,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,E,E,E,E,H,H,H,H,H,H,S,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,H,C,E,L,L,L,L,E,E,E,E,g,E,E,H,E,E,L,L,b,b,E,g,E,E,E,b,g,E,E,E,E,E],j=[N,N,N,N,N,N,N,N,N,N,N,H,H,H,g,y,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,N,S,O,M,_,D,P,C,L,L,L,L,L,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,C,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,E,N];t.L=g,t.R=y,t.EN=b,t.ON_R=3,t.AN=4,t.R_H=5,t.B=6,t.DOT="\u00b7",t.doBidiReorder=function(e,n,r){if(e.length<2)return{};var i=e.split(""),o=new Array(i.length),u=new Array(i.length),a=[];s=r?m:v,F(i,a,i.length,n);for(var f=0;f<o.length;o[f]=f,f++);I(2,a,o),I(1,a,o);for(var f=0;f<o.length-1;f++)n[f]===w?a[f]=t.AN:a[f]===y&&(n[f]>T&&n[f]<O||n[f]===E||n[f]===H)?a[f]=t.ON_R:f>0&&i[f-1]==="\u0644"&&/\u0622|\u0623|\u0625|\u0627/.test(i[f])&&(a[f-1]=a[f]=t.R_H,f++);i[i.length-1]===t.DOT&&(a[i.length-1]=t.B);for(var f=0;f<o.length;f++)u[f]=a[o[f]];return{logicalFromVisual:o,bidiLevels:u}},t.hasBidiCharacters=function(e,t){var n=!1;for(var r=0;r<e.length;r++)t[r]=R(e.charAt(r)),!n&&(t[r]==y||t[r]==T)&&(n=!0);return n},t.getVisualFromLogicalIdx=function(e,t){for(var n=0;n<t.logicalFromVisual.length;n++)if(t.logicalFromVisual[n]==e)return n;return 0}}),ace.define("ace/bidihandler",["require","exports","module","ace/lib/bidiutil","ace/lib/lang","ace/lib/useragent"],function(e,t,n){"use strict";var r=e("./lib/bidiutil"),i=e("./lib/lang"),s=e("./lib/useragent"),o=/[\u0590-\u05f4\u0600-\u06ff\u0700-\u08ac]/,u=function(e){this.session=e,this.bidiMap={},this.currentRow=null,this.bidiUtil=r,this.charWidths=[],this.EOL="\u00ac",this.showInvisibles=!0,this.isRtlDir=!1,this.line="",this.wrapIndent=0,this.isLastRow=!1,this.EOF="\u00b6",this.seenBidi=!1};(function(){this.isBidiRow=function(e,t,n){return this.seenBidi?(e!==this.currentRow&&(this.currentRow=e,this.updateRowLine(t,n),this.updateBidiMap()),this.bidiMap.bidiLevels):!1},this.onChange=function(e){this.seenBidi?this.currentRow=null:e.action=="insert"&&o.test(e.lines.join("\n"))&&(this.seenBidi=!0,this.currentRow=null)},this.getDocumentRow=function(){var e=0,t=this.session.$screenRowCache;if(t.length){var n=this.session.$getRowCacheIndex(t,this.currentRow);n>=0&&(e=this.session.$docRowCache[n])}return e},this.getSplitIndex=function(){var e=0,t=this.session.$screenRowCache;if(t.length){var n,r=this.session.$getRowCacheIndex(t,this.currentRow);while(this.currentRow-e>0){n=this.session.$getRowCacheIndex(t,this.currentRow-e-1);if(n!==r)break;r=n,e++}}return e},this.updateRowLine=function(e,t){e===undefined&&(e=this.getDocumentRow()),this.wrapIndent=0,this.isLastRow=e===this.session.getLength()-1,this.line=this.session.getLine(e);if(this.session.$useWrapMode){var n=this.session.$wrapData[e];n&&(t===undefined&&(t=this.getSplitIndex()),t>0&&n.length?(this.wrapIndent=n.indent,this.line=t<n.length?this.line.substring(n[t-1],n[n.length-1]):this.line.substring(n[n.length-1])):this.line=this.line.substring(0,n[t]))}var s=this.session,o=0,u;this.line=this.line.replace(/\t|[\u1100-\u2029, \u202F-\uFFE6]/g,function(e,t){return e==="	"||s.isFullWidth(e.charCodeAt(0))?(u=e==="	"?s.getScreenTabSize(t+o):2,o+=u-1,i.stringRepeat(r.DOT,u)):e})},this.updateBidiMap=function(){var e=[],t=this.isLastRow?this.EOF:this.EOL,n=this.line+(this.showInvisibles?t:r.DOT);r.hasBidiCharacters(n,e)?this.bidiMap=r.doBidiReorder(n,e,this.isRtlDir):this.bidiMap={}},this.markAsDirty=function(){this.currentRow=null},this.updateCharacterWidths=function(e){if(!this.seenBidi)return;if(this.characterWidth===e.$characterSize.width)return;var t=this.characterWidth=e.$characterSize.width,n=e.$measureCharWidth("\u05d4");this.charWidths[r.L]=this.charWidths[r.EN]=this.charWidths[r.ON_R]=t,this.charWidths[r.R]=this.charWidths[r.AN]=n,this.charWidths[r.R_H]=s.isChrome?n:n*.45,this.charWidths[r.B]=0,this.currentRow=null},this.getShowInvisibles=function(){return this.showInvisibles},this.setShowInvisibles=function(e){this.showInvisibles=e,this.currentRow=null},this.setEolChar=function(e){this.EOL=e},this.setTextDir=function(e){this.isRtlDir=e},this.getPosLeft=function(e){e-=this.wrapIndent;var t=r.getVisualFromLogicalIdx(e>0?e-1:0,this.bidiMap),n=this.bidiMap.bidiLevels,i=0;e===0&&n[t]%2!==0&&t++;for(var s=0;s<t;s++)i+=this.charWidths[n[s]];return e!==0&&n[t]%2===0&&(i+=this.charWidths[n[t]]),this.wrapIndent&&(i+=this.wrapIndent*this.charWidths[r.L]),i},this.getSelections=function(e,t){var n=this.bidiMap,i=n.bidiLevels,s,o=this.wrapIndent*this.charWidths[r.L],u=[],a=Math.min(e,t)-this.wrapIndent,f=Math.max(e,t)-this.wrapIndent,l=!1,c=!1,h=0;for(var p,d=0;d<i.length;d++)p=n.logicalFromVisual[d],s=i[d],l=p>=a&&p<f,l&&!c?h=o:!l&&c&&u.push({left:h,width:o-h}),o+=this.charWidths[s],c=l;return l&&d===i.length&&u.push({left:h,width:o-h}),u},this.offsetToCol=function(e){var t=0,e=Math.max(e,0),n=0,i=0,s=this.bidiMap.bidiLevels,o=this.charWidths[s[i]];this.wrapIndent&&(e-=this.wrapIndent*this.charWidths[r.L]);while(e>n+o/2){n+=o;if(i===s.length-1){o=0;break}o=this.charWidths[s[++i]]}return i>0&&s[i-1]%2!==0&&s[i]%2===0?(e<n&&i--,t=this.bidiMap.logicalFromVisual[i]):i>0&&s[i-1]%2===0&&s[i]%2!==0?t=1+(e>n?this.bidiMap.logicalFromVisual[i]:this.bidiMap.logicalFromVisual[i-1]):this.isRtlDir&&i===s.length-1&&o===0&&s[i-1]%2===0||!this.isRtlDir&&i===0&&s[i]%2!==0?t=1+this.bidiMap.logicalFromVisual[i]:(i>0&&s[i-1]%2!==0&&o!==0&&i--,t=this.bidiMap.logicalFromVisual[i]),t+this.wrapIndent}}).call(u.prototype),t.BidiHandler=u}),ace.define("ace/selection",["require","exports","module","ace/lib/oop","ace/lib/lang","ace/lib/event_emitter","ace/range"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./lib/lang"),s=e("./lib/event_emitter").EventEmitter,o=e("./range").Range,u=function(e){this.session=e,this.doc=e.getDocument(),this.clearSelection(),this.cursor=this.lead=this.doc.createAnchor(0,0),this.anchor=this.doc.createAnchor(0,0),this.$silent=!1;var t=this;this.cursor.on("change",function(e){t.$cursorChanged=!0,t.$silent||t._emit("changeCursor"),!t.$isEmpty&&!t.$silent&&t._emit("changeSelection"),!t.$keepDesiredColumnOnChange&&e.old.column!=e.value.column&&(t.$desiredColumn=null)}),this.anchor.on("change",function(){t.$anchorChanged=!0,!t.$isEmpty&&!t.$silent&&t._emit("changeSelection")})};(function(){r.implement(this,s),this.isEmpty=function(){return this.$isEmpty||this.anchor.row==this.lead.row&&this.anchor.column==this.lead.column},this.isMultiLine=function(){return!this.$isEmpty&&this.anchor.row!=this.cursor.row},this.getCursor=function(){return this.lead.getPosition()},this.setSelectionAnchor=function(e,t){this.$isEmpty=!1,this.anchor.setPosition(e,t)},this.getAnchor=this.getSelectionAnchor=function(){return this.$isEmpty?this.getSelectionLead():this.anchor.getPosition()},this.getSelectionLead=function(){return this.lead.getPosition()},this.isBackwards=function(){var e=this.anchor,t=this.lead;return e.row>t.row||e.row==t.row&&e.column>t.column},this.getRange=function(){var e=this.anchor,t=this.lead;return this.$isEmpty?o.fromPoints(t,t):this.isBackwards()?o.fromPoints(t,e):o.fromPoints(e,t)},this.clearSelection=function(){this.$isEmpty||(this.$isEmpty=!0,this._emit("changeSelection"))},this.selectAll=function(){this.$setSelection(0,0,Number.MAX_VALUE,Number.MAX_VALUE)},this.setRange=this.setSelectionRange=function(e,t){var n=t?e.end:e.start,r=t?e.start:e.end;this.$setSelection(n.row,n.column,r.row,r.column)},this.$setSelection=function(e,t,n,r){var i=this.$isEmpty;this.$silent=!0,this.$cursorChanged=this.$anchorChanged=!1,this.anchor.setPosition(e,t),this.cursor.setPosition(n,r),this.$isEmpty=!o.comparePoints(this.anchor,this.cursor),this.$silent=!1,this.$cursorChanged&&this._emit("changeCursor"),(this.$cursorChanged||this.$anchorChanged)&&this._emit("changeSelection")},this.$moveSelection=function(e){var t=this.lead;this.$isEmpty&&this.setSelectionAnchor(t.row,t.column),e.call(this)},this.selectTo=function(e,t){this.$moveSelection(function(){this.moveCursorTo(e,t)})},this.selectToPosition=function(e){this.$moveSelection(function(){this.moveCursorToPosition(e)})},this.moveTo=function(e,t){this.clearSelection(),this.moveCursorTo(e,t)},this.moveToPosition=function(e){this.clearSelection(),this.moveCursorToPosition(e)},this.selectUp=function(){this.$moveSelection(this.moveCursorUp)},this.selectDown=function(){this.$moveSelection(this.moveCursorDown)},this.selectRight=function(){this.$moveSelection(this.moveCursorRight)},this.selectLeft=function(){this.$moveSelection(this.moveCursorLeft)},this.selectLineStart=function(){this.$moveSelection(this.moveCursorLineStart)},this.selectLineEnd=function(){this.$moveSelection(this.moveCursorLineEnd)},this.selectFileEnd=function(){this.$moveSelection(this.moveCursorFileEnd)},this.selectFileStart=function(){this.$moveSelection(this.moveCursorFileStart)},this.selectWordRight=function(){this.$moveSelection(this.moveCursorWordRight)},this.selectWordLeft=function(){this.$moveSelection(this.moveCursorWordLeft)},this.getWordRange=function(e,t){if(typeof t=="undefined"){var n=e||this.lead;e=n.row,t=n.column}return this.session.getWordRange(e,t)},this.selectWord=function(){this.setSelectionRange(this.getWordRange())},this.selectAWord=function(){var e=this.getCursor(),t=this.session.getAWordRange(e.row,e.column);this.setSelectionRange(t)},this.getLineRange=function(e,t){var n=typeof e=="number"?e:this.lead.row,r,i=this.session.getFoldLine(n);return i?(n=i.start.row,r=i.end.row):r=n,t===!0?new o(n,0,r,this.session.getLine(r).length):new o(n,0,r+1,0)},this.selectLine=function(){this.setSelectionRange(this.getLineRange())},this.moveCursorUp=function(){this.moveCursorBy(-1,0)},this.moveCursorDown=function(){this.moveCursorBy(1,0)},this.wouldMoveIntoSoftTab=function(e,t,n){var r=e.column,i=e.column+t;return n<0&&(r=e.column-t,i=e.column),this.session.isTabStop(e)&&this.doc.getLine(e.row).slice(r,i).split(" ").length-1==t},this.moveCursorLeft=function(){var e=this.lead.getPosition(),t;if(t=this.session.getFoldAt(e.row,e.column,-1))this.moveCursorTo(t.start.row,t.start.column);else if(e.column===0)e.row>0&&this.moveCursorTo(e.row-1,this.doc.getLine(e.row-1).length);else{var n=this.session.getTabSize();this.wouldMoveIntoSoftTab(e,n,-1)&&!this.session.getNavigateWithinSoftTabs()?this.moveCursorBy(0,-n):this.moveCursorBy(0,-1)}},this.moveCursorRight=function(){var e=this.lead.getPosition(),t;if(t=this.session.getFoldAt(e.row,e.column,1))this.moveCursorTo(t.end.row,t.end.column);else if(this.lead.column==this.doc.getLine(this.lead.row).length)this.lead.row<this.doc.getLength()-1&&this.moveCursorTo(this.lead.row+1,0);else{var n=this.session.getTabSize(),e=this.lead;this.wouldMoveIntoSoftTab(e,n,1)&&!this.session.getNavigateWithinSoftTabs()?this.moveCursorBy(0,n):this.moveCursorBy(0,1)}},this.moveCursorLineStart=function(){var e=this.lead.row,t=this.lead.column,n=this.session.documentToScreenRow(e,t),r=this.session.screenToDocumentPosition(n,0),i=this.session.getDisplayLine(e,null,r.row,r.column),s=i.match(/^\s*/);s[0].length!=t&&!this.session.$useEmacsStyleLineStart&&(r.column+=s[0].length),this.moveCursorToPosition(r)},this.moveCursorLineEnd=function(){var e=this.lead,t=this.session.getDocumentLastRowColumnPosition(e.row,e.column);if(this.lead.column==t.column){var n=this.session.getLine(t.row);if(t.column==n.length){var r=n.search(/\s+$/);r>0&&(t.column=r)}}this.moveCursorTo(t.row,t.column)},this.moveCursorFileEnd=function(){var e=this.doc.getLength()-1,t=this.doc.getLine(e).length;this.moveCursorTo(e,t)},this.moveCursorFileStart=function(){this.moveCursorTo(0,0)},this.moveCursorLongWordRight=function(){var e=this.lead.row,t=this.lead.column,n=this.doc.getLine(e),r=n.substring(t);this.session.nonTokenRe.lastIndex=0,this.session.tokenRe.lastIndex=0;var i=this.session.getFoldAt(e,t,1);if(i){this.moveCursorTo(i.end.row,i.end.column);return}this.session.nonTokenRe.exec(r)&&(t+=this.session.nonTokenRe.lastIndex,this.session.nonTokenRe.lastIndex=0,r=n.substring(t));if(t>=n.length){this.moveCursorTo(e,n.length),this.moveCursorRight(),e<this.doc.getLength()-1&&this.moveCursorWordRight();return}this.session.tokenRe.exec(r)&&(t+=this.session.tokenRe.lastIndex,this.session.tokenRe.lastIndex=0),this.moveCursorTo(e,t)},this.moveCursorLongWordLeft=function(){var e=this.lead.row,t=this.lead.column,n;if(n=this.session.getFoldAt(e,t,-1)){this.moveCursorTo(n.start.row,n.start.column);return}var r=this.session.getFoldStringAt(e,t,-1);r==null&&(r=this.doc.getLine(e).substring(0,t));var s=i.stringReverse(r);this.session.nonTokenRe.lastIndex=0,this.session.tokenRe.lastIndex=0,this.session.nonTokenRe.exec(s)&&(t-=this.session.nonTokenRe.lastIndex,s=s.slice(this.session.nonTokenRe.lastIndex),this.session.nonTokenRe.lastIndex=0);if(t<=0){this.moveCursorTo(e,0),this.moveCursorLeft(),e>0&&this.moveCursorWordLeft();return}this.session.tokenRe.exec(s)&&(t-=this.session.tokenRe.lastIndex,this.session.tokenRe.lastIndex=0),this.moveCursorTo(e,t)},this.$shortWordEndIndex=function(e){var t=0,n,r=/\s/,i=this.session.tokenRe;i.lastIndex=0;if(this.session.tokenRe.exec(e))t=this.session.tokenRe.lastIndex;else{while((n=e[t])&&r.test(n))t++;if(t<1){i.lastIndex=0;while((n=e[t])&&!i.test(n)){i.lastIndex=0,t++;if(r.test(n)){if(t>2){t--;break}while((n=e[t])&&r.test(n))t++;if(t>2)break}}}}return i.lastIndex=0,t},this.moveCursorShortWordRight=function(){var e=this.lead.row,t=this.lead.column,n=this.doc.getLine(e),r=n.substring(t),i=this.session.getFoldAt(e,t,1);if(i)return this.moveCursorTo(i.end.row,i.end.column);if(t==n.length){var s=this.doc.getLength();do e++,r=this.doc.getLine(e);while(e<s&&/^\s*$/.test(r));/^\s+/.test(r)||(r=""),t=0}var o=this.$shortWordEndIndex(r);this.moveCursorTo(e,t+o)},this.moveCursorShortWordLeft=function(){var e=this.lead.row,t=this.lead.column,n;if(n=this.session.getFoldAt(e,t,-1))return this.moveCursorTo(n.start.row,n.start.column);var r=this.session.getLine(e).substring(0,t);if(t===0){do e--,r=this.doc.getLine(e);while(e>0&&/^\s*$/.test(r));t=r.length,/\s+$/.test(r)||(r="")}var s=i.stringReverse(r),o=this.$shortWordEndIndex(s);return this.moveCursorTo(e,t-o)},this.moveCursorWordRight=function(){this.session.$selectLongWords?this.moveCursorLongWordRight():this.moveCursorShortWordRight()},this.moveCursorWordLeft=function(){this.session.$selectLongWords?this.moveCursorLongWordLeft():this.moveCursorShortWordLeft()},this.moveCursorBy=function(e,t){var n=this.session.documentToScreenPosition(this.lead.row,this.lead.column),r;t===0&&(e!==0&&(this.session.$bidiHandler.isBidiRow(n.row,this.lead.row)?(r=this.session.$bidiHandler.getPosLeft(n.column),n.column=Math.round(r/this.session.$bidiHandler.charWidths[0])):r=n.column*this.session.$bidiHandler.charWidths[0]),this.$desiredColumn?n.column=this.$desiredColumn:this.$desiredColumn=n.column);var i=this.session.screenToDocumentPosition(n.row+e,n.column,r);e!==0&&t===0&&i.row===this.lead.row&&i.column===this.lead.column&&this.session.lineWidgets&&this.session.lineWidgets[i.row]&&(i.row>0||e>0)&&i.row++,this.moveCursorTo(i.row,i.column+t,t===0)},this.moveCursorToPosition=function(e){this.moveCursorTo(e.row,e.column)},this.moveCursorTo=function(e,t,n){var r=this.session.getFoldAt(e,t,1);r&&(e=r.start.row,t=r.start.column),this.$keepDesiredColumnOnChange=!0;var i=this.session.getLine(e);/[\uDC00-\uDFFF]/.test(i.charAt(t))&&i.charAt(t-1)&&(this.lead.row==e&&this.lead.column==t+1?t-=1:t+=1),this.lead.setPosition(e,t),this.$keepDesiredColumnOnChange=!1,n||(this.$desiredColumn=null)},this.moveCursorToScreen=function(e,t,n){var r=this.session.screenToDocumentPosition(e,t);this.moveCursorTo(r.row,r.column,n)},this.detach=function(){this.lead.detach(),this.anchor.detach(),this.session=this.doc=null},this.fromOrientedRange=function(e){this.setSelectionRange(e,e.cursor==e.start),this.$desiredColumn=e.desiredColumn||this.$desiredColumn},this.toOrientedRange=function(e){var t=this.getRange();return e?(e.start.column=t.start.column,e.start.row=t.start.row,e.end.column=t.end.column,e.end.row=t.end.row):e=t,e.cursor=this.isBackwards()?e.start:e.end,e.desiredColumn=this.$desiredColumn,e},this.getRangeOfMovements=function(e){var t=this.getCursor();try{e(this);var n=this.getCursor();return o.fromPoints(t,n)}catch(r){return o.fromPoints(t,t)}finally{this.moveCursorToPosition(t)}},this.toJSON=function(){if(this.rangeCount)var e=this.ranges.map(function(e){var t=e.clone();return t.isBackwards=e.cursor==e.start,t});else{var e=this.getRange();e.isBackwards=this.isBackwards()}return e},this.fromJSON=function(e){if(e.start==undefined){if(this.rangeList){this.toSingleRange(e[0]);for(var t=e.length;t--;){var n=o.fromPoints(e[t].start,e[t].end);e[t].isBackwards&&(n.cursor=n.start),this.addRange(n,!0)}return}e=e[0]}this.rangeList&&this.toSingleRange(e),this.setSelectionRange(e,e.isBackwards)},this.isEqual=function(e){if((e.length||this.rangeCount)&&e.length!=this.rangeCount)return!1;if(!e.length||!this.ranges)return this.getRange().isEqual(e);for(var t=this.ranges.length;t--;)if(!this.ranges[t].isEqual(e[t]))return!1;return!0}}).call(u.prototype),t.Selection=u}),ace.define("ace/tokenizer",["require","exports","module","ace/config"],function(e,t,n){"use strict";var r=e("./config"),i=2e3,s=function(e){this.states=e,this.regExps={},this.matchMappings={};for(var t in this.states){var n=this.states[t],r=[],i=0,s=this.matchMappings[t]={defaultToken:"text"},o="g",u=[];for(var a=0;a<n.length;a++){var f=n[a];f.defaultToken&&(s.defaultToken=f.defaultToken),f.caseInsensitive&&(o="gi");if(f.regex==null)continue;f.regex instanceof RegExp&&(f.regex=f.regex.toString().slice(1,-1));var l=f.regex,c=(new RegExp("(?:("+l+")|(.))")).exec("a").length-2;Array.isArray(f.token)?f.token.length==1||c==1?f.token=f.token[0]:c-1!=f.token.length?(this.reportError("number of classes and regexp groups doesn't match",{rule:f,groupCount:c-1}),f.token=f.token[0]):(f.tokenArray=f.token,f.token=null,f.onMatch=this.$arrayTokens):typeof f.token=="function"&&!f.onMatch&&(c>1?f.onMatch=this.$applyToken:f.onMatch=f.token),c>1&&(/\\\d/.test(f.regex)?l=f.regex.replace(/\\([0-9]+)/g,function(e,t){return"\\"+(parseInt(t,10)+i+1)}):(c=1,l=this.removeCapturingGroups(f.regex)),!f.splitRegex&&typeof f.token!="string"&&u.push(f)),s[i]=a,i+=c,r.push(l),f.onMatch||(f.onMatch=null)}r.length||(s[0]=0,r.push("$")),u.forEach(function(e){e.splitRegex=this.createSplitterRegexp(e.regex,o)},this),this.regExps[t]=new RegExp("("+r.join(")|(")+")|($)",o)}};(function(){this.$setMaxTokenCount=function(e){i=e|0},this.$applyToken=function(e){var t=this.splitRegex.exec(e).slice(1),n=this.token.apply(this,t);if(typeof n=="string")return[{type:n,value:e}];var r=[];for(var i=0,s=n.length;i<s;i++)t[i]&&(r[r.length]={type:n[i],value:t[i]});return r},this.$arrayTokens=function(e){if(!e)return[];var t=this.splitRegex.exec(e);if(!t)return"text";var n=[],r=this.tokenArray;for(var i=0,s=r.length;i<s;i++)t[i+1]&&(n[n.length]={type:r[i],value:t[i+1]});return n},this.removeCapturingGroups=function(e){var t=e.replace(/\[(?:\\.|[^\]])*?\]|\\.|\(\?[:=!]|(\()/g,function(e,t){return t?"(?:":e});return t},this.createSplitterRegexp=function(e,t){if(e.indexOf("(?=")!=-1){var n=0,r=!1,i={};e.replace(/(\\.)|(\((?:\?[=!])?)|(\))|([\[\]])/g,function(e,t,s,o,u,a){return r?r=u!="]":u?r=!0:o?(n==i.stack&&(i.end=a+1,i.stack=-1),n--):s&&(n++,s.length!=1&&(i.stack=n,i.start=a)),e}),i.end!=null&&/^\)*$/.test(e.substr(i.end))&&(e=e.substring(0,i.start)+e.substr(i.end))}return e.charAt(0)!="^"&&(e="^"+e),e.charAt(e.length-1)!="$"&&(e+="$"),new RegExp(e,(t||"").replace("g",""))},this.getLineTokens=function(e,t){if(t&&typeof t!="string"){var n=t.slice(0);t=n[0],t==="#tmp"&&(n.shift(),t=n.shift())}else var n=[];var r=t||"start",s=this.states[r];s||(r="start",s=this.states[r]);var o=this.matchMappings[r],u=this.regExps[r];u.lastIndex=0;var a,f=[],l=0,c=0,h={type:null,value:""};while(a=u.exec(e)){var p=o.defaultToken,d=null,v=a[0],m=u.lastIndex;if(m-v.length>l){var g=e.substring(l,m-v.length);h.type==p?h.value+=g:(h.type&&f.push(h),h={type:p,value:g})}for(var y=0;y<a.length-2;y++){if(a[y+1]===undefined)continue;d=s[o[y]],d.onMatch?p=d.onMatch(v,r,n,e):p=d.token,d.next&&(typeof d.next=="string"?r=d.next:r=d.next(r,n),s=this.states[r],s||(this.reportError("state doesn't exist",r),r="start",s=this.states[r]),o=this.matchMappings[r],l=m,u=this.regExps[r],u.lastIndex=m),d.consumeLineEnd&&(l=m);break}if(v)if(typeof p=="string")!!d&&d.merge===!1||h.type!==p?(h.type&&f.push(h),h={type:p,value:v}):h.value+=v;else if(p){h.type&&f.push(h),h={type:null,value:""};for(var y=0;y<p.length;y++)f.push(p[y])}if(l==e.length)break;l=m;if(c++>i){c>2*e.length&&this.reportError("infinite loop with in ace tokenizer",{startState:t,line:e});while(l<e.length)h.type&&f.push(h),h={value:e.substring(l,l+=2e3),type:"overflow"};r="start",n=[];break}}return h.type&&f.push(h),n.length>1&&n[0]!==r&&n.unshift("#tmp",r),{tokens:f,state:n.length?n:r}},this.reportError=r.reportError}).call(s.prototype),t.Tokenizer=s}),ace.define("ace/mode/text_highlight_rules",["require","exports","module","ace/lib/lang"],function(e,t,n){"use strict";var r=e("../lib/lang"),i=function(){this.$rules={start:[{token:"empty_line",regex:"^$"},{defaultToken:"text"}]}};(function(){this.addRules=function(e,t){if(!t){for(var n in e)this.$rules[n]=e[n];return}for(var n in e){var r=e[n];for(var i=0;i<r.length;i++){var s=r[i];if(s.next||s.onMatch)typeof s.next=="string"&&s.next.indexOf(t)!==0&&(s.next=t+s.next),s.nextState&&s.nextState.indexOf(t)!==0&&(s.nextState=t+s.nextState)}this.$rules[t+n]=r}},this.getRules=function(){return this.$rules},this.embedRules=function(e,t,n,i,s){var o=typeof e=="function"?(new e).getRules():e;if(i)for(var u=0;u<i.length;u++)i[u]=t+i[u];else{i=[];for(var a in o)i.push(t+a)}this.addRules(o,t);if(n){var f=Array.prototype[s?"push":"unshift"];for(var u=0;u<i.length;u++)f.apply(this.$rules[i[u]],r.deepCopy(n))}this.$embeds||(this.$embeds=[]),this.$embeds.push(t)},this.getEmbeds=function(){return this.$embeds};var e=function(e,t){return(e!="start"||t.length)&&t.unshift(this.nextState,e),this.nextState},t=function(e,t){return t.shift(),t.shift()||"start"};this.normalizeRules=function(){function i(s){var o=r[s];o.processed=!0;for(var u=0;u<o.length;u++){var a=o[u],f=null;Array.isArray(a)&&(f=a,a={}),!a.regex&&a.start&&(a.regex=a.start,a.next||(a.next=[]),a.next.push({defaultToken:a.token},{token:a.token+".end",regex:a.end||a.start,next:"pop"}),a.token=a.token+".start",a.push=!0);var l=a.next||a.push;if(l&&Array.isArray(l)){var c=a.stateName;c||(c=a.token,typeof c!="string"&&(c=c[0]||""),r[c]&&(c+=n++)),r[c]=l,a.next=c,i(c)}else l=="pop"&&(a.next=t);a.push&&(a.nextState=a.next||a.push,a.next=e,delete a.push);if(a.rules)for(var h in a.rules)r[h]?r[h].push&&r[h].push.apply(r[h],a.rules[h]):r[h]=a.rules[h];var p=typeof a=="string"?a:a.include;p&&(Array.isArray(p)?f=p.map(function(e){return r[e]}):f=r[p]);if(f){var d=[u,1].concat(f);a.noEscape&&(d=d.filter(function(e){return!e.next})),o.splice.apply(o,d),u--}a.keywordMap&&(a.token=this.createKeywordMapper(a.keywordMap,a.defaultToken||"text",a.caseInsensitive),delete a.defaultToken)}}var n=0,r=this.$rules;Object.keys(r).forEach(i,this)},this.createKeywordMapper=function(e,t,n,r){var i=Object.create(null);return Object.keys(e).forEach(function(t){var s=e[t];n&&(s=s.toLowerCase());var o=s.split(r||"|");for(var u=o.length;u--;)i[o[u]]=t}),Object.getPrototypeOf(i)&&(i.__proto__=null),this.$keywordList=Object.keys(i),e=null,n?function(e){return i[e.toLowerCase()]||t}:function(e){return i[e]||t}},this.getKeywords=function(){return this.$keywords}}).call(i.prototype),t.TextHighlightRules=i}),ace.define("ace/mode/behaviour",["require","exports","module"],function(e,t,n){"use strict";var r=function(){this.$behaviours={}};(function(){this.add=function(e,t,n){switch(undefined){case this.$behaviours:this.$behaviours={};case this.$behaviours[e]:this.$behaviours[e]={}}this.$behaviours[e][t]=n},this.addBehaviours=function(e){for(var t in e)for(var n in e[t])this.add(t,n,e[t][n])},this.remove=function(e){this.$behaviours&&this.$behaviours[e]&&delete this.$behaviours[e]},this.inherit=function(e,t){if(typeof e=="function")var n=(new e).getBehaviours(t);else var n=e.getBehaviours(t);this.addBehaviours(n)},this.getBehaviours=function(e){if(!e)return this.$behaviours;var t={};for(var n=0;n<e.length;n++)this.$behaviours[e[n]]&&(t[e[n]]=this.$behaviours[e[n]]);return t}}).call(r.prototype),t.Behaviour=r}),ace.define("ace/token_iterator",["require","exports","module","ace/range"],function(e,t,n){"use strict";var r=e("./range").Range,i=function(e,t,n){this.$session=e,this.$row=t,this.$rowTokens=e.getTokens(t);var r=e.getTokenAt(t,n);this.$tokenIndex=r?r.index:-1};(function(){this.stepBackward=function(){this.$tokenIndex-=1;while(this.$tokenIndex<0){this.$row-=1;if(this.$row<0)return this.$row=0,null;this.$rowTokens=this.$session.getTokens(this.$row),this.$tokenIndex=this.$rowTokens.length-1}return this.$rowTokens[this.$tokenIndex]},this.stepForward=function(){this.$tokenIndex+=1;var e;while(this.$tokenIndex>=this.$rowTokens.length){this.$row+=1,e||(e=this.$session.getLength());if(this.$row>=e)return this.$row=e-1,null;this.$rowTokens=this.$session.getTokens(this.$row),this.$tokenIndex=0}return this.$rowTokens[this.$tokenIndex]},this.getCurrentToken=function(){return this.$rowTokens[this.$tokenIndex]},this.getCurrentTokenRow=function(){return this.$row},this.getCurrentTokenColumn=function(){var e=this.$rowTokens,t=this.$tokenIndex,n=e[t].start;if(n!==undefined)return n;n=0;while(t>0)t-=1,n+=e[t].value.length;return n},this.getCurrentTokenPosition=function(){return{row:this.$row,column:this.getCurrentTokenColumn()}},this.getCurrentTokenRange=function(){var e=this.$rowTokens[this.$tokenIndex],t=this.getCurrentTokenColumn();return new r(this.$row,t,this.$row,t+e.value.length)}}).call(i.prototype),t.TokenIterator=i}),ace.define("ace/mode/behaviour/cstyle",["require","exports","module","ace/lib/oop","ace/mode/behaviour","ace/token_iterator","ace/lib/lang"],function(e,t,n){"use strict";var r=e("../../lib/oop"),i=e("../behaviour").Behaviour,s=e("../../token_iterator").TokenIterator,o=e("../../lib/lang"),u=["text","paren.rparen","punctuation.operator"],a=["text","paren.rparen","punctuation.operator","comment"],f,l={},c={'"':'"',"'":"'"},h=function(e){var t=-1;e.multiSelect&&(t=e.selection.index,l.rangeCount!=e.multiSelect.rangeCount&&(l={rangeCount:e.multiSelect.rangeCount}));if(l[t])return f=l[t];f=l[t]={autoInsertedBrackets:0,autoInsertedRow:-1,autoInsertedLineEnd:"",maybeInsertedBrackets:0,maybeInsertedRow:-1,maybeInsertedLineStart:"",maybeInsertedLineEnd:""}},p=function(e,t,n,r){var i=e.end.row-e.start.row;return{text:n+t+r,selection:[0,e.start.column+1,i,e.end.column+(i?0:1)]}},d=function(e){this.add("braces","insertion",function(t,n,r,i,s){var u=r.getCursorPosition(),a=i.doc.getLine(u.row);if(s=="{"){h(r);var l=r.getSelectionRange(),c=i.doc.getTextRange(l);if(c!==""&&c!=="{"&&r.getWrapBehavioursEnabled())return p(l,c,"{","}");if(d.isSaneInsertion(r,i))return/[\]\}\)]/.test(a[u.column])||r.inMultiSelectMode||e&&e.braces?(d.recordAutoInsert(r,i,"}"),{text:"{}",selection:[1,1]}):(d.recordMaybeInsert(r,i,"{"),{text:"{",selection:[1,1]})}else if(s=="}"){h(r);var v=a.substring(u.column,u.column+1);if(v=="}"){var m=i.$findOpeningBracket("}",{column:u.column+1,row:u.row});if(m!==null&&d.isAutoInsertedClosing(u,a,s))return d.popAutoInsertedClosing(),{text:"",selection:[1,1]}}}else{if(s=="\n"||s=="\r\n"){h(r);var g="";d.isMaybeInsertedClosing(u,a)&&(g=o.stringRepeat("}",f.maybeInsertedBrackets),d.clearMaybeInsertedClosing());var v=a.substring(u.column,u.column+1);if(v==="}"){var y=i.findMatchingBracket({row:u.row,column:u.column+1},"}");if(!y)return null;var b=this.$getIndent(i.getLine(y.row))}else{if(!g){d.clearMaybeInsertedClosing();return}var b=this.$getIndent(a)}var w=b+i.getTabString();return{text:"\n"+w+"\n"+b+g,selection:[1,w.length,1,w.length]}}d.clearMaybeInsertedClosing()}}),this.add("braces","deletion",function(e,t,n,r,i){var s=r.doc.getTextRange(i);if(!i.isMultiLine()&&s=="{"){h(n);var o=r.doc.getLine(i.start.row),u=o.substring(i.end.column,i.end.column+1);if(u=="}")return i.end.column++,i;f.maybeInsertedBrackets--}}),this.add("parens","insertion",function(e,t,n,r,i){if(i=="("){h(n);var s=n.getSelectionRange(),o=r.doc.getTextRange(s);if(o!==""&&n.getWrapBehavioursEnabled())return p(s,o,"(",")");if(d.isSaneInsertion(n,r))return d.recordAutoInsert(n,r,")"),{text:"()",selection:[1,1]}}else if(i==")"){h(n);var u=n.getCursorPosition(),a=r.doc.getLine(u.row),f=a.substring(u.column,u.column+1);if(f==")"){var l=r.$findOpeningBracket(")",{column:u.column+1,row:u.row});if(l!==null&&d.isAutoInsertedClosing(u,a,i))return d.popAutoInsertedClosing(),{text:"",selection:[1,1]}}}}),this.add("parens","deletion",function(e,t,n,r,i){var s=r.doc.getTextRange(i);if(!i.isMultiLine()&&s=="("){h(n);var o=r.doc.getLine(i.start.row),u=o.substring(i.start.column+1,i.start.column+2);if(u==")")return i.end.column++,i}}),this.add("brackets","insertion",function(e,t,n,r,i){if(i=="["){h(n);var s=n.getSelectionRange(),o=r.doc.getTextRange(s);if(o!==""&&n.getWrapBehavioursEnabled())return p(s,o,"[","]");if(d.isSaneInsertion(n,r))return d.recordAutoInsert(n,r,"]"),{text:"[]",selection:[1,1]}}else if(i=="]"){h(n);var u=n.getCursorPosition(),a=r.doc.getLine(u.row),f=a.substring(u.column,u.column+1);if(f=="]"){var l=r.$findOpeningBracket("]",{column:u.column+1,row:u.row});if(l!==null&&d.isAutoInsertedClosing(u,a,i))return d.popAutoInsertedClosing(),{text:"",selection:[1,1]}}}}),this.add("brackets","deletion",function(e,t,n,r,i){var s=r.doc.getTextRange(i);if(!i.isMultiLine()&&s=="["){h(n);var o=r.doc.getLine(i.start.row),u=o.substring(i.start.column+1,i.start.column+2);if(u=="]")return i.end.column++,i}}),this.add("string_dquotes","insertion",function(e,t,n,r,i){var s=r.$mode.$quotes||c;if(i.length==1&&s[i]){if(this.lineCommentStart&&this.lineCommentStart.indexOf(i)!=-1)return;h(n);var o=i,u=n.getSelectionRange(),a=r.doc.getTextRange(u);if(a!==""&&(a.length!=1||!s[a])&&n.getWrapBehavioursEnabled())return p(u,a,o,o);if(!a){var f=n.getCursorPosition(),l=r.doc.getLine(f.row),d=l.substring(f.column-1,f.column),v=l.substring(f.column,f.column+1),m=r.getTokenAt(f.row,f.column),g=r.getTokenAt(f.row,f.column+1);if(d=="\\"&&m&&/escape/.test(m.type))return null;var y=m&&/string|escape/.test(m.type),b=!g||/string|escape/.test(g.type),w;if(v==o)w=y!==b,w&&/string\.end/.test(g.type)&&(w=!1);else{if(y&&!b)return null;if(y&&b)return null;var E=r.$mode.tokenRe;E.lastIndex=0;var S=E.test(d);E.lastIndex=0;var x=E.test(d);if(S||x)return null;if(v&&!/[\s;,.})\]\\]/.test(v))return null;w=!0}return{text:w?o+o:"",selection:[1,1]}}}}),this.add("string_dquotes","deletion",function(e,t,n,r,i){var s=r.doc.getTextRange(i);if(!i.isMultiLine()&&(s=='"'||s=="'")){h(n);var o=r.doc.getLine(i.start.row),u=o.substring(i.start.column+1,i.start.column+2);if(u==s)return i.end.column++,i}})};d.isSaneInsertion=function(e,t){var n=e.getCursorPosition(),r=new s(t,n.row,n.column);if(!this.$matchTokenType(r.getCurrentToken()||"text",u)){var i=new s(t,n.row,n.column+1);if(!this.$matchTokenType(i.getCurrentToken()||"text",u))return!1}return r.stepForward(),r.getCurrentTokenRow()!==n.row||this.$matchTokenType(r.getCurrentToken()||"text",a)},d.$matchTokenType=function(e,t){return t.indexOf(e.type||e)>-1},d.recordAutoInsert=function(e,t,n){var r=e.getCursorPosition(),i=t.doc.getLine(r.row);this.isAutoInsertedClosing(r,i,f.autoInsertedLineEnd[0])||(f.autoInsertedBrackets=0),f.autoInsertedRow=r.row,f.autoInsertedLineEnd=n+i.substr(r.column),f.autoInsertedBrackets++},d.recordMaybeInsert=function(e,t,n){var r=e.getCursorPosition(),i=t.doc.getLine(r.row);this.isMaybeInsertedClosing(r,i)||(f.maybeInsertedBrackets=0),f.maybeInsertedRow=r.row,f.maybeInsertedLineStart=i.substr(0,r.column)+n,f.maybeInsertedLineEnd=i.substr(r.column),f.maybeInsertedBrackets++},d.isAutoInsertedClosing=function(e,t,n){return f.autoInsertedBrackets>0&&e.row===f.autoInsertedRow&&n===f.autoInsertedLineEnd[0]&&t.substr(e.column)===f.autoInsertedLineEnd},d.isMaybeInsertedClosing=function(e,t){return f.maybeInsertedBrackets>0&&e.row===f.maybeInsertedRow&&t.substr(e.column)===f.maybeInsertedLineEnd&&t.substr(0,e.column)==f.maybeInsertedLineStart},d.popAutoInsertedClosing=function(){f.autoInsertedLineEnd=f.autoInsertedLineEnd.substr(1),f.autoInsertedBrackets--},d.clearMaybeInsertedClosing=function(){f&&(f.maybeInsertedBrackets=0,f.maybeInsertedRow=-1)},r.inherits(d,i),t.CstyleBehaviour=d}),ace.define("ace/unicode",["require","exports","module"],function(e,t,n){"use strict";var r=[48,9,8,25,5,0,2,25,48,0,11,0,5,0,6,22,2,30,2,457,5,11,15,4,8,0,2,0,18,116,2,1,3,3,9,0,2,2,2,0,2,19,2,82,2,138,2,4,3,155,12,37,3,0,8,38,10,44,2,0,2,1,2,1,2,0,9,26,6,2,30,10,7,61,2,9,5,101,2,7,3,9,2,18,3,0,17,58,3,100,15,53,5,0,6,45,211,57,3,18,2,5,3,11,3,9,2,1,7,6,2,2,2,7,3,1,3,21,2,6,2,0,4,3,3,8,3,1,3,3,9,0,5,1,2,4,3,11,16,2,2,5,5,1,3,21,2,6,2,1,2,1,2,1,3,0,2,4,5,1,3,2,4,0,8,3,2,0,8,15,12,2,2,8,2,2,2,21,2,6,2,1,2,4,3,9,2,2,2,2,3,0,16,3,3,9,18,2,2,7,3,1,3,21,2,6,2,1,2,4,3,8,3,1,3,2,9,1,5,1,2,4,3,9,2,0,17,1,2,5,4,2,2,3,4,1,2,0,2,1,4,1,4,2,4,11,5,4,4,2,2,3,3,0,7,0,15,9,18,2,2,7,2,2,2,22,2,9,2,4,4,7,2,2,2,3,8,1,2,1,7,3,3,9,19,1,2,7,2,2,2,22,2,9,2,4,3,8,2,2,2,3,8,1,8,0,2,3,3,9,19,1,2,7,2,2,2,22,2,15,4,7,2,2,2,3,10,0,9,3,3,9,11,5,3,1,2,17,4,23,2,8,2,0,3,6,4,0,5,5,2,0,2,7,19,1,14,57,6,14,2,9,40,1,2,0,3,1,2,0,3,0,7,3,2,6,2,2,2,0,2,0,3,1,2,12,2,2,3,4,2,0,2,5,3,9,3,1,35,0,24,1,7,9,12,0,2,0,2,0,5,9,2,35,5,19,2,5,5,7,2,35,10,0,58,73,7,77,3,37,11,42,2,0,4,328,2,3,3,6,2,0,2,3,3,40,2,3,3,32,2,3,3,6,2,0,2,3,3,14,2,56,2,3,3,66,5,0,33,15,17,84,13,619,3,16,2,25,6,74,22,12,2,6,12,20,12,19,13,12,2,2,2,1,13,51,3,29,4,0,5,1,3,9,34,2,3,9,7,87,9,42,6,69,11,28,4,11,5,11,11,39,3,4,12,43,5,25,7,10,38,27,5,62,2,28,3,10,7,9,14,0,89,75,5,9,18,8,13,42,4,11,71,55,9,9,4,48,83,2,2,30,14,230,23,280,3,5,3,37,3,5,3,7,2,0,2,0,2,0,2,30,3,52,2,6,2,0,4,2,2,6,4,3,3,5,5,12,6,2,2,6,67,1,20,0,29,0,14,0,17,4,60,12,5,0,4,11,18,0,5,0,3,9,2,0,4,4,7,0,2,0,2,0,2,3,2,10,3,3,6,4,5,0,53,1,2684,46,2,46,2,132,7,6,15,37,11,53,10,0,17,22,10,6,2,6,2,6,2,6,2,6,2,6,2,6,2,6,2,31,48,0,470,1,36,5,2,4,6,1,5,85,3,1,3,2,2,89,2,3,6,40,4,93,18,23,57,15,513,6581,75,20939,53,1164,68,45,3,268,4,27,21,31,3,13,13,1,2,24,9,69,11,1,38,8,3,102,3,1,111,44,25,51,13,68,12,9,7,23,4,0,5,45,3,35,13,28,4,64,15,10,39,54,10,13,3,9,7,22,4,1,5,66,25,2,227,42,2,1,3,9,7,11171,13,22,5,48,8453,301,3,61,3,105,39,6,13,4,6,11,2,12,2,4,2,0,2,1,2,1,2,107,34,362,19,63,3,53,41,11,5,15,17,6,13,1,25,2,33,4,2,134,20,9,8,25,5,0,2,25,12,88,4,5,3,5,3,5,3,2],i=0,s=[];for(var o=0;o<r.length;o+=2)s.push(i+=r[o]),r[o+1]&&s.push(45,i+=r[o+1]);t.wordChars=String.fromCharCode.apply(null,s)}),ace.define("ace/mode/text",["require","exports","module","ace/tokenizer","ace/mode/text_highlight_rules","ace/mode/behaviour/cstyle","ace/unicode","ace/lib/lang","ace/token_iterator","ace/range"],function(e,t,n){"use strict";var r=e("../tokenizer").Tokenizer,i=e("./text_highlight_rules").TextHighlightRules,s=e("./behaviour/cstyle").CstyleBehaviour,o=e("../unicode"),u=e("../lib/lang"),a=e("../token_iterator").TokenIterator,f=e("../range").Range,l=function(){this.HighlightRules=i};(function(){this.$defaultBehaviour=new s,this.tokenRe=new RegExp("^["+o.wordChars+"\\$_]+","g"),this.nonTokenRe=new RegExp("^(?:[^"+o.wordChars+"\\$_]|\\s])+","g"),this.getTokenizer=function(){return this.$tokenizer||(this.$highlightRules=this.$highlightRules||new this.HighlightRules(this.$highlightRuleConfig),this.$tokenizer=new r(this.$highlightRules.getRules())),this.$tokenizer},this.lineCommentStart="",this.blockComment="",this.toggleCommentLines=function(e,t,n,r){function w(e){for(var t=n;t<=r;t++)e(i.getLine(t),t)}var i=t.doc,s=!0,o=!0,a=Infinity,f=t.getTabSize(),l=!1;if(!this.lineCommentStart){if(!this.blockComment)return!1;var c=this.blockComment.start,h=this.blockComment.end,p=new RegExp("^(\\s*)(?:"+u.escapeRegExp(c)+")"),d=new RegExp("(?:"+u.escapeRegExp(h)+")\\s*$"),v=function(e,t){if(g(e,t))return;if(!s||/\S/.test(e))i.insertInLine({row:t,column:e.length},h),i.insertInLine({row:t,column:a},c)},m=function(e,t){var n;(n=e.match(d))&&i.removeInLine(t,e.length-n[0].length,e.length),(n=e.match(p))&&i.removeInLine(t,n[1].length,n[0].length)},g=function(e,n){if(p.test(e))return!0;var r=t.getTokens(n);for(var i=0;i<r.length;i++)if(r[i].type==="comment")return!0}}else{if(Array.isArray(this.lineCommentStart))var p=this.lineCommentStart.map(u.escapeRegExp).join("|"),c=this.lineCommentStart[0];else var p=u.escapeRegExp(this.lineCommentStart),c=this.lineCommentStart;p=new RegExp("^(\\s*)(?:"+p+") ?"),l=t.getUseSoftTabs();var m=function(e,t){var n=e.match(p);if(!n)return;var r=n[1].length,s=n[0].length;!b(e,r,s)&&n[0][s-1]==" "&&s--,i.removeInLine(t,r,s)},y=c+" ",v=function(e,t){if(!s||/\S/.test(e))b(e,a,a)?i.insertInLine({row:t,column:a},y):i.insertInLine({row:t,column:a},c)},g=function(e,t){return p.test(e)},b=function(e,t,n){var r=0;while(t--&&e.charAt(t)==" ")r++;if(r%f!=0)return!1;var r=0;while(e.charAt(n++)==" ")r++;return f>2?r%f!=f-1:r%f==0}}var E=Infinity;w(function(e,t){var n=e.search(/\S/);n!==-1?(n<a&&(a=n),o&&!g(e,t)&&(o=!1)):E>e.length&&(E=e.length)}),a==Infinity&&(a=E,s=!1,o=!1),l&&a%f!=0&&(a=Math.floor(a/f)*f),w(o?m:v)},this.toggleBlockComment=function(e,t,n,r){var i=this.blockComment;if(!i)return;!i.start&&i[0]&&(i=i[0]);var s=new a(t,r.row,r.column),o=s.getCurrentToken(),u=t.selection,l=t.selection.toOrientedRange(),c,h;if(o&&/comment/.test(o.type)){var p,d;while(o&&/comment/.test(o.type)){var v=o.value.indexOf(i.start);if(v!=-1){var m=s.getCurrentTokenRow(),g=s.getCurrentTokenColumn()+v;p=new f(m,g,m,g+i.start.length);break}o=s.stepBackward()}var s=new a(t,r.row,r.column),o=s.getCurrentToken();while(o&&/comment/.test(o.type)){var v=o.value.indexOf(i.end);if(v!=-1){var m=s.getCurrentTokenRow(),g=s.getCurrentTokenColumn()+v;d=new f(m,g,m,g+i.end.length);break}o=s.stepForward()}d&&t.remove(d),p&&(t.remove(p),c=p.start.row,h=-i.start.length)}else h=i.start.length,c=n.start.row,t.insert(n.end,i.end),t.insert(n.start,i.start);l.start.row==c&&(l.start.column+=h),l.end.row==c&&(l.end.column+=h),t.selection.fromOrientedRange(l)},this.getNextLineIndent=function(e,t,n){return this.$getIndent(t)},this.checkOutdent=function(e,t,n){return!1},this.autoOutdent=function(e,t,n){},this.$getIndent=function(e){return e.match(/^\s*/)[0]},this.createWorker=function(e){return null},this.createModeDelegates=function(e){this.$embeds=[],this.$modes={};for(var t in e)e[t]&&(this.$embeds.push(t),this.$modes[t]=new e[t]);var n=["toggleBlockComment","toggleCommentLines","getNextLineIndent","checkOutdent","autoOutdent","transformAction","getCompletions"];for(var t=0;t<n.length;t++)(function(e){var r=n[t],i=e[r];e[n[t]]=function(){return this.$delegator(r,arguments,i)}})(this)},this.$delegator=function(e,t,n){var r=t[0];typeof r!="string"&&(r=r[0]);for(var i=0;i<this.$embeds.length;i++){if(!this.$modes[this.$embeds[i]])continue;var s=r.split(this.$embeds[i]);if(!s[0]&&s[1]){t[0]=s[1];var o=this.$modes[this.$embeds[i]];return o[e].apply(o,t)}}var u=n.apply(this,t);return n?u:undefined},this.transformAction=function(e,t,n,r,i){if(this.$behaviour){var s=this.$behaviour.getBehaviours();for(var o in s)if(s[o][t]){var u=s[o][t].apply(this,arguments);if(u)return u}}},this.getKeywords=function(e){if(!this.completionKeywords){var t=this.$tokenizer.rules,n=[];for(var r in t){var i=t[r];for(var s=0,o=i.length;s<o;s++)if(typeof i[s].token=="string")/keyword|support|storage/.test(i[s].token)&&n.push(i[s].regex);else if(typeof i[s].token=="object")for(var u=0,a=i[s].token.length;u<a;u++)if(/keyword|support|storage/.test(i[s].token[u])){var r=i[s].regex.match(/\(.+?\)/g)[u];n.push(r.substr(1,r.length-2))}}this.completionKeywords=n}return e?n.concat(this.$keywordList||[]):this.$keywordList},this.$createKeywordList=function(){return this.$highlightRules||this.getTokenizer(),this.$keywordList=this.$highlightRules.$keywordList||[]},this.getCompletions=function(e,t,n,r){var i=this.$keywordList||this.$createKeywordList();return i.map(function(e){return{name:e,value:e,score:0,meta:"keyword"}})},this.$id="ace/mode/text"}).call(l.prototype),t.Mode=l}),ace.define("ace/apply_delta",["require","exports","module"],function(e,t,n){"use strict";function r(e,t){throw console.log("Invalid Delta:",e),"Invalid Delta: "+t}function i(e,t){return t.row>=0&&t.row<e.length&&t.column>=0&&t.column<=e[t.row].length}function s(e,t){t.action!="insert"&&t.action!="remove"&&r(t,"delta.action must be 'insert' or 'remove'"),t.lines instanceof Array||r(t,"delta.lines must be an Array"),(!t.start||!t.end)&&r(t,"delta.start/end must be an present");var n=t.start;i(e,t.start)||r(t,"delta.start must be contained in document");var s=t.end;t.action=="remove"&&!i(e,s)&&r(t,"delta.end must contained in document for 'remove' actions");var o=s.row-n.row,u=s.column-(o==0?n.column:0);(o!=t.lines.length-1||t.lines[o].length!=u)&&r(t,"delta.range must match delta lines")}t.applyDelta=function(e,t,n){var r=t.start.row,i=t.start.column,s=e[r]||"";switch(t.action){case"insert":var o=t.lines;if(o.length===1)e[r]=s.substring(0,i)+t.lines[0]+s.substring(i);else{var u=[r,1].concat(t.lines);e.splice.apply(e,u),e[r]=s.substring(0,i)+e[r],e[r+t.lines.length-1]+=s.substring(i)}break;case"remove":var a=t.end.column,f=t.end.row;r===f?e[r]=s.substring(0,i)+s.substring(a):e.splice(r,f-r+1,s.substring(0,i)+e[f].substring(a))}}}),ace.define("ace/anchor",["require","exports","module","ace/lib/oop","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./lib/event_emitter").EventEmitter,s=t.Anchor=function(e,t,n){this.$onChange=this.onChange.bind(this),this.attach(e),typeof n=="undefined"?this.setPosition(t.row,t.column):this.setPosition(t,n)};(function(){function e(e,t,n){var r=n?e.column<=t.column:e.column<t.column;return e.row<t.row||e.row==t.row&&r}function t(t,n,r){var i=t.action=="insert",s=(i?1:-1)*(t.end.row-t.start.row),o=(i?1:-1)*(t.end.column-t.start.column),u=t.start,a=i?u:t.end;return e(n,u,r)?{row:n.row,column:n.column}:e(a,n,!r)?{row:n.row+s,column:n.column+(n.row==a.row?o:0)}:{row:u.row,column:u.column}}r.implement(this,i),this.getPosition=function(){return this.$clipPositionToDocument(this.row,this.column)},this.getDocument=function(){return this.document},this.$insertRight=!1,this.onChange=function(e){if(e.start.row==e.end.row&&e.start.row!=this.row)return;if(e.start.row>this.row)return;var n=t(e,{row:this.row,column:this.column},this.$insertRight);this.setPosition(n.row,n.column,!0)},this.setPosition=function(e,t,n){var r;n?r={row:e,column:t}:r=this.$clipPositionToDocument(e,t);if(this.row==r.row&&this.column==r.column)return;var i={row:this.row,column:this.column};this.row=r.row,this.column=r.column,this._signal("change",{old:i,value:r})},this.detach=function(){this.document.removeEventListener("change",this.$onChange)},this.attach=function(e){this.document=e||this.document,this.document.on("change",this.$onChange)},this.$clipPositionToDocument=function(e,t){var n={};return e>=this.document.getLength()?(n.row=Math.max(0,this.document.getLength()-1),n.column=this.document.getLine(n.row).length):e<0?(n.row=0,n.column=0):(n.row=e,n.column=Math.min(this.document.getLine(n.row).length,Math.max(0,t))),t<0&&(n.column=0),n}}).call(s.prototype)}),ace.define("ace/document",["require","exports","module","ace/lib/oop","ace/apply_delta","ace/lib/event_emitter","ace/range","ace/anchor"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./apply_delta").applyDelta,s=e("./lib/event_emitter").EventEmitter,o=e("./range").Range,u=e("./anchor").Anchor,a=function(e){this.$lines=[""],e.length===0?this.$lines=[""]:Array.isArray(e)?this.insertMergedLines({row:0,column:0},e):this.insert({row:0,column:0},e)};(function(){r.implement(this,s),this.setValue=function(e){var t=this.getLength()-1;this.remove(new o(0,0,t,this.getLine(t).length)),this.insert({row:0,column:0},e)},this.getValue=function(){return this.getAllLines().join(this.getNewLineCharacter())},this.createAnchor=function(e,t){return new u(this,e,t)},"aaa".split(/a/).length===0?this.$split=function(e){return e.replace(/\r\n|\r/g,"\n").split("\n")}:this.$split=function(e){return e.split(/\r\n|\r|\n/)},this.$detectNewLine=function(e){var t=e.match(/^.*?(\r\n|\r|\n)/m);this.$autoNewLine=t?t[1]:"\n",this._signal("changeNewLineMode")},this.getNewLineCharacter=function(){switch(this.$newLineMode){case"windows":return"\r\n";case"unix":return"\n";default:return this.$autoNewLine||"\n"}},this.$autoNewLine="",this.$newLineMode="auto",this.setNewLineMode=function(e){if(this.$newLineMode===e)return;this.$newLineMode=e,this._signal("changeNewLineMode")},this.getNewLineMode=function(){return this.$newLineMode},this.isNewLine=function(e){return e=="\r\n"||e=="\r"||e=="\n"},this.getLine=function(e){return this.$lines[e]||""},this.getLines=function(e,t){return this.$lines.slice(e,t+1)},this.getAllLines=function(){return this.getLines(0,this.getLength())},this.getLength=function(){return this.$lines.length},this.getTextRange=function(e){return this.getLinesForRange(e).join(this.getNewLineCharacter())},this.getLinesForRange=function(e){var t;if(e.start.row===e.end.row)t=[this.getLine(e.start.row).substring(e.start.column,e.end.column)];else{t=this.getLines(e.start.row,e.end.row),t[0]=(t[0]||"").substring(e.start.column);var n=t.length-1;e.end.row-e.start.row==n&&(t[n]=t[n].substring(0,e.end.column))}return t},this.insertLines=function(e,t){return console.warn("Use of document.insertLines is deprecated. Use the insertFullLines method instead."),this.insertFullLines(e,t)},this.removeLines=function(e,t){return console.warn("Use of document.removeLines is deprecated. Use the removeFullLines method instead."),this.removeFullLines(e,t)},this.insertNewLine=function(e){return console.warn("Use of document.insertNewLine is deprecated. Use insertMergedLines(position, ['', '']) instead."),this.insertMergedLines(e,["",""])},this.insert=function(e,t){return this.getLength()<=1&&this.$detectNewLine(t),this.insertMergedLines(e,this.$split(t))},this.insertInLine=function(e,t){var n=this.clippedPos(e.row,e.column),r=this.pos(e.row,e.column+t.length);return this.applyDelta({start:n,end:r,action:"insert",lines:[t]},!0),this.clonePos(r)},this.clippedPos=function(e,t){var n=this.getLength();e===undefined?e=n:e<0?e=0:e>=n&&(e=n-1,t=undefined);var r=this.getLine(e);return t==undefined&&(t=r.length),t=Math.min(Math.max(t,0),r.length),{row:e,column:t}},this.clonePos=function(e){return{row:e.row,column:e.column}},this.pos=function(e,t){return{row:e,column:t}},this.$clipPosition=function(e){var t=this.getLength();return e.row>=t?(e.row=Math.max(0,t-1),e.column=this.getLine(t-1).length):(e.row=Math.max(0,e.row),e.column=Math.min(Math.max(e.column,0),this.getLine(e.row).length)),e},this.insertFullLines=function(e,t){e=Math.min(Math.max(e,0),this.getLength());var n=0;e<this.getLength()?(t=t.concat([""]),n=0):(t=[""].concat(t),e--,n=this.$lines[e].length),this.insertMergedLines({row:e,column:n},t)},this.insertMergedLines=function(e,t){var n=this.clippedPos(e.row,e.column),r={row:n.row+t.length-1,column:(t.length==1?n.column:0)+t[t.length-1].length};return this.applyDelta({start:n,end:r,action:"insert",lines:t}),this.clonePos(r)},this.remove=function(e){var t=this.clippedPos(e.start.row,e.start.column),n=this.clippedPos(e.end.row,e.end.column);return this.applyDelta({start:t,end:n,action:"remove",lines:this.getLinesForRange({start:t,end:n})}),this.clonePos(t)},this.removeInLine=function(e,t,n){var r=this.clippedPos(e,t),i=this.clippedPos(e,n);return this.applyDelta({start:r,end:i,action:"remove",lines:this.getLinesForRange({start:r,end:i})},!0),this.clonePos(r)},this.removeFullLines=function(e,t){e=Math.min(Math.max(0,e),this.getLength()-1),t=Math.min(Math.max(0,t),this.getLength()-1);var n=t==this.getLength()-1&&e>0,r=t<this.getLength()-1,i=n?e-1:e,s=n?this.getLine(i).length:0,u=r?t+1:t,a=r?0:this.getLine(u).length,f=new o(i,s,u,a),l=this.$lines.slice(e,t+1);return this.applyDelta({start:f.start,end:f.end,action:"remove",lines:this.getLinesForRange(f)}),l},this.removeNewLine=function(e){e<this.getLength()-1&&e>=0&&this.applyDelta({start:this.pos(e,this.getLine(e).length),end:this.pos(e+1,0),action:"remove",lines:["",""]})},this.replace=function(e,t){e instanceof o||(e=o.fromPoints(e.start,e.end));if(t.length===0&&e.isEmpty())return e.start;if(t==this.getTextRange(e))return e.end;this.remove(e);var n;return t?n=this.insert(e.start,t):n=e.start,n},this.applyDeltas=function(e){for(var t=0;t<e.length;t++)this.applyDelta(e[t])},this.revertDeltas=function(e){for(var t=e.length-1;t>=0;t--)this.revertDelta(e[t])},this.applyDelta=function(e,t){var n=e.action=="insert";if(n?e.lines.length<=1&&!e.lines[0]:!o.comparePoints(e.start,e.end))return;n&&e.lines.length>2e4&&this.$splitAndapplyLargeDelta(e,2e4),i(this.$lines,e,t),this._signal("change",e)},this.$splitAndapplyLargeDelta=function(e,t){var n=e.lines,r=n.length,i=e.start.row,s=e.start.column,o=0,u=0;do{o=u,u+=t-1;var a=n.slice(o,u);if(u>r){e.lines=a,e.start.row=i+o,e.start.column=s;break}a.push(""),this.applyDelta({start:this.pos(i+o,s),end:this.pos(i+u,s=0),action:e.action,lines:a},!0)}while(!0)},this.revertDelta=function(e){this.applyDelta({start:this.clonePos(e.start),end:this.clonePos(e.end),action:e.action=="insert"?"remove":"insert",lines:e.lines.slice()})},this.indexToPosition=function(e,t){var n=this.$lines||this.getAllLines(),r=this.getNewLineCharacter().length;for(var i=t||0,s=n.length;i<s;i++){e-=n[i].length+r;if(e<0)return{row:i,column:e+n[i].length+r}}return{row:s-1,column:n[s-1].length}},this.positionToIndex=function(e,t){var n=this.$lines||this.getAllLines(),r=this.getNewLineCharacter().length,i=0,s=Math.min(e.row,n.length);for(var o=t||0;o<s;++o)i+=n[o].length+r;return i+e.column}}).call(a.prototype),t.Document=a}),ace.define("ace/background_tokenizer",["require","exports","module","ace/lib/oop","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./lib/event_emitter").EventEmitter,s=function(e,t){this.running=!1,this.lines=[],this.states=[],this.currentLine=0,this.tokenizer=e;var n=this;this.$worker=function(){if(!n.running)return;var e=new Date,t=n.currentLine,r=-1,i=n.doc,s=t;while(n.lines[t])t++;var o=i.getLength(),u=0;n.running=!1;while(t<o){n.$tokenizeRow(t),r=t;do t++;while(n.lines[t]);u++;if(u%5===0&&new Date-e>20){n.running=setTimeout(n.$worker,20);break}}n.currentLine=t,r==-1&&(r=t),s<=r&&n.fireUpdateEvent(s,r)}};(function(){r.implement(this,i),this.setTokenizer=function(e){this.tokenizer=e,this.lines=[],this.states=[],this.start(0)},this.setDocument=function(e){this.doc=e,this.lines=[],this.states=[],this.stop()},this.fireUpdateEvent=function(e,t){var n={first:e,last:t};this._signal("update",{data:n})},this.start=function(e){this.currentLine=Math.min(e||0,this.currentLine,this.doc.getLength()),this.lines.splice(this.currentLine,this.lines.length),this.states.splice(this.currentLine,this.states.length),this.stop(),this.running=setTimeout(this.$worker,700)},this.scheduleStart=function(){this.running||(this.running=setTimeout(this.$worker,700))},this.$updateOnChange=function(e){var t=e.start.row,n=e.end.row-t;if(n===0)this.lines[t]=null;else if(e.action=="remove")this.lines.splice(t,n+1,null),this.states.splice(t,n+1,null);else{var r=Array(n+1);r.unshift(t,1),this.lines.splice.apply(this.lines,r),this.states.splice.apply(this.states,r)}this.currentLine=Math.min(t,this.currentLine,this.doc.getLength()),this.stop()},this.stop=function(){this.running&&clearTimeout(this.running),this.running=!1},this.getTokens=function(e){return this.lines[e]||this.$tokenizeRow(e)},this.getState=function(e){return this.currentLine==e&&this.$tokenizeRow(e),this.states[e]||"start"},this.$tokenizeRow=function(e){var t=this.doc.getLine(e),n=this.states[e-1],r=this.tokenizer.getLineTokens(t,n,e);return this.states[e]+""!=r.state+""?(this.states[e]=r.state,this.lines[e+1]=null,this.currentLine>e+1&&(this.currentLine=e+1)):this.currentLine==e&&(this.currentLine=e+1),this.lines[e]=r.tokens}}).call(s.prototype),t.BackgroundTokenizer=s}),ace.define("ace/search_highlight",["require","exports","module","ace/lib/lang","ace/lib/oop","ace/range"],function(e,t,n){"use strict";var r=e("./lib/lang"),i=e("./lib/oop"),s=e("./range").Range,o=function(e,t,n){this.setRegexp(e),this.clazz=t,this.type=n||"text"};(function(){this.MAX_RANGES=500,this.setRegexp=function(e){if(this.regExp+""==e+"")return;this.regExp=e,this.cache=[]},this.update=function(e,t,n,i){if(!this.regExp)return;var o=i.firstRow,u=i.lastRow;for(var a=o;a<=u;a++){var f=this.cache[a];f==null&&(f=r.getMatchOffsets(n.getLine(a),this.regExp),f.length>this.MAX_RANGES&&(f=f.slice(0,this.MAX_RANGES)),f=f.map(function(e){return new s(a,e.offset,a,e.offset+e.length)}),this.cache[a]=f.length?f:"");for(var l=f.length;l--;)t.drawSingleLineMarker(e,f[l].toScreenRange(n),this.clazz,i)}}}).call(o.prototype),t.SearchHighlight=o}),ace.define("ace/edit_session/fold_line",["require","exports","module","ace/range"],function(e,t,n){"use strict";function i(e,t){this.foldData=e,Array.isArray(t)?this.folds=t:t=this.folds=[t];var n=t[t.length-1];this.range=new r(t[0].start.row,t[0].start.column,n.end.row,n.end.column),this.start=this.range.start,this.end=this.range.end,this.folds.forEach(function(e){e.setFoldLine(this)},this)}var r=e("../range").Range;(function(){this.shiftRow=function(e){this.start.row+=e,this.end.row+=e,this.folds.forEach(function(t){t.start.row+=e,t.end.row+=e})},this.addFold=function(e){if(e.sameRow){if(e.start.row<this.startRow||e.endRow>this.endRow)throw new Error("Can't add a fold to this FoldLine as it has no connection");this.folds.push(e),this.folds.sort(function(e,t){return-e.range.compareEnd(t.start.row,t.start.column)}),this.range.compareEnd(e.start.row,e.start.column)>0?(this.end.row=e.end.row,this.end.column=e.end.column):this.range.compareStart(e.end.row,e.end.column)<0&&(this.start.row=e.start.row,this.start.column=e.start.column)}else if(e.start.row==this.end.row)this.folds.push(e),this.end.row=e.end.row,this.end.column=e.end.column;else{if(e.end.row!=this.start.row)throw new Error("Trying to add fold to FoldRow that doesn't have a matching row");this.folds.unshift(e),this.start.row=e.start.row,this.start.column=e.start.column}e.foldLine=this},this.containsRow=function(e){return e>=this.start.row&&e<=this.end.row},this.walk=function(e,t,n){var r=0,i=this.folds,s,o,u,a=!0;t==null&&(t=this.end.row,n=this.end.column);for(var f=0;f<i.length;f++){s=i[f],o=s.range.compareStart(t,n);if(o==-1){e(null,t,n,r,a);return}u=e(null,s.start.row,s.start.column,r,a),u=!u&&e(s.placeholder,s.start.row,s.start.column,r);if(u||o===0)return;a=!s.sameRow,r=s.end.column}e(null,t,n,r,a)},this.getNextFoldTo=function(e,t){var n,r;for(var i=0;i<this.folds.length;i++){n=this.folds[i],r=n.range.compareEnd(e,t);if(r==-1)return{fold:n,kind:"after"};if(r===0)return{fold:n,kind:"inside"}}return null},this.addRemoveChars=function(e,t,n){var r=this.getNextFoldTo(e,t),i,s;if(r){i=r.fold;if(r.kind=="inside"&&i.start.column!=t&&i.start.row!=e)window.console&&window.console.log(e,t,i);else if(i.start.row==e){s=this.folds;var o=s.indexOf(i);o===0&&(this.start.column+=n);for(o;o<s.length;o++){i=s[o],i.start.column+=n;if(!i.sameRow)return;i.end.column+=n}this.end.column+=n}}},this.split=function(e,t){var n=this.getNextFoldTo(e,t);if(!n||n.kind=="inside")return null;var r=n.fold,s=this.folds,o=this.foldData,u=s.indexOf(r),a=s[u-1];this.end.row=a.end.row,this.end.column=a.end.column,s=s.splice(u,s.length-u);var f=new i(o,s);return o.splice(o.indexOf(this)+1,0,f),f},this.merge=function(e){var t=e.folds;for(var n=0;n<t.length;n++)this.addFold(t[n]);var r=this.foldData;r.splice(r.indexOf(e),1)},this.toString=function(){var e=[this.range.toString()+": ["];return this.folds.forEach(function(t){e.push("  "+t.toString())}),e.push("]"),e.join("\n")},this.idxToPosition=function(e){var t=0;for(var n=0;n<this.folds.length;n++){var r=this.folds[n];e-=r.start.column-t;if(e<0)return{row:r.start.row,column:r.start.column+e};e-=r.placeholder.length;if(e<0)return r.start;t=r.end.column}return{row:this.end.row,column:this.end.column+e}}}).call(i.prototype),t.FoldLine=i}),ace.define("ace/range_list",["require","exports","module","ace/range"],function(e,t,n){"use strict";var r=e("./range").Range,i=r.comparePoints,s=function(){this.ranges=[]};(function(){this.comparePoints=i,this.pointIndex=function(e,t,n){var r=this.ranges;for(var s=n||0;s<r.length;s++){var o=r[s],u=i(e,o.end);if(u>0)continue;var a=i(e,o.start);return u===0?t&&a!==0?-s-2:s:a>0||a===0&&!t?s:-s-1}return-s-1},this.add=function(e){var t=!e.isEmpty(),n=this.pointIndex(e.start,t);n<0&&(n=-n-1);var r=this.pointIndex(e.end,t,n);return r<0?r=-r-1:r++,this.ranges.splice(n,r-n,e)},this.addList=function(e){var t=[];for(var n=e.length;n--;)t.push.apply(t,this.add(e[n]));return t},this.substractPoint=function(e){var t=this.pointIndex(e);if(t>=0)return this.ranges.splice(t,1)},this.merge=function(){var e=[],t=this.ranges;t=t.sort(function(e,t){return i(e.start,t.start)});var n=t[0],r;for(var s=1;s<t.length;s++){r=n,n=t[s];var o=i(r.end,n.start);if(o<0)continue;if(o==0&&!r.isEmpty()&&!n.isEmpty())continue;i(r.end,n.end)<0&&(r.end.row=n.end.row,r.end.column=n.end.column),t.splice(s,1),e.push(n),n=r,s--}return this.ranges=t,e},this.contains=function(e,t){return this.pointIndex({row:e,column:t})>=0},this.containsPoint=function(e){return this.pointIndex(e)>=0},this.rangeAtPoint=function(e){var t=this.pointIndex(e);if(t>=0)return this.ranges[t]},this.clipRows=function(e,t){var n=this.ranges;if(n[0].start.row>t||n[n.length-1].start.row<e)return[];var r=this.pointIndex({row:e,column:0});r<0&&(r=-r-1);var i=this.pointIndex({row:t,column:0},r);i<0&&(i=-i-1);var s=[];for(var o=r;o<i;o++)s.push(n[o]);return s},this.removeAll=function(){return this.ranges.splice(0,this.ranges.length)},this.attach=function(e){this.session&&this.detach(),this.session=e,this.onChange=this.$onChange.bind(this),this.session.on("change",this.onChange)},this.detach=function(){if(!this.session)return;this.session.removeListener("change",this.onChange),this.session=null},this.$onChange=function(e){if(e.action=="insert")var t=e.start,n=e.end;else var n=e.start,t=e.end;var r=t.row,i=n.row,s=i-r,o=-t.column+n.column,u=this.ranges;for(var a=0,f=u.length;a<f;a++){var l=u[a];if(l.end.row<r)continue;if(l.start.row>r)break;l.start.row==r&&l.start.column>=t.column&&(l.start.column!=t.column||!this.$insertRight)&&(l.start.column+=o,l.start.row+=s);if(l.end.row==r&&l.end.column>=t.column){if(l.end.column==t.column&&this.$insertRight)continue;l.end.column==t.column&&o>0&&a<f-1&&l.end.column>l.start.column&&l.end.column==u[a+1].start.column&&(l.end.column-=o),l.end.column+=o,l.end.row+=s}}if(s!=0&&a<f)for(;a<f;a++){var l=u[a];l.start.row+=s,l.end.row+=s}}}).call(s.prototype),t.RangeList=s}),ace.define("ace/edit_session/fold",["require","exports","module","ace/range","ace/range_list","ace/lib/oop"],function(e,t,n){"use strict";function u(e,t){e.row-=t.row,e.row==0&&(e.column-=t.column)}function a(e,t){u(e.start,t),u(e.end,t)}function f(e,t){e.row==0&&(e.column+=t.column),e.row+=t.row}function l(e,t){f(e.start,t),f(e.end,t)}var r=e("../range").Range,i=e("../range_list").RangeList,s=e("../lib/oop"),o=t.Fold=function(e,t){this.foldLine=null,this.placeholder=t,this.range=e,this.start=e.start,this.end=e.end,this.sameRow=e.start.row==e.end.row,this.subFolds=this.ranges=[]};s.inherits(o,i),function(){this.toString=function(){return'"'+this.placeholder+'" '+this.range.toString()},this.setFoldLine=function(e){this.foldLine=e,this.subFolds.forEach(function(t){t.setFoldLine(e)})},this.clone=function(){var e=this.range.clone(),t=new o(e,this.placeholder);return this.subFolds.forEach(function(e){t.subFolds.push(e.clone())}),t.collapseChildren=this.collapseChildren,t},this.addSubFold=function(e){if(this.range.isEqual(e))return;if(!this.range.containsRange(e))throw new Error("A fold can't intersect already existing fold"+e.range+this.range);a(e,this.start);var t=e.start.row,n=e.start.column;for(var r=0,i=-1;r<this.subFolds.length;r++){i=this.subFolds[r].range.compare(t,n);if(i!=1)break}var s=this.subFolds[r];if(i==0)return s.addSubFold(e);var t=e.range.end.row,n=e.range.end.column;for(var o=r,i=-1;o<this.subFolds.length;o++){i=this.subFolds[o].range.compare(t,n);if(i!=1)break}var u=this.subFolds[o];if(i==0)throw new Error("A fold can't intersect already existing fold"+e.range+this.range);var f=this.subFolds.splice(r,o-r,e);return e.setFoldLine(this.foldLine),e},this.restoreRange=function(e){return l(e,this.start)}}.call(o.prototype)}),ace.define("ace/edit_session/folding",["require","exports","module","ace/range","ace/edit_session/fold_line","ace/edit_session/fold","ace/token_iterator"],function(e,t,n){"use strict";function u(){this.getFoldAt=function(e,t,n){var r=this.getFoldLine(e);if(!r)return null;var i=r.folds;for(var s=0;s<i.length;s++){var o=i[s];if(o.range.contains(e,t)){if(n==1&&o.range.isEnd(e,t))continue;if(n==-1&&o.range.isStart(e,t))continue;return o}}},this.getFoldsInRange=function(e){var t=e.start,n=e.end,r=this.$foldData,i=[];t.column+=1,n.column-=1;for(var s=0;s<r.length;s++){var o=r[s].range.compareRange(e);if(o==2)continue;if(o==-2)break;var u=r[s].folds;for(var a=0;a<u.length;a++){var f=u[a];o=f.range.compareRange(e);if(o==-2)break;if(o==2)continue;if(o==42)break;i.push(f)}}return t.column-=1,n.column+=1,i},this.getFoldsInRangeList=function(e){if(Array.isArray(e)){var t=[];e.forEach(function(e){t=t.concat(this.getFoldsInRange(e))},this)}else var t=this.getFoldsInRange(e);return t},this.getAllFolds=function(){var e=[],t=this.$foldData;for(var n=0;n<t.length;n++)for(var r=0;r<t[n].folds.length;r++)e.push(t[n].folds[r]);return e},this.getFoldStringAt=function(e,t,n,r){r=r||this.getFoldLine(e);if(!r)return null;var i={end:{column:0}},s,o;for(var u=0;u<r.folds.length;u++){o=r.folds[u];var a=o.range.compareEnd(e,t);if(a==-1){s=this.getLine(o.start.row).substring(i.end.column,o.start.column);break}if(a===0)return null;i=o}return s||(s=this.getLine(o.start.row).substring(i.end.column)),n==-1?s.substring(0,t-i.end.column):n==1?s.substring(t-i.end.column):s},this.getFoldLine=function(e,t){var n=this.$foldData,r=0;t&&(r=n.indexOf(t)),r==-1&&(r=0);for(r;r<n.length;r++){var i=n[r];if(i.start.row<=e&&i.end.row>=e)return i;if(i.end.row>e)return null}return null},this.getNextFoldLine=function(e,t){var n=this.$foldData,r=0;t&&(r=n.indexOf(t)),r==-1&&(r=0);for(r;r<n.length;r++){var i=n[r];if(i.end.row>=e)return i}return null},this.getFoldedRowCount=function(e,t){var n=this.$foldData,r=t-e+1;for(var i=0;i<n.length;i++){var s=n[i],o=s.end.row,u=s.start.row;if(o>=t){u<t&&(u>=e?r-=t-u:r=0);break}o>=e&&(u>=e?r-=o-u:r-=o-e+1)}return r},this.$addFoldLine=function(e){return this.$foldData.push(e),this.$foldData.sort(function(e,t){return e.start.row-t.start.row}),e},this.addFold=function(e,t){var n=this.$foldData,r=!1,o;e instanceof s?o=e:(o=new s(t,e),o.collapseChildren=t.collapseChildren),this.$clipRangeToDocument(o.range);var u=o.start.row,a=o.start.column,f=o.end.row,l=o.end.column;if(u<f||u==f&&a<=l-2){var c=this.getFoldAt(u,a,1),h=this.getFoldAt(f,l,-1);if(c&&h==c)return c.addSubFold(o);c&&!c.range.isStart(u,a)&&this.removeFold(c),h&&!h.range.isEnd(f,l)&&this.removeFold(h);var p=this.getFoldsInRange(o.range);p.length>0&&(this.removeFolds(p),p.forEach(function(e){o.addSubFold(e)}));for(var d=0;d<n.length;d++){var v=n[d];if(f==v.start.row){v.addFold(o),r=!0;break}if(u==v.end.row){v.addFold(o),r=!0;if(!o.sameRow){var m=n[d+1];if(m&&m.start.row==f){v.merge(m);break}}break}if(f<=v.start.row)break}return r||(v=this.$addFoldLine(new i(this.$foldData,o))),this.$useWrapMode?this.$updateWrapData(v.start.row,v.start.row):this.$updateRowLengthCache(v.start.row,v.start.row),this.$modified=!0,this._signal("changeFold",{data:o,action:"add"}),o}throw new Error("The range has to be at least 2 characters width")},this.addFolds=function(e){e.forEach(function(e){this.addFold(e)},this)},this.removeFold=function(e){var t=e.foldLine,n=t.start.row,r=t.end.row,i=this.$foldData,s=t.folds;if(s.length==1)i.splice(i.indexOf(t),1);else if(t.range.isEnd(e.end.row,e.end.column))s.pop(),t.end.row=s[s.length-1].end.row,t.end.column=s[s.length-1].end.column;else if(t.range.isStart(e.start.row,e.start.column))s.shift(),t.start.row=s[0].start.row,t.start.column=s[0].start.column;else if(e.sameRow)s.splice(s.indexOf(e),1);else{var o=t.split(e.start.row,e.start.column);s=o.folds,s.shift(),o.start.row=s[0].start.row,o.start.column=s[0].start.column}this.$updating||(this.$useWrapMode?this.$updateWrapData(n,r):this.$updateRowLengthCache(n,r)),this.$modified=!0,this._signal("changeFold",{data:e,action:"remove"})},this.removeFolds=function(e){var t=[];for(var n=0;n<e.length;n++)t.push(e[n]);t.forEach(function(e){this.removeFold(e)},this),this.$modified=!0},this.expandFold=function(e){this.removeFold(e),e.subFolds.forEach(function(t){e.restoreRange(t),this.addFold(t)},this),e.collapseChildren>0&&this.foldAll(e.start.row+1,e.end.row,e.collapseChildren-1),e.subFolds=[]},this.expandFolds=function(e){e.forEach(function(e){this.expandFold(e)},this)},this.unfold=function(e,t){var n,i;e==null?(n=new r(0,0,this.getLength(),0),t=!0):typeof e=="number"?n=new r(e,0,e,this.getLine(e).length):"row"in e?n=r.fromPoints(e,e):n=e,i=this.getFoldsInRangeList(n);if(t)this.removeFolds(i);else{var s=i;while(s.length)this.expandFolds(s),s=this.getFoldsInRangeList(n)}if(i.length)return i},this.isRowFolded=function(e,t){return!!this.getFoldLine(e,t)},this.getRowFoldEnd=function(e,t){var n=this.getFoldLine(e,t);return n?n.end.row:e},this.getRowFoldStart=function(e,t){var n=this.getFoldLine(e,t);return n?n.start.row:e},this.getFoldDisplayLine=function(e,t,n,r,i){r==null&&(r=e.start.row),i==null&&(i=0),t==null&&(t=e.end.row),n==null&&(n=this.getLine(t).length);var s=this.doc,o="";return e.walk(function(e,t,n,u){if(t<r)return;if(t==r){if(n<i)return;u=Math.max(i,u)}e!=null?o+=e:o+=s.getLine(t).substring(u,n)},t,n),o},this.getDisplayLine=function(e,t,n,r){var i=this.getFoldLine(e);if(!i){var s;return s=this.doc.getLine(e),s.substring(r||0,t||s.length)}return this.getFoldDisplayLine(i,e,t,n,r)},this.$cloneFoldData=function(){var e=[];return e=this.$foldData.map(function(t){var n=t.folds.map(function(e){return e.clone()});return new i(e,n)}),e},this.toggleFold=function(e){var t=this.selection,n=t.getRange(),r,i;if(n.isEmpty()){var s=n.start;r=this.getFoldAt(s.row,s.column);if(r){this.expandFold(r);return}(i=this.findMatchingBracket(s))?n.comparePoint(i)==1?n.end=i:(n.start=i,n.start.column++,n.end.column--):(i=this.findMatchingBracket({row:s.row,column:s.column+1}))?(n.comparePoint(i)==1?n.end=i:n.start=i,n.start.column++):n=this.getCommentFoldRange(s.row,s.column)||n}else{var o=this.getFoldsInRange(n);if(e&&o.length){this.expandFolds(o);return}o.length==1&&(r=o[0])}r||(r=this.getFoldAt(n.start.row,n.start.column));if(r&&r.range.toString()==n.toString()){this.expandFold(r);return}var u="...";if(!n.isMultiLine()){u=this.getTextRange(n);if(u.length<4)return;u=u.trim().substring(0,2)+".."}this.addFold(u,n)},this.getCommentFoldRange=function(e,t,n){var i=new o(this,e,t),s=i.getCurrentToken(),u=s.type;if(s&&/^comment|string/.test(u)){u=u.match(/comment|string/)[0],u=="comment"&&(u+="|doc-start");var a=new RegExp(u),f=new r;if(n!=1){do s=i.stepBackward();while(s&&a.test(s.type));i.stepForward()}f.start.row=i.getCurrentTokenRow(),f.start.column=i.getCurrentTokenColumn()+2,i=new o(this,e,t);if(n!=-1){var l=-1;do{s=i.stepForward();if(l==-1){var c=this.getState(i.$row);a.test(c)||(l=i.$row)}else if(i.$row>l)break}while(s&&a.test(s.type));s=i.stepBackward()}else s=i.getCurrentToken();return f.end.row=i.getCurrentTokenRow(),f.end.column=i.getCurrentTokenColumn()+s.value.length-2,f}},this.foldAll=function(e,t,n){n==undefined&&(n=1e5);var r=this.foldWidgets;if(!r)return;t=t||this.getLength(),e=e||0;for(var i=e;i<t;i++){r[i]==null&&(r[i]=this.getFoldWidget(i));if(r[i]!="start")continue;var s=this.getFoldWidgetRange(i);if(s&&s.isMultiLine()&&s.end.row<=t&&s.start.row>=e){i=s.end.row;try{var o=this.addFold("...",s);o&&(o.collapseChildren=n)}catch(u){}}}},this.$foldStyles={manual:1,markbegin:1,markbeginend:1},this.$foldStyle="markbegin",this.setFoldStyle=function(e){if(!this.$foldStyles[e])throw new Error("invalid fold style: "+e+"["+Object.keys(this.$foldStyles).join(", ")+"]");if(this.$foldStyle==e)return;this.$foldStyle=e,e=="manual"&&this.unfold();var t=this.$foldMode;this.$setFolding(null),this.$setFolding(t)},this.$setFolding=function(e){if(this.$foldMode==e)return;this.$foldMode=e,this.off("change",this.$updateFoldWidgets),this.off("tokenizerUpdate",this.$tokenizerUpdateFoldWidgets),this._signal("changeAnnotation");if(!e||this.$foldStyle=="manual"){this.foldWidgets=null;return}this.foldWidgets=[],this.getFoldWidget=e.getFoldWidget.bind(e,this,this.$foldStyle),this.getFoldWidgetRange=e.getFoldWidgetRange.bind(e,this,this.$foldStyle),this.$updateFoldWidgets=this.updateFoldWidgets.bind(this),this.$tokenizerUpdateFoldWidgets=this.tokenizerUpdateFoldWidgets.bind(this),this.on("change",this.$updateFoldWidgets),this.on("tokenizerUpdate",this.$tokenizerUpdateFoldWidgets)},this.getParentFoldRangeData=function(e,t){var n=this.foldWidgets;if(!n||t&&n[e])return{};var r=e-1,i;while(r>=0){var s=n[r];s==null&&(s=n[r]=this.getFoldWidget(r));if(s=="start"){var o=this.getFoldWidgetRange(r);i||(i=o);if(o&&o.end.row>=e)break}r--}return{range:r!==-1&&o,firstRange:i}},this.onFoldWidgetClick=function(e,t){t=t.domEvent;var n={children:t.shiftKey,all:t.ctrlKey||t.metaKey,siblings:t.altKey},r=this.$toggleFoldWidget(e,n);if(!r){var i=t.target||t.srcElement;i&&/ace_fold-widget/.test(i.className)&&(i.className+=" ace_invalid")}},this.$toggleFoldWidget=function(e,t){if(!this.getFoldWidget)return;var n=this.getFoldWidget(e),r=this.getLine(e),i=n==="end"?-1:1,s=this.getFoldAt(e,i===-1?0:r.length,i);if(s)return t.children||t.all?this.removeFold(s):this.expandFold(s),s;var o=this.getFoldWidgetRange(e,!0);if(o&&!o.isMultiLine()){s=this.getFoldAt(o.start.row,o.start.column,1);if(s&&o.isEqual(s.range))return this.removeFold(s),s}if(t.siblings){var u=this.getParentFoldRangeData(e);if(u.range)var a=u.range.start.row+1,f=u.range.end.row;this.foldAll(a,f,t.all?1e4:0)}else t.children?(f=o?o.end.row:this.getLength(),this.foldAll(e+1,f,t.all?1e4:0)):o&&(t.all&&(o.collapseChildren=1e4),this.addFold("...",o));return o},this.toggleFoldWidget=function(e){var t=this.selection.getCursor().row;t=this.getRowFoldStart(t);var n=this.$toggleFoldWidget(t,{});if(n)return;var r=this.getParentFoldRangeData(t,!0);n=r.range||r.firstRange;if(n){t=n.start.row;var i=this.getFoldAt(t,this.getLine(t).length,1);i?this.removeFold(i):this.addFold("...",n)}},this.updateFoldWidgets=function(e){var t=e.start.row,n=e.end.row-t;if(n===0)this.foldWidgets[t]=null;else if(e.action=="remove")this.foldWidgets.splice(t,n+1,null);else{var r=Array(n+1);r.unshift(t,1),this.foldWidgets.splice.apply(this.foldWidgets,r)}},this.tokenizerUpdateFoldWidgets=function(e){var t=e.data;t.first!=t.last&&this.foldWidgets.length>t.first&&this.foldWidgets.splice(t.first,this.foldWidgets.length)}}var r=e("../range").Range,i=e("./fold_line").FoldLine,s=e("./fold").Fold,o=e("../token_iterator").TokenIterator;t.Folding=u}),ace.define("ace/edit_session/bracket_match",["require","exports","module","ace/token_iterator","ace/range"],function(e,t,n){"use strict";function s(){this.findMatchingBracket=function(e,t){if(e.column==0)return null;var n=t||this.getLine(e.row).charAt(e.column-1);if(n=="")return null;var r=n.match(/([\(\[\{])|([\)\]\}])/);return r?r[1]?this.$findClosingBracket(r[1],e):this.$findOpeningBracket(r[2],e):null},this.getBracketRange=function(e){var t=this.getLine(e.row),n=!0,r,s=t.charAt(e.column-1),o=s&&s.match(/([\(\[\{])|([\)\]\}])/);o||(s=t.charAt(e.column),e={row:e.row,column:e.column+1},o=s&&s.match(/([\(\[\{])|([\)\]\}])/),n=!1);if(!o)return null;if(o[1]){var u=this.$findClosingBracket(o[1],e);if(!u)return null;r=i.fromPoints(e,u),n||(r.end.column++,r.start.column--),r.cursor=r.end}else{var u=this.$findOpeningBracket(o[2],e);if(!u)return null;r=i.fromPoints(u,e),n||(r.start.column++,r.end.column--),r.cursor=r.start}return r},this.$brackets={")":"(","(":")","]":"[","[":"]","{":"}","}":"{"},this.$findOpeningBracket=function(e,t,n){var i=this.$brackets[e],s=1,o=new r(this,t.row,t.column),u=o.getCurrentToken();u||(u=o.stepForward());if(!u)return;n||(n=new RegExp("(\\.?"+u.type.replace(".","\\.").replace("rparen",".paren").replace(/\b(?:end)\b/,"(?:start|begin|end)")+")+"));var a=t.column-o.getCurrentTokenColumn()-2,f=u.value;for(;;){while(a>=0){var l=f.charAt(a);if(l==i){s-=1;if(s==0)return{row:o.getCurrentTokenRow(),column:a+o.getCurrentTokenColumn()}}else l==e&&(s+=1);a-=1}do u=o.stepBackward();while(u&&!n.test(u.type));if(u==null)break;f=u.value,a=f.length-1}return null},this.$findClosingBracket=function(e,t,n){var i=this.$brackets[e],s=1,o=new r(this,t.row,t.column),u=o.getCurrentToken();u||(u=o.stepForward());if(!u)return;n||(n=new RegExp("(\\.?"+u.type.replace(".","\\.").replace("lparen",".paren").replace(/\b(?:start|begin)\b/,"(?:start|begin|end)")+")+"));var a=t.column-o.getCurrentTokenColumn();for(;;){var f=u.value,l=f.length;while(a<l){var c=f.charAt(a);if(c==i){s-=1;if(s==0)return{row:o.getCurrentTokenRow(),column:a+o.getCurrentTokenColumn()}}else c==e&&(s+=1);a+=1}do u=o.stepForward();while(u&&!n.test(u.type));if(u==null)break;a=0}return null}}var r=e("../token_iterator").TokenIterator,i=e("../range").Range;t.BracketMatch=s}),ace.define("ace/edit_session",["require","exports","module","ace/lib/oop","ace/lib/lang","ace/bidihandler","ace/config","ace/lib/event_emitter","ace/selection","ace/mode/text","ace/range","ace/document","ace/background_tokenizer","ace/search_highlight","ace/edit_session/folding","ace/edit_session/bracket_match"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./lib/lang"),s=e("./bidihandler").BidiHandler,o=e("./config"),u=e("./lib/event_emitter").EventEmitter,a=e("./selection").Selection,f=e("./mode/text").Mode,l=e("./range").Range,c=e("./document").Document,h=e("./background_tokenizer").BackgroundTokenizer,p=e("./search_highlight").SearchHighlight,d=function(e,t){this.$breakpoints=[],this.$decorations=[],this.$frontMarkers={},this.$backMarkers={},this.$markerId=1,this.$undoSelect=!0,this.$foldData=[],this.id="session"+ ++d.$uid,this.$foldData.toString=function(){return this.join("\n")},this.on("changeFold",this.onChangeFold.bind(this)),this.$onChange=this.onChange.bind(this);if(typeof e!="object"||!e.getLine)e=new c(e);this.$bidiHandler=new s(this),this.setDocument(e),this.selection=new a(this),o.resetOptions(this),this.setMode(t),o._signal("session",this)};d.$uid=0,function(){function m(e){return e<4352?!1:e>=4352&&e<=4447||e>=4515&&e<=4519||e>=4602&&e<=4607||e>=9001&&e<=9002||e>=11904&&e<=11929||e>=11931&&e<=12019||e>=12032&&e<=12245||e>=12272&&e<=12283||e>=12288&&e<=12350||e>=12353&&e<=12438||e>=12441&&e<=12543||e>=12549&&e<=12589||e>=12593&&e<=12686||e>=12688&&e<=12730||e>=12736&&e<=12771||e>=12784&&e<=12830||e>=12832&&e<=12871||e>=12880&&e<=13054||e>=13056&&e<=19903||e>=19968&&e<=42124||e>=42128&&e<=42182||e>=43360&&e<=43388||e>=44032&&e<=55203||e>=55216&&e<=55238||e>=55243&&e<=55291||e>=63744&&e<=64255||e>=65040&&e<=65049||e>=65072&&e<=65106||e>=65108&&e<=65126||e>=65128&&e<=65131||e>=65281&&e<=65376||e>=65504&&e<=65510}r.implement(this,u),this.setDocument=function(e){this.doc&&this.doc.removeListener("change",this.$onChange),this.doc=e,e.on("change",this.$onChange),this.bgTokenizer&&this.bgTokenizer.setDocument(this.getDocument()),this.resetCaches()},this.getDocument=function(){return this.doc},this.$resetRowCache=function(e){if(!e){this.$docRowCache=[],this.$screenRowCache=[];return}var t=this.$docRowCache.length,n=this.$getRowCacheIndex(this.$docRowCache,e)+1;t>n&&(this.$docRowCache.splice(n,t),this.$screenRowCache.splice(n,t))},this.$getRowCacheIndex=function(e,t){var n=0,r=e.length-1;while(n<=r){var i=n+r>>1,s=e[i];if(t>s)n=i+1;else{if(!(t<s))return i;r=i-1}}return n-1},this.resetCaches=function(){this.$modified=!0,this.$wrapData=[],this.$rowLengthCache=[],this.$resetRowCache(0),this.bgTokenizer&&this.bgTokenizer.start(0)},this.onChangeFold=function(e){var t=e.data;this.$resetRowCache(t.start.row)},this.onChange=function(e){this.$modified=!0,this.$bidiHandler.onChange(e),this.$resetRowCache(e.start.row);var t=this.$updateInternalDataOnChange(e);!this.$fromUndo&&this.$undoManager&&(t&&t.length&&(this.$undoManager.add({action:"removeFolds",folds:t},this.mergeUndoDeltas),this.mergeUndoDeltas=!0),this.$undoManager.add(e,this.mergeUndoDeltas),this.mergeUndoDeltas=!0,this.$informUndoManager.schedule()),this.bgTokenizer&&this.bgTokenizer.$updateOnChange(e),this._signal("change",e)},this.setValue=function(e){this.doc.setValue(e),this.selection.moveTo(0,0),this.$resetRowCache(0),this.setUndoManager(this.$undoManager),this.getUndoManager().reset()},this.getValue=this.toString=function(){return this.doc.getValue()},this.getSelection=function(){return this.selection},this.getState=function(e){return this.bgTokenizer.getState(e)},this.getTokens=function(e){return this.bgTokenizer.getTokens(e)},this.getTokenAt=function(e,t){var n=this.bgTokenizer.getTokens(e),r,i=0;if(t==null){var s=n.length-1;i=this.getLine(e).length}else for(var s=0;s<n.length;s++){i+=n[s].value.length;if(i>=t)break}return r=n[s],r?(r.index=s,r.start=i-r.value.length,r):null},this.setUndoManager=function(e){this.$undoManager=e,this.$informUndoManager&&this.$informUndoManager.cancel();if(e){var t=this;e.addSession(this),this.$syncInformUndoManager=function(){t.$informUndoManager.cancel(),t.mergeUndoDeltas=!1},this.$informUndoManager=i.delayedCall(this.$syncInformUndoManager)}else this.$syncInformUndoManager=function(){}},this.markUndoGroup=function(){this.$syncInformUndoManager&&this.$syncInformUndoManager()},this.$defaultUndoManager={undo:function(){},redo:function(){},reset:function(){},add:function(){},addSelection:function(){},startNewGroup:function(){},addSession:function(){}},this.getUndoManager=function(){return this.$undoManager||this.$defaultUndoManager},this.getTabString=function(){return this.getUseSoftTabs()?i.stringRepeat(" ",this.getTabSize()):"	"},this.setUseSoftTabs=function(e){this.setOption("useSoftTabs",e)},this.getUseSoftTabs=function(){return this.$useSoftTabs&&!this.$mode.$indentWithTabs},this.setTabSize=function(e){this.setOption("tabSize",e)},this.getTabSize=function(){return this.$tabSize},this.isTabStop=function(e){return this.$useSoftTabs&&e.column%this.$tabSize===0},this.setNavigateWithinSoftTabs=function(e){this.setOption("navigateWithinSoftTabs",e)},this.getNavigateWithinSoftTabs=function(){return this.$navigateWithinSoftTabs},this.$overwrite=!1,this.setOverwrite=function(e){this.setOption("overwrite",e)},this.getOverwrite=function(){return this.$overwrite},this.toggleOverwrite=function(){this.setOverwrite(!this.$overwrite)},this.addGutterDecoration=function(e,t){this.$decorations[e]||(this.$decorations[e]=""),this.$decorations[e]+=" "+t,this._signal("changeBreakpoint",{})},this.removeGutterDecoration=function(e,t){this.$decorations[e]=(this.$decorations[e]||"").replace(" "+t,""),this._signal("changeBreakpoint",{})},this.getBreakpoints=function(){return this.$breakpoints},this.setBreakpoints=function(e){this.$breakpoints=[];for(var t=0;t<e.length;t++)this.$breakpoints[e[t]]="ace_breakpoint";this._signal("changeBreakpoint",{})},this.clearBreakpoints=function(){this.$breakpoints=[],this._signal("changeBreakpoint",{})},this.setBreakpoint=function(e,t){t===undefined&&(t="ace_breakpoint"),t?this.$breakpoints[e]=t:delete this.$breakpoints[e],this._signal("changeBreakpoint",{})},this.clearBreakpoint=function(e){delete this.$breakpoints[e],this._signal("changeBreakpoint",{})},this.addMarker=function(e,t,n,r){var i=this.$markerId++,s={range:e,type:n||"line",renderer:typeof n=="function"?n:null,clazz:t,inFront:!!r,id:i};return r?(this.$frontMarkers[i]=s,this._signal("changeFrontMarker")):(this.$backMarkers[i]=s,this._signal("changeBackMarker")),i},this.addDynamicMarker=function(e,t){if(!e.update)return;var n=this.$markerId++;return e.id=n,e.inFront=!!t,t?(this.$frontMarkers[n]=e,this._signal("changeFrontMarker")):(this.$backMarkers[n]=e,this._signal("changeBackMarker")),e},this.removeMarker=function(e){var t=this.$frontMarkers[e]||this.$backMarkers[e];if(!t)return;var n=t.inFront?this.$frontMarkers:this.$backMarkers;delete n[e],this._signal(t.inFront?"changeFrontMarker":"changeBackMarker")},this.getMarkers=function(e){return e?this.$frontMarkers:this.$backMarkers},this.highlight=function(e){if(!this.$searchHighlight){var t=new p(null,"ace_selected-word","text");this.$searchHighlight=this.addDynamicMarker(t)}this.$searchHighlight.setRegexp(e)},this.highlightLines=function(e,t,n,r){typeof t!="number"&&(n=t,t=e),n||(n="ace_step");var i=new l(e,0,t,Infinity);return i.id=this.addMarker(i,n,"fullLine",r),i},this.setAnnotations=function(e){this.$annotations=e,this._signal("changeAnnotation",{})},this.getAnnotations=function(){return this.$annotations||[]},this.clearAnnotations=function(){this.setAnnotations([])},this.$detectNewLine=function(e){var t=e.match(/^.*?(\r?\n)/m);t?this.$autoNewLine=t[1]:this.$autoNewLine="\n"},this.getWordRange=function(e,t){var n=this.getLine(e),r=!1;t>0&&(r=!!n.charAt(t-1).match(this.tokenRe)),r||(r=!!n.charAt(t).match(this.tokenRe));if(r)var i=this.tokenRe;else if(/^\s+$/.test(n.slice(t-1,t+1)))var i=/\s/;else var i=this.nonTokenRe;var s=t;if(s>0){do s--;while(s>=0&&n.charAt(s).match(i));s++}var o=t;while(o<n.length&&n.charAt(o).match(i))o++;return new l(e,s,e,o)},this.getAWordRange=function(e,t){var n=this.getWordRange(e,t),r=this.getLine(n.end.row);while(r.charAt(n.end.column).match(/[ \t]/))n.end.column+=1;return n},this.setNewLineMode=function(e){this.doc.setNewLineMode(e)},this.getNewLineMode=function(){return this.doc.getNewLineMode()},this.setUseWorker=function(e){this.setOption("useWorker",e)},this.getUseWorker=function(){return this.$useWorker},this.onReloadTokenizer=function(e){var t=e.data;this.bgTokenizer.start(t.first),this._signal("tokenizerUpdate",e)},this.$modes={},this.$mode=null,this.$modeId=null,this.setMode=function(e,t){if(e&&typeof e=="object"){if(e.getTokenizer)return this.$onChangeMode(e);var n=e,r=n.path}else r=e||"ace/mode/text";this.$modes["ace/mode/text"]||(this.$modes["ace/mode/text"]=new f);if(this.$modes[r]&&!n){this.$onChangeMode(this.$modes[r]),t&&t();return}this.$modeId=r,o.loadModule(["mode",r],function(e){if(this.$modeId!==r)return t&&t();this.$modes[r]&&!n?this.$onChangeMode(this.$modes[r]):e&&e.Mode&&(e=new e.Mode(n),n||(this.$modes[r]=e,e.$id=r),this.$onChangeMode(e)),t&&t()}.bind(this)),this.$mode||this.$onChangeMode(this.$modes["ace/mode/text"],!0)},this.$onChangeMode=function(e,t){t||(this.$modeId=e.$id);if(this.$mode===e)return;this.$mode=e,this.$stopWorker(),this.$useWorker&&this.$startWorker();var n=e.getTokenizer();if(n.addEventListener!==undefined){var r=this.onReloadTokenizer.bind(this);n.addEventListener("update",r)}if(!this.bgTokenizer){this.bgTokenizer=new h(n);var i=this;this.bgTokenizer.addEventListener("update",function(e){i._signal("tokenizerUpdate",e)})}else this.bgTokenizer.setTokenizer(n);this.bgTokenizer.setDocument(this.getDocument()),this.tokenRe=e.tokenRe,this.nonTokenRe=e.nonTokenRe,t||(e.attachToSession&&e.attachToSession(this),this.$options.wrapMethod.set.call(this,this.$wrapMethod),this.$setFolding(e.foldingRules),this.bgTokenizer.start(0),this._emit("changeMode"))},this.$stopWorker=function(){this.$worker&&(this.$worker.terminate(),this.$worker=null)},this.$startWorker=function(){try{this.$worker=this.$mode.createWorker(this)}catch(e){o.warn("Could not load worker",e),this.$worker=null}},this.getMode=function(){return this.$mode},this.$scrollTop=0,this.setScrollTop=function(e){if(this.$scrollTop===e||isNaN(e))return;this.$scrollTop=e,this._signal("changeScrollTop",e)},this.getScrollTop=function(){return this.$scrollTop},this.$scrollLeft=0,this.setScrollLeft=function(e){if(this.$scrollLeft===e||isNaN(e))return;this.$scrollLeft=e,this._signal("changeScrollLeft",e)},this.getScrollLeft=function(){return this.$scrollLeft},this.getScreenWidth=function(){return this.$computeWidth(),this.lineWidgets?Math.max(this.getLineWidgetMaxWidth(),this.screenWidth):this.screenWidth},this.getLineWidgetMaxWidth=function(){if(this.lineWidgetsWidth!=null)return this.lineWidgetsWidth;var e=0;return this.lineWidgets.forEach(function(t){t&&t.screenWidth>e&&(e=t.screenWidth)}),this.lineWidgetWidth=e},this.$computeWidth=function(e){if(this.$modified||e){this.$modified=!1;if(this.$useWrapMode)return this.screenWidth=this.$wrapLimit;var t=this.doc.getAllLines(),n=this.$rowLengthCache,r=0,i=0,s=this.$foldData[i],o=s?s.start.row:Infinity,u=t.length;for(var a=0;a<u;a++){if(a>o){a=s.end.row+1;if(a>=u)break;s=this.$foldData[i++],o=s?s.start.row:Infinity}n[a]==null&&(n[a]=this.$getStringScreenWidth(t[a])[0]),n[a]>r&&(r=n[a])}this.screenWidth=r}},this.getLine=function(e){return this.doc.getLine(e)},this.getLines=function(e,t){return this.doc.getLines(e,t)},this.getLength=function(){return this.doc.getLength()},this.getTextRange=function(e){return this.doc.getTextRange(e||this.selection.getRange())},this.insert=function(e,t){return this.doc.insert(e,t)},this.remove=function(e){return this.doc.remove(e)},this.removeFullLines=function(e,t){return this.doc.removeFullLines(e,t)},this.undoChanges=function(e,t){if(!e.length)return;this.$fromUndo=!0;for(var n=e.length-1;n!=-1;n--){var r=e[n];r.action=="insert"||r.action=="remove"?this.doc.revertDelta(r):r.folds&&this.addFolds(r.folds)}!t&&this.$undoSelect&&(e.selectionBefore?this.selection.fromJSON(e.selectionBefore):this.selection.setRange(this.$getUndoSelection(e,!0))),this.$fromUndo=!1},this.redoChanges=function(e,t){if(!e.length)return;this.$fromUndo=!0;for(var n=0;n<e.length;n++){var r=e[n];(r.action=="insert"||r.action=="remove")&&this.doc.applyDelta(r)}!t&&this.$undoSelect&&(e.selectionAfter?this.selection.fromJSON(e.selectionAfter):this.selection.setRange(this.$getUndoSelection(e,!1))),this.$fromUndo=!1},this.setUndoSelect=function(e){this.$undoSelect=e},this.$getUndoSelection=function(e,t){function n(e){return t?e.action!=="insert":e.action==="insert"}var r,i,s;for(var o=0;o<e.length;o++){var u=e[o];if(!u.start)continue;if(!r){n(u)?(r=l.fromPoints(u.start,u.end),s=!0):(r=l.fromPoints(u.start,u.start),s=!1);continue}n(u)?(i=u.start,r.compare(i.row,i.column)==-1&&r.setStart(i),i=u.end,r.compare(i.row,i.column)==1&&r.setEnd(i),s=!0):(i=u.start,r.compare(i.row,i.column)==-1&&(r=l.fromPoints(u.start,u.start)),s=!1)}return r},this.replace=function(e,t){return this.doc.replace(e,t)},this.moveText=function(e,t,n){var r=this.getTextRange(e),i=this.getFoldsInRange(e),s=l.fromPoints(t,t);if(!n){this.remove(e);var o=e.start.row-e.end.row,u=o?-e.end.column:e.start.column-e.end.column;u&&(s.start.row==e.end.row&&s.start.column>e.end.column&&(s.start.column+=u),s.end.row==e.end.row&&s.end.column>e.end.column&&(s.end.column+=u)),o&&s.start.row>=e.end.row&&(s.start.row+=o,s.end.row+=o)}s.end=this.insert(s.start,r);if(i.length){var a=e.start,f=s.start,o=f.row-a.row,u=f.column-a.column;this.addFolds(i.map(function(e){return e=e.clone(),e.start.row==a.row&&(e.start.column+=u),e.end.row==a.row&&(e.end.column+=u),e.start.row+=o,e.end.row+=o,e}))}return s},this.indentRows=function(e,t,n){n=n.replace(/\t/g,this.getTabString());for(var r=e;r<=t;r++)this.doc.insertInLine({row:r,column:0},n)},this.outdentRows=function(e){var t=e.collapseRows(),n=new l(0,0,0,0),r=this.getTabSize();for(var i=t.start.row;i<=t.end.row;++i){var s=this.getLine(i);n.start.row=i,n.end.row=i;for(var o=0;o<r;++o)if(s.charAt(o)!=" ")break;o<r&&s.charAt(o)=="	"?(n.start.column=o,n.end.column=o+1):(n.start.column=0,n.end.column=o),this.remove(n)}},this.$moveLines=function(e,t,n){e=this.getRowFoldStart(e),t=this.getRowFoldEnd(t);if(n<0){var r=this.getRowFoldStart(e+n);if(r<0)return 0;var i=r-e}else if(n>0){var r=this.getRowFoldEnd(t+n);if(r>this.doc.getLength()-1)return 0;var i=r-t}else{e=this.$clipRowToDocument(e),t=this.$clipRowToDocument(t);var i=t-e+1}var s=new l(e,0,t,Number.MAX_VALUE),o=this.getFoldsInRange(s).map(function(e){return e=e.clone(),e.start.row+=i,e.end.row+=i,e}),u=n==0?this.doc.getLines(e,t):this.doc.removeFullLines(e,t);return this.doc.insertFullLines(e+i,u),o.length&&this.addFolds(o),i},this.moveLinesUp=function(e,t){return this.$moveLines(e,t,-1)},this.moveLinesDown=function(e,t){return this.$moveLines(e,t,1)},this.duplicateLines=function(e,t){return this.$moveLines(e,t,0)},this.$clipRowToDocument=function(e){return Math.max(0,Math.min(e,this.doc.getLength()-1))},this.$clipColumnToRow=function(e,t){return t<0?0:Math.min(this.doc.getLine(e).length,t)},this.$clipPositionToDocument=function(e,t){t=Math.max(0,t);if(e<0)e=0,t=0;else{var n=this.doc.getLength();e>=n?(e=n-1,t=this.doc.getLine(n-1).length):t=Math.min(this.doc.getLine(e).length,t)}return{row:e,column:t}},this.$clipRangeToDocument=function(e){e.start.row<0?(e.start.row=0,e.start.column=0):e.start.column=this.$clipColumnToRow(e.start.row,e.start.column);var t=this.doc.getLength()-1;return e.end.row>t?(e.end.row=t,e.end.column=this.doc.getLine(t).length):e.end.column=this.$clipColumnToRow(e.end.row,e.end.column),e},this.$wrapLimit=80,this.$useWrapMode=!1,this.$wrapLimitRange={min:null,max:null},this.setUseWrapMode=function(e){if(e!=this.$useWrapMode){this.$useWrapMode=e,this.$modified=!0,this.$resetRowCache(0);if(e){var t=this.getLength();this.$wrapData=Array(t),this.$updateWrapData(0,t-1)}this._signal("changeWrapMode")}},this.getUseWrapMode=function(){return this.$useWrapMode},this.setWrapLimitRange=function(e,t){if(this.$wrapLimitRange.min!==e||this.$wrapLimitRange.max!==t)this.$wrapLimitRange={min:e,max:t},this.$modified=!0,this.$bidiHandler.markAsDirty(),this.$useWrapMode&&this._signal("changeWrapMode")},this.adjustWrapLimit=function(e,t){var n=this.$wrapLimitRange;n.max<0&&(n={min:t,max:t});var r=this.$constrainWrapLimit(e,n.min,n.max);return r!=this.$wrapLimit&&r>1?(this.$wrapLimit=r,this.$modified=!0,this.$useWrapMode&&(this.$updateWrapData(0,this.getLength()-1),this.$resetRowCache(0),this._signal("changeWrapLimit")),!0):!1},this.$constrainWrapLimit=function(e,t,n){return t&&(e=Math.max(t,e)),n&&(e=Math.min(n,e)),e},this.getWrapLimit=function(){return this.$wrapLimit},this.setWrapLimit=function(e){this.setWrapLimitRange(e,e)},this.getWrapLimitRange=function(){return{min:this.$wrapLimitRange.min,max:this.$wrapLimitRange.max}},this.$updateInternalDataOnChange=function(e){var t=this.$useWrapMode,n=e.action,r=e.start,i=e.end,s=r.row,o=i.row,u=o-s,a=null;this.$updating=!0;if(u!=0)if(n==="remove"){this[t?"$wrapData":"$rowLengthCache"].splice(s,u);var f=this.$foldData;a=this.getFoldsInRange(e),this.removeFolds(a);var l=this.getFoldLine(i.row),c=0;if(l){l.addRemoveChars(i.row,i.column,r.column-i.column),l.shiftRow(-u);var h=this.getFoldLine(s);h&&h!==l&&(h.merge(l),l=h),c=f.indexOf(l)+1}for(c;c<f.length;c++){var l=f[c];l.start.row>=i.row&&l.shiftRow(-u)}o=s}else{var p=Array(u);p.unshift(s,0);var d=t?this.$wrapData:this.$rowLengthCache;d.splice.apply(d,p);var f=this.$foldData,l=this.getFoldLine(s),c=0;if(l){var v=l.range.compareInside(r.row,r.column);v==0?(l=l.split(r.row,r.column),l&&(l.shiftRow(u),l.addRemoveChars(o,0,i.column-r.column))):v==-1&&(l.addRemoveChars(s,0,i.column-r.column),l.shiftRow(u)),c=f.indexOf(l)+1}for(c;c<f.length;c++){var l=f[c];l.start.row>=s&&l.shiftRow(u)}}else{u=Math.abs(e.start.column-e.end.column),n==="remove"&&(a=this.getFoldsInRange(e),this.removeFolds(a),u=-u);var l=this.getFoldLine(s);l&&l.addRemoveChars(s,r.column,u)}return t&&this.$wrapData.length!=this.doc.getLength()&&console.error("doc.getLength() and $wrapData.length have to be the same!"),this.$updating=!1,t?this.$updateWrapData(s,o):this.$updateRowLengthCache(s,o),a},this.$updateRowLengthCache=function(e,t,n){this.$rowLengthCache[e]=null,this.$rowLengthCache[t]=null},this.$updateWrapData=function(e,t){var r=this.doc.getAllLines(),i=this.getTabSize(),o=this.$wrapData,u=this.$wrapLimit,a,f,l=e;t=Math.min(t,r.length-1);while(l<=t)f=this.getFoldLine(l,f),f?(a=[],f.walk(function(e,t,i,o){var u;if(e!=null){u=this.$getDisplayTokens(e,a.length),u[0]=n;for(var f=1;f<u.length;f++)u[f]=s}else u=this.$getDisplayTokens(r[t].substring(o,i),a.length);a=a.concat(u)}.bind(this),f.end.row,r[f.end.row].length+1),o[f.start.row]=this.$computeWrapSplits(a,u,i),l=f.end.row+1):(a=this.$getDisplayTokens(r[l]),o[l]=this.$computeWrapSplits(a,u,i),l++)};var e=1,t=2,n=3,s=4,a=9,c=10,d=11,v=12;this.$computeWrapSplits=function(e,r,i){function g(){var t=0;if(m===0)return t;if(p)for(var n=0;n<e.length;n++){var r=e[n];if(r==c)t+=1;else{if(r!=d){if(r==v)continue;break}t+=i}}return h&&p!==!1&&(t+=i),Math.min(t,m)}function y(t){var n=e.slice(f,t),r=n.length;n.join("").replace(/12/g,function(){r-=1}).replace(/2/g,function(){r-=1}),o.length||(b=g(),o.indent=b),l+=r,o.push(l),f=t}if(e.length==0)return[];var o=[],u=e.length,f=0,l=0,h=this.$wrapAsCode,p=this.$indentedSoftWrap,m=r<=Math.max(2*i,8)||p===!1?0:Math.floor(r/2),b=0;while(u-f>r-b){var w=f+r-b;if(e[w-1]>=c&&e[w]>=c){y(w);continue}if(e[w]==n||e[w]==s){for(w;w!=f-1;w--)if(e[w]==n)break;if(w>f){y(w);continue}w=f+r;for(w;w<e.length;w++)if(e[w]!=s)break;if(w==e.length)break;y(w);continue}var E=Math.max(w-(r-(r>>2)),f-1);while(w>E&&e[w]<n)w--;if(h){while(w>E&&e[w]<n)w--;while(w>E&&e[w]==a)w--}else while(w>E&&e[w]<c)w--;if(w>E){y(++w);continue}w=f+r,e[w]==t&&w--,y(w-b)}return o},this.$getDisplayTokens=function(n,r){var i=[],s;r=r||0;for(var o=0;o<n.length;o++){var u=n.charCodeAt(o);if(u==9){s=this.getScreenTabSize(i.length+r),i.push(d);for(var f=1;f<s;f++)i.push(v)}else u==32?i.push(c):u>39&&u<48||u>57&&u<64?i.push(a):u>=4352&&m(u)?i.push(e,t):i.push(e)}return i},this.$getStringScreenWidth=function(e,t,n){if(t==0)return[0,0];t==null&&(t=Infinity),n=n||0;var r,i;for(i=0;i<e.length;i++){r=e.charCodeAt(i),r==9?n+=this.getScreenTabSize(n):r>=4352&&m(r)?n+=2:n+=1;if(n>t)break}return[n,i]},this.lineWidgets=null,this.getRowLength=function(e){if(this.lineWidgets)var t=this.lineWidgets[e]&&this.lineWidgets[e].rowCount||0;else t=0;return!this.$useWrapMode||!this.$wrapData[e]?1+t:this.$wrapData[e].length+1+t},this.getRowLineCount=function(e){return!this.$useWrapMode||!this.$wrapData[e]?1:this.$wrapData[e].length+1},this.getRowWrapIndent=function(e){if(this.$useWrapMode){var t=this.screenToDocumentPosition(e,Number.MAX_VALUE),n=this.$wrapData[t.row];return n.length&&n[0]<t.column?n.indent:0}return 0},this.getScreenLastRowColumn=function(e){var t=this.screenToDocumentPosition(e,Number.MAX_VALUE);return this.documentToScreenColumn(t.row,t.column)},this.getDocumentLastRowColumn=function(e,t){var n=this.documentToScreenRow(e,t);return this.getScreenLastRowColumn(n)},this.getDocumentLastRowColumnPosition=function(e,t){var n=this.documentToScreenRow(e,t);return this.screenToDocumentPosition(n,Number.MAX_VALUE/10)},this.getRowSplitData=function(e){return this.$useWrapMode?this.$wrapData[e]:undefined},this.getScreenTabSize=function(e){return this.$tabSize-e%this.$tabSize},this.screenToDocumentRow=function(e,t){return this.screenToDocumentPosition(e,t).row},this.screenToDocumentColumn=function(e,t){return this.screenToDocumentPosition(e,t).column},this.screenToDocumentPosition=function(e,t,n){if(e<0)return{row:0,column:0};var r,i=0,s=0,o,u=0,a=0,f=this.$screenRowCache,l=this.$getRowCacheIndex(f,e),c=f.length;if(c&&l>=0)var u=f[l],i=this.$docRowCache[l],h=e>f[c-1];else var h=!c;var p=this.getLength()-1,d=this.getNextFoldLine(i),v=d?d.start.row:Infinity;while(u<=e){a=this.getRowLength(i);if(u+a>e||i>=p)break;u+=a,i++,i>v&&(i=d.end.row+1,d=this.getNextFoldLine(i,d),v=d?d.start.row:Infinity),h&&(this.$docRowCache.push(i),this.$screenRowCache.push(u))}if(d&&d.start.row<=i)r=this.getFoldDisplayLine(d),i=d.start.row;else{if(u+a<=e||i>p)return{row:p,column:this.getLine(p).length};r=this.getLine(i),d=null}var m=0,g=Math.floor(e-u);if(this.$useWrapMode){var y=this.$wrapData[i];y&&(o=y[g],g>0&&y.length&&(m=y.indent,s=y[g-1]||y[y.length-1],r=r.substring(s)))}return n!==undefined&&this.$bidiHandler.isBidiRow(u+g,i,g)&&(t=this.$bidiHandler.offsetToCol(n)),s+=this.$getStringScreenWidth(r,t-m)[1],this.$useWrapMode&&s>=o&&(s=o-1),d?d.idxToPosition(s):{row:i,column:s}},this.documentToScreenPosition=function(e,t){if(typeof t=="undefined")var n=this.$clipPositionToDocument(e.row,e.column);else n=this.$clipPositionToDocument(e,t);e=n.row,t=n.column;var r=0,i=null,s=null;s=this.getFoldAt(e,t,1),s&&(e=s.start.row,t=s.start.column);var o,u=0,a=this.$docRowCache,f=this.$getRowCacheIndex(a,e),l=a.length;if(l&&f>=0)var u=a[f],r=this.$screenRowCache[f],c=e>a[l-1];else var c=!l;var h=this.getNextFoldLine(u),p=h?h.start.row:Infinity;while(u<e){if(u>=p){o=h.end.row+1;if(o>e)break;h=this.getNextFoldLine(o,h),p=h?h.start.row:Infinity}else o=u+1;r+=this.getRowLength(u),u=o,c&&(this.$docRowCache.push(u),this.$screenRowCache.push(r))}var d="";h&&u>=p?(d=this.getFoldDisplayLine(h,e,t),i=h.start.row):(d=this.getLine(e).substring(0,t),i=e);var v=0;if(this.$useWrapMode){var m=this.$wrapData[i];if(m){var g=0;while(d.length>=m[g])r++,g++;d=d.substring(m[g-1]||0,d.length),v=g>0?m.indent:0}}return{row:r,column:v+this.$getStringScreenWidth(d)[0]}},this.documentToScreenColumn=function(e,t){return this.documentToScreenPosition(e,t).column},this.documentToScreenRow=function(e,t){return this.documentToScreenPosition(e,t).row},this.getScreenLength=function(){var e=0,t=null;if(!this.$useWrapMode){e=this.getLength();var n=this.$foldData;for(var r=0;r<n.length;r++)t=n[r],e-=t.end.row-t.start.row}else{var i=this.$wrapData.length,s=0,r=0,t=this.$foldData[r++],o=t?t.start.row:Infinity;while(s<i){var u=this.$wrapData[s];e+=u?u.length+1:1,s++,s>o&&(s=t.end.row+1,t=this.$foldData[r++],o=t?t.start.row:Infinity)}}return this.lineWidgets&&(e+=this.$getWidgetScreenLength()),e},this.$setFontMetrics=function(e){if(!this.$enableVarChar)return;this.$getStringScreenWidth=function(t,n,r){if(n===0)return[0,0];n||(n=Infinity),r=r||0;var i,s;for(s=0;s<t.length;s++){i=t.charAt(s),i==="	"?r+=this.getScreenTabSize(r):r+=e.getCharacterWidth(i);if(r>n)break}return[r,s]}},this.destroy=function(){this.bgTokenizer&&(this.bgTokenizer.setDocument(null),this.bgTokenizer=null),this.$stopWorker()},this.isFullWidth=m}.call(d.prototype),e("./edit_session/folding").Folding.call(d.prototype),e("./edit_session/bracket_match").BracketMatch.call(d.prototype),o.defineOptions(d.prototype,"session",{wrap:{set:function(e){!e||e=="off"?e=!1:e=="free"?e=!0:e=="printMargin"?e=-1:typeof e=="string"&&(e=parseInt(e,10)||!1);if(this.$wrap==e)return;this.$wrap=e;if(!e)this.setUseWrapMode(!1);else{var t=typeof e=="number"?e:null;this.setWrapLimitRange(t,t),this.setUseWrapMode(!0)}},get:function(){return this.getUseWrapMode()?this.$wrap==-1?"printMargin":this.getWrapLimitRange().min?this.$wrap:"free":"off"},handlesSet:!0},wrapMethod:{set:function(e){e=e=="auto"?this.$mode.type!="text":e!="text",e!=this.$wrapAsCode&&(this.$wrapAsCode=e,this.$useWrapMode&&(this.$modified=!0,this.$resetRowCache(0),this.$updateWrapData(0,this.getLength()-1)))},initialValue:"auto"},indentedSoftWrap:{initialValue:!0},firstLineNumber:{set:function(){this._signal("changeBreakpoint")},initialValue:1},useWorker:{set:function(e){this.$useWorker=e,this.$stopWorker(),e&&this.$startWorker()},initialValue:!0},useSoftTabs:{initialValue:!0},tabSize:{set:function(e){if(isNaN(e)||this.$tabSize===e)return;this.$modified=!0,this.$rowLengthCache=[],this.$tabSize=e,this._signal("changeTabSize")},initialValue:4,handlesSet:!0},navigateWithinSoftTabs:{initialValue:!1},foldStyle:{set:function(e){this.setFoldStyle(e)},handlesSet:!0},overwrite:{set:function(e){this._signal("changeOverwrite")},initialValue:!1},newLineMode:{set:function(e){this.doc.setNewLineMode(e)},get:function(){return this.doc.getNewLineMode()},handlesSet:!0},mode:{set:function(e){this.setMode(e)},get:function(){return this.$modeId}}}),t.EditSession=d}),ace.define("ace/search",["require","exports","module","ace/lib/lang","ace/lib/oop","ace/range"],function(e,t,n){"use strict";function u(e,t){function n(e){return/\w/.test(e)||t.regExp?"\\b":""}return n(e[0])+e+n(e[e.length-1])}var r=e("./lib/lang"),i=e("./lib/oop"),s=e("./range").Range,o=function(){this.$options={}};(function(){this.set=function(e){return i.mixin(this.$options,e),this},this.getOptions=function(){return r.copyObject(this.$options)},this.setOptions=function(e){this.$options=e},this.find=function(e){var t=this.$options,n=this.$matchIterator(e,t);if(!n)return!1;var r=null;return n.forEach(function(e,n,i,o){return r=new s(e,n,i,o),n==o&&t.start&&t.start.start&&t.skipCurrent!=0&&r.isEqual(t.start)?(r=null,!1):!0}),r},this.findAll=function(e){var t=this.$options;if(!t.needle)return[];this.$assembleRegExp(t);var n=t.range,i=n?e.getLines(n.start.row,n.end.row):e.doc.getAllLines(),o=[],u=t.re;if(t.$isMultiLine){var a=u.length,f=i.length-a,l;e:for(var c=u.offset||0;c<=f;c++){for(var h=0;h<a;h++)if(i[c+h].search(u[h])==-1)continue e;var p=i[c],d=i[c+a-1],v=p.length-p.match(u[0])[0].length,m=d.match(u[a-1])[0].length;if(l&&l.end.row===c&&l.end.column>v)continue;o.push(l=new s(c,v,c+a-1,m)),a>2&&(c=c+a-2)}}else for(var g=0;g<i.length;g++){var y=r.getMatchOffsets(i[g],u);for(var h=0;h<y.length;h++){var b=y[h];o.push(new s(g,b.offset,g,b.offset+b.length))}}if(n){var w=n.start.column,E=n.start.column,g=0,h=o.length-1;while(g<h&&o[g].start.column<w&&o[g].start.row==n.start.row)g++;while(g<h&&o[h].end.column>E&&o[h].end.row==n.end.row)h--;o=o.slice(g,h+1);for(g=0,h=o.length;g<h;g++)o[g].start.row+=n.start.row,o[g].end.row+=n.start.row}return o},this.replace=function(e,t){var n=this.$options,r=this.$assembleRegExp(n);if(n.$isMultiLine)return t;if(!r)return;var i=r.exec(e);if(!i||i[0].length!=e.length)return null;t=e.replace(r,t);if(n.preserveCase){t=t.split("");for(var s=Math.min(e.length,e.length);s--;){var o=e[s];o&&o.toLowerCase()!=o?t[s]=t[s].toUpperCase():t[s]=t[s].toLowerCase()}t=t.join("")}return t},this.$assembleRegExp=function(e,t){if(e.needle instanceof RegExp)return e.re=e.needle;var n=e.needle;if(!e.needle)return e.re=!1;e.regExp||(n=r.escapeRegExp(n)),e.wholeWord&&(n=u(n,e));var i=e.caseSensitive?"gm":"gmi";e.$isMultiLine=!t&&/[\n\r]/.test(n);if(e.$isMultiLine)return e.re=this.$assembleMultilineRegExp(n,i);try{var s=new RegExp(n,i)}catch(o){s=!1}return e.re=s},this.$assembleMultilineRegExp=function(e,t){var n=e.replace(/\r\n|\r|\n/g,"$\n^").split("\n"),r=[];for(var i=0;i<n.length;i++)try{r.push(new RegExp(n[i],t))}catch(s){return!1}return r},this.$matchIterator=function(e,t){var n=this.$assembleRegExp(t);if(!n)return!1;var r=t.backwards==1,i=t.skipCurrent!=0,s=t.range,o=t.start;o||(o=s?s[r?"end":"start"]:e.selection.getRange()),o.start&&(o=o[i!=r?"end":"start"]);var u=s?s.start.row:0,a=s?s.end.row:e.getLength()-1;if(r)var f=function(e){var n=o.row;if(c(n,o.column,e))return;for(n--;n>=u;n--)if(c(n,Number.MAX_VALUE,e))return;if(t.wrap==0)return;for(n=a,u=o.row;n>=u;n--)if(c(n,Number.MAX_VALUE,e))return};else var f=function(e){var n=o.row;if(c(n,o.column,e))return;for(n+=1;n<=a;n++)if(c(n,0,e))return;if(t.wrap==0)return;for(n=u,a=o.row;n<=a;n++)if(c(n,0,e))return};if(t.$isMultiLine)var l=n.length,c=function(t,i,s){var o=r?t-l+1:t;if(o<0)return;var u=e.getLine(o),a=u.search(n[0]);if(!r&&a<i||a===-1)return;for(var f=1;f<l;f++){u=e.getLine(o+f);if(u.search(n[f])==-1)return}var c=u.match(n[l-1])[0].length;if(r&&c>i)return;if(s(o,a,o+l-1,c))return!0};else if(r)var c=function(t,r,i){var s=e.getLine(t),o=[],u,a=0;n.lastIndex=0;while(u=n.exec(s)){var f=u[0].length;a=u.index;if(!f){if(a>=s.length)break;n.lastIndex=a+=1}if(u.index+f>r)break;o.push(u.index,f)}for(var l=o.length-1;l>=0;l-=2){var c=o[l-1],f=o[l];if(i(t,c,t,c+f))return!0}};else var c=function(t,r,i){var s=e.getLine(t),o,u;n.lastIndex=r;while(u=n.exec(s)){var a=u[0].length;o=u.index;if(i(t,o,t,o+a))return!0;if(!a){n.lastIndex=o+=1;if(o>=s.length)return!1}}};return{forEach:f}}}).call(o.prototype),t.Search=o}),ace.define("ace/keyboard/hash_handler",["require","exports","module","ace/lib/keys","ace/lib/useragent"],function(e,t,n){"use strict";function o(e,t){this.platform=t||(i.isMac?"mac":"win"),this.commands={},this.commandKeyBinding={},this.addCommands(e),this.$singleCommand=!0}function u(e,t){o.call(this,e,t),this.$singleCommand=!1}var r=e("../lib/keys"),i=e("../lib/useragent"),s=r.KEY_MODS;u.prototype=o.prototype,function(){function e(e){return typeof e=="object"&&e.bindKey&&e.bindKey.position||(e.isDefault?-100:0)}this.addCommand=function(e){this.commands[e.name]&&this.removeCommand(e),this.commands[e.name]=e,e.bindKey&&this._buildKeyHash(e)},this.removeCommand=function(e,t){var n=e&&(typeof e=="string"?e:e.name);e=this.commands[n],t||delete this.commands[n];var r=this.commandKeyBinding;for(var i in r){var s=r[i];if(s==e)delete r[i];else if(Array.isArray(s)){var o=s.indexOf(e);o!=-1&&(s.splice(o,1),s.length==1&&(r[i]=s[0]))}}},this.bindKey=function(e,t,n){typeof e=="object"&&e&&(n==undefined&&(n=e.position),e=e[this.platform]);if(!e)return;if(typeof t=="function")return this.addCommand({exec:t,bindKey:e,name:t.name||e});e.split("|").forEach(function(e){var r="";if(e.indexOf(" ")!=-1){var i=e.split(/\s+/);e=i.pop(),i.forEach(function(e){var t=this.parseKeys(e),n=s[t.hashId]+t.key;r+=(r?" ":"")+n,this._addCommandToBinding(r,"chainKeys")},this),r+=" "}var o=this.parseKeys(e),u=s[o.hashId]+o.key;this._addCommandToBinding(r+u,t,n)},this)},this._addCommandToBinding=function(t,n,r){var i=this.commandKeyBinding,s;if(!n)delete i[t];else if(!i[t]||this.$singleCommand)i[t]=n;else{Array.isArray(i[t])?(s=i[t].indexOf(n))!=-1&&i[t].splice(s,1):i[t]=[i[t]],typeof r!="number"&&(r=e(n));var o=i[t];for(s=0;s<o.length;s++){var u=o[s],a=e(u);if(a>r)break}o.splice(s,0,n)}},this.addCommands=function(e){e&&Object.keys(e).forEach(function(t){var n=e[t];if(!n)return;if(typeof n=="string")return this.bindKey(n,t);typeof n=="function"&&(n={exec:n});if(typeof n!="object")return;n.name||(n.name=t),this.addCommand(n)},this)},this.removeCommands=function(e){Object.keys(e).forEach(function(t){this.removeCommand(e[t])},this)},this.bindKeys=function(e){Object.keys(e).forEach(function(t){this.bindKey(t,e[t])},this)},this._buildKeyHash=function(e){this.bindKey(e.bindKey,e)},this.parseKeys=function(e){var t=e.toLowerCase().split(/[\-\+]([\-\+])?/).filter(function(e){return e}),n=t.pop(),i=r[n];if(r.FUNCTION_KEYS[i])n=r.FUNCTION_KEYS[i].toLowerCase();else{if(!t.length)return{key:n,hashId:-1};if(t.length==1&&t[0]=="shift")return{key:n.toUpperCase(),hashId:-1}}var s=0;for(var o=t.length;o--;){var u=r.KEY_MODS[t[o]];if(u==null)return typeof console!="undefined"&&console.error("invalid modifier "+t[o]+" in "+e),!1;s|=u}return{key:n,hashId:s}},this.findKeyCommand=function(t,n){var r=s[t]+n;return this.commandKeyBinding[r]},this.handleKeyboard=function(e,t,n,r){if(r<0)return;var i=s[t]+n,o=this.commandKeyBinding[i];e.$keyChain&&(e.$keyChain+=" "+i,o=this.commandKeyBinding[e.$keyChain]||o);if(o)if(o=="chainKeys"||o[o.length-1]=="chainKeys")return e.$keyChain=e.$keyChain||i,{command:"null"};if(e.$keyChain)if(!!t&&t!=4||n.length!=1){if(t==-1||r>0)e.$keyChain=""}else e.$keyChain=e.$keyChain.slice(0,-i.length-1);return{command:o}},this.getStatusText=function(e,t){return t.$keyChain||""}}.call(o.prototype),t.HashHandler=o,t.MultiHashHandler=u}),ace.define("ace/commands/command_manager",["require","exports","module","ace/lib/oop","ace/keyboard/hash_handler","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("../lib/oop"),i=e("../keyboard/hash_handler").MultiHashHandler,s=e("../lib/event_emitter").EventEmitter,o=function(e,t){i.call(this,t,e),this.byName=this.commands,this.setDefaultHandler("exec",function(e){return e.command.exec(e.editor,e.args||{})})};r.inherits(o,i),function(){r.implement(this,s),this.exec=function(e,t,n){if(Array.isArray(e)){for(var r=e.length;r--;)if(this.exec(e[r],t,n))return!0;return!1}typeof e=="string"&&(e=this.commands[e]);if(!e)return!1;if(t&&t.$readOnly&&!e.readOnly)return!1;if(e.isAvailable&&!e.isAvailable(t))return!1;var i={editor:t,command:e,args:n};return i.returnValue=this._emit("exec",i),this._signal("afterExec",i),i.returnValue===!1?!1:!0},this.toggleRecording=function(e){if(this.$inReplay)return;return e&&e._emit("changeStatus"),this.recording?(this.macro.pop(),this.removeEventListener("exec",this.$addCommandToMacro),this.macro.length||(this.macro=this.oldMacro),this.recording=!1):(this.$addCommandToMacro||(this.$addCommandToMacro=function(e){this.macro.push([e.command,e.args])}.bind(this)),this.oldMacro=this.macro,this.macro=[],this.on("exec",this.$addCommandToMacro),this.recording=!0)},this.replay=function(e){if(this.$inReplay||!this.macro)return;if(this.recording)return this.toggleRecording(e);try{this.$inReplay=!0,this.macro.forEach(function(t){typeof t=="string"?this.exec(t,e):this.exec(t[0],e,t[1])},this)}finally{this.$inReplay=!1}},this.trimMacro=function(e){return e.map(function(e){return typeof e[0]!="string"&&(e[0]=e[0].name),e[1]||(e=e[0]),e})}}.call(o.prototype),t.CommandManager=o}),ace.define("ace/commands/default_commands",["require","exports","module","ace/lib/lang","ace/config","ace/range"],function(e,t,n){"use strict";function o(e,t){return{win:e,mac:t}}var r=e("../lib/lang"),i=e("../config"),s=e("../range").Range;t.commands=[{name:"showSettingsMenu",bindKey:o("Ctrl-,","Command-,"),exec:function(e){i.loadModule("ace/ext/settings_menu",function(t){t.init(e),e.showSettingsMenu()})},readOnly:!0},{name:"goToNextError",bindKey:o("Alt-E","F4"),exec:function(e){i.loadModule("./ext/error_marker",function(t){t.showErrorMarker(e,1)})},scrollIntoView:"animate",readOnly:!0},{name:"goToPreviousError",bindKey:o("Alt-Shift-E","Shift-F4"),exec:function(e){i.loadModule("./ext/error_marker",function(t){t.showErrorMarker(e,-1)})},scrollIntoView:"animate",readOnly:!0},{name:"selectall",bindKey:o("Ctrl-A","Command-A"),exec:function(e){e.selectAll()},readOnly:!0},{name:"centerselection",bindKey:o(null,"Ctrl-L"),exec:function(e){e.centerSelection()},readOnly:!0},{name:"gotoline",bindKey:o("Ctrl-L","Command-L"),exec:function(e,t){typeof t!="number"&&(t=parseInt(prompt("Enter line number:"),10)),isNaN(t)||e.gotoLine(t)},readOnly:!0},{name:"fold",bindKey:o("Alt-L|Ctrl-F1","Command-Alt-L|Command-F1"),exec:function(e){e.session.toggleFold(!1)},multiSelectAction:"forEach",scrollIntoView:"center",readOnly:!0},{name:"unfold",bindKey:o("Alt-Shift-L|Ctrl-Shift-F1","Command-Alt-Shift-L|Command-Shift-F1"),exec:function(e){e.session.toggleFold(!0)},multiSelectAction:"forEach",scrollIntoView:"center",readOnly:!0},{name:"toggleFoldWidget",bindKey:o("F2","F2"),exec:function(e){e.session.toggleFoldWidget()},multiSelectAction:"forEach",scrollIntoView:"center",readOnly:!0},{name:"toggleParentFoldWidget",bindKey:o("Alt-F2","Alt-F2"),exec:function(e){e.session.toggleFoldWidget(!0)},multiSelectAction:"forEach",scrollIntoView:"center",readOnly:!0},{name:"foldall",bindKey:o(null,"Ctrl-Command-Option-0"),exec:function(e){e.session.foldAll()},scrollIntoView:"center",readOnly:!0},{name:"foldOther",bindKey:o("Alt-0","Command-Option-0"),exec:function(e){e.session.foldAll(),e.session.unfold(e.selection.getAllRanges())},scrollIntoView:"center",readOnly:!0},{name:"unfoldall",bindKey:o("Alt-Shift-0","Command-Option-Shift-0"),exec:function(e){e.session.unfold()},scrollIntoView:"center",readOnly:!0},{name:"findnext",bindKey:o("Ctrl-K","Command-G"),exec:function(e){e.findNext()},multiSelectAction:"forEach",scrollIntoView:"center",readOnly:!0},{name:"findprevious",bindKey:o("Ctrl-Shift-K","Command-Shift-G"),exec:function(e){e.findPrevious()},multiSelectAction:"forEach",scrollIntoView:"center",readOnly:!0},{name:"selectOrFindNext",bindKey:o("Alt-K","Ctrl-G"),exec:function(e){e.selection.isEmpty()?e.selection.selectWord():e.findNext()},readOnly:!0},{name:"selectOrFindPrevious",bindKey:o("Alt-Shift-K","Ctrl-Shift-G"),exec:function(e){e.selection.isEmpty()?e.selection.selectWord():e.findPrevious()},readOnly:!0},{name:"find",bindKey:o("Ctrl-F","Command-F"),exec:function(e){i.loadModule("ace/ext/searchbox",function(t){t.Search(e)})},readOnly:!0},{name:"overwrite",bindKey:"Insert",exec:function(e){e.toggleOverwrite()},readOnly:!0},{name:"selecttostart",bindKey:o("Ctrl-Shift-Home","Command-Shift-Home|Command-Shift-Up"),exec:function(e){e.getSelection().selectFileStart()},multiSelectAction:"forEach",readOnly:!0,scrollIntoView:"animate",aceCommandGroup:"fileJump"},{name:"gotostart",bindKey:o("Ctrl-Home","Command-Home|Command-Up"),exec:function(e){e.navigateFileStart()},multiSelectAction:"forEach",readOnly:!0,scrollIntoView:"animate",aceCommandGroup:"fileJump"},{name:"selectup",bindKey:o("Shift-Up","Shift-Up|Ctrl-Shift-P"),exec:function(e){e.getSelection().selectUp()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"golineup",bindKey:o("Up","Up|Ctrl-P"),exec:function(e,t){e.navigateUp(t.times)},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selecttoend",bindKey:o("Ctrl-Shift-End","Command-Shift-End|Command-Shift-Down"),exec:function(e){e.getSelection().selectFileEnd()},multiSelectAction:"forEach",readOnly:!0,scrollIntoView:"animate",aceCommandGroup:"fileJump"},{name:"gotoend",bindKey:o("Ctrl-End","Command-End|Command-Down"),exec:function(e){e.navigateFileEnd()},multiSelectAction:"forEach",readOnly:!0,scrollIntoView:"animate",aceCommandGroup:"fileJump"},{name:"selectdown",bindKey:o("Shift-Down","Shift-Down|Ctrl-Shift-N"),exec:function(e){e.getSelection().selectDown()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"golinedown",bindKey:o("Down","Down|Ctrl-N"),exec:function(e,t){e.navigateDown(t.times)},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selectwordleft",bindKey:o("Ctrl-Shift-Left","Option-Shift-Left"),exec:function(e){e.getSelection().selectWordLeft()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"gotowordleft",bindKey:o("Ctrl-Left","Option-Left"),exec:function(e){e.navigateWordLeft()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selecttolinestart",bindKey:o("Alt-Shift-Left","Command-Shift-Left|Ctrl-Shift-A"),exec:function(e){e.getSelection().selectLineStart()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"gotolinestart",bindKey:o("Alt-Left|Home","Command-Left|Home|Ctrl-A"),exec:function(e){e.navigateLineStart()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selectleft",bindKey:o("Shift-Left","Shift-Left|Ctrl-Shift-B"),exec:function(e){e.getSelection().selectLeft()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"gotoleft",bindKey:o("Left","Left|Ctrl-B"),exec:function(e,t){e.navigateLeft(t.times)},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selectwordright",bindKey:o("Ctrl-Shift-Right","Option-Shift-Right"),exec:function(e){e.getSelection().selectWordRight()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"gotowordright",bindKey:o("Ctrl-Right","Option-Right"),exec:function(e){e.navigateWordRight()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selecttolineend",bindKey:o("Alt-Shift-Right","Command-Shift-Right|Shift-End|Ctrl-Shift-E"),exec:function(e){e.getSelection().selectLineEnd()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"gotolineend",bindKey:o("Alt-Right|End","Command-Right|End|Ctrl-E"),exec:function(e){e.navigateLineEnd()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selectright",bindKey:o("Shift-Right","Shift-Right"),exec:function(e){e.getSelection().selectRight()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"gotoright",bindKey:o("Right","Right|Ctrl-F"),exec:function(e,t){e.navigateRight(t.times)},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selectpagedown",bindKey:"Shift-PageDown",exec:function(e){e.selectPageDown()},readOnly:!0},{name:"pagedown",bindKey:o(null,"Option-PageDown"),exec:function(e){e.scrollPageDown()},readOnly:!0},{name:"gotopagedown",bindKey:o("PageDown","PageDown|Ctrl-V"),exec:function(e){e.gotoPageDown()},readOnly:!0},{name:"selectpageup",bindKey:"Shift-PageUp",exec:function(e){e.selectPageUp()},readOnly:!0},{name:"pageup",bindKey:o(null,"Option-PageUp"),exec:function(e){e.scrollPageUp()},readOnly:!0},{name:"gotopageup",bindKey:"PageUp",exec:function(e){e.gotoPageUp()},readOnly:!0},{name:"scrollup",bindKey:o("Ctrl-Up",null),exec:function(e){e.renderer.scrollBy(0,-2*e.renderer.layerConfig.lineHeight)},readOnly:!0},{name:"scrolldown",bindKey:o("Ctrl-Down",null),exec:function(e){e.renderer.scrollBy(0,2*e.renderer.layerConfig.lineHeight)},readOnly:!0},{name:"selectlinestart",bindKey:"Shift-Home",exec:function(e){e.getSelection().selectLineStart()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"selectlineend",bindKey:"Shift-End",exec:function(e){e.getSelection().selectLineEnd()},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"togglerecording",bindKey:o("Ctrl-Alt-E","Command-Option-E"),exec:function(e){e.commands.toggleRecording(e)},readOnly:!0},{name:"replaymacro",bindKey:o("Ctrl-Shift-E","Command-Shift-E"),exec:function(e){e.commands.replay(e)},readOnly:!0},{name:"jumptomatching",bindKey:o("Ctrl-P","Ctrl-P"),exec:function(e){e.jumpToMatching()},multiSelectAction:"forEach",scrollIntoView:"animate",readOnly:!0},{name:"selecttomatching",bindKey:o("Ctrl-Shift-P","Ctrl-Shift-P"),exec:function(e){e.jumpToMatching(!0)},multiSelectAction:"forEach",scrollIntoView:"animate",readOnly:!0},{name:"expandToMatching",bindKey:o("Ctrl-Shift-M","Ctrl-Shift-M"),exec:function(e){e.jumpToMatching(!0,!0)},multiSelectAction:"forEach",scrollIntoView:"animate",readOnly:!0},{name:"passKeysToBrowser",bindKey:o(null,null),exec:function(){},passEvent:!0,readOnly:!0},{name:"copy",exec:function(e){},readOnly:!0},{name:"cut",exec:function(e){var t=e.$copyWithEmptySelection&&e.selection.isEmpty(),n=t?e.selection.getLineRange():e.selection.getRange();e._emit("cut",n),n.isEmpty()||e.session.remove(n),e.clearSelection()},scrollIntoView:"cursor",multiSelectAction:"forEach"},{name:"paste",exec:function(e,t){e.$handlePaste(t)},scrollIntoView:"cursor"},{name:"removeline",bindKey:o("Ctrl-D","Command-D"),exec:function(e){e.removeLines()},scrollIntoView:"cursor",multiSelectAction:"forEachLine"},{name:"duplicateSelection",bindKey:o("Ctrl-Shift-D","Command-Shift-D"),exec:function(e){e.duplicateSelection()},scrollIntoView:"cursor",multiSelectAction:"forEach"},{name:"sortlines",bindKey:o("Ctrl-Alt-S","Command-Alt-S"),exec:function(e){e.sortLines()},scrollIntoView:"selection",multiSelectAction:"forEachLine"},{name:"togglecomment",bindKey:o("Ctrl-/","Command-/"),exec:function(e){e.toggleCommentLines()},multiSelectAction:"forEachLine",scrollIntoView:"selectionPart"},{name:"toggleBlockComment",bindKey:o("Ctrl-Shift-/","Command-Shift-/"),exec:function(e){e.toggleBlockComment()},multiSelectAction:"forEach",scrollIntoView:"selectionPart"},{name:"modifyNumberUp",bindKey:o("Ctrl-Shift-Up","Alt-Shift-Up"),exec:function(e){e.modifyNumber(1)},scrollIntoView:"cursor",multiSelectAction:"forEach"},{name:"modifyNumberDown",bindKey:o("Ctrl-Shift-Down","Alt-Shift-Down"),exec:function(e){e.modifyNumber(-1)},scrollIntoView:"cursor",multiSelectAction:"forEach"},{name:"replace",bindKey:o("Ctrl-H","Command-Option-F"),exec:function(e){i.loadModule("ace/ext/searchbox",function(t){t.Search(e,!0)})}},{name:"undo",bindKey:o("Ctrl-Z","Command-Z"),exec:function(e){e.undo()}},{name:"redo",bindKey:o("Ctrl-Shift-Z|Ctrl-Y","Command-Shift-Z|Command-Y"),exec:function(e){e.redo()}},{name:"copylinesup",bindKey:o("Alt-Shift-Up","Command-Option-Up"),exec:function(e){e.copyLinesUp()},scrollIntoView:"cursor"},{name:"movelinesup",bindKey:o("Alt-Up","Option-Up"),exec:function(e){e.moveLinesUp()},scrollIntoView:"cursor"},{name:"copylinesdown",bindKey:o("Alt-Shift-Down","Command-Option-Down"),exec:function(e){e.copyLinesDown()},scrollIntoView:"cursor"},{name:"movelinesdown",bindKey:o("Alt-Down","Option-Down"),exec:function(e){e.moveLinesDown()},scrollIntoView:"cursor"},{name:"del",bindKey:o("Delete","Delete|Ctrl-D|Shift-Delete"),exec:function(e){e.remove("right")},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"backspace",bindKey:o("Shift-Backspace|Backspace","Ctrl-Backspace|Shift-Backspace|Backspace|Ctrl-H"),exec:function(e){e.remove("left")},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"cut_or_delete",bindKey:o("Shift-Delete",null),exec:function(e){if(!e.selection.isEmpty())return!1;e.remove("left")},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"removetolinestart",bindKey:o("Alt-Backspace","Command-Backspace"),exec:function(e){e.removeToLineStart()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"removetolineend",bindKey:o("Alt-Delete","Ctrl-K|Command-Delete"),exec:function(e){e.removeToLineEnd()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"removetolinestarthard",bindKey:o("Ctrl-Shift-Backspace",null),exec:function(e){var t=e.selection.getRange();t.start.column=0,e.session.remove(t)},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"removetolineendhard",bindKey:o("Ctrl-Shift-Delete",null),exec:function(e){var t=e.selection.getRange();t.end.column=Number.MAX_VALUE,e.session.remove(t)},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"removewordleft",bindKey:o("Ctrl-Backspace","Alt-Backspace|Ctrl-Alt-Backspace"),exec:function(e){e.removeWordLeft()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"removewordright",bindKey:o("Ctrl-Delete","Alt-Delete"),exec:function(e){e.removeWordRight()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"outdent",bindKey:o("Shift-Tab","Shift-Tab"),exec:function(e){e.blockOutdent()},multiSelectAction:"forEach",scrollIntoView:"selectionPart"},{name:"indent",bindKey:o("Tab","Tab"),exec:function(e){e.indent()},multiSelectAction:"forEach",scrollIntoView:"selectionPart"},{name:"blockoutdent",bindKey:o("Ctrl-[","Ctrl-["),exec:function(e){e.blockOutdent()},multiSelectAction:"forEachLine",scrollIntoView:"selectionPart"},{name:"blockindent",bindKey:o("Ctrl-]","Ctrl-]"),exec:function(e){e.blockIndent()},multiSelectAction:"forEachLine",scrollIntoView:"selectionPart"},{name:"insertstring",exec:function(e,t){e.insert(t)},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"inserttext",exec:function(e,t){e.insert(r.stringRepeat(t.text||"",t.times||1))},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"splitline",bindKey:o(null,"Ctrl-O"),exec:function(e){e.splitLine()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"transposeletters",bindKey:o("Alt-Shift-X","Ctrl-T"),exec:function(e){e.transposeLetters()},multiSelectAction:function(e){e.transposeSelections(1)},scrollIntoView:"cursor"},{name:"touppercase",bindKey:o("Ctrl-U","Ctrl-U"),exec:function(e){e.toUpperCase()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"tolowercase",bindKey:o("Ctrl-Shift-U","Ctrl-Shift-U"),exec:function(e){e.toLowerCase()},multiSelectAction:"forEach",scrollIntoView:"cursor"},{name:"expandtoline",bindKey:o("Ctrl-Shift-L","Command-Shift-L"),exec:function(e){var t=e.selection.getRange();t.start.column=t.end.column=0,t.end.row++,e.selection.setRange(t,!1)},multiSelectAction:"forEach",scrollIntoView:"cursor",readOnly:!0},{name:"joinlines",bindKey:o(null,null),exec:function(e){var t=e.selection.isBackwards(),n=t?e.selection.getSelectionLead():e.selection.getSelectionAnchor(),i=t?e.selection.getSelectionAnchor():e.selection.getSelectionLead(),o=e.session.doc.getLine(n.row).length,u=e.session.doc.getTextRange(e.selection.getRange()),a=u.replace(/\n\s*/," ").length,f=e.session.doc.getLine(n.row);for(var l=n.row+1;l<=i.row+1;l++){var c=r.stringTrimLeft(r.stringTrimRight(e.session.doc.getLine(l)));c.length!==0&&(c=" "+c),f+=c}i.row+1<e.session.doc.getLength()-1&&(f+=e.session.doc.getNewLineCharacter()),e.clearSelection(),e.session.doc.replace(new s(n.row,0,i.row+2,0),f),a>0?(e.selection.moveCursorTo(n.row,n.column),e.selection.selectTo(n.row,n.column+a)):(o=e.session.doc.getLine(n.row).length>o?o+1:o,e.selection.moveCursorTo(n.row,o))},multiSelectAction:"forEach",readOnly:!0},{name:"invertSelection",bindKey:o(null,null),exec:function(e){var t=e.session.doc.getLength()-1,n=e.session.doc.getLine(t).length,r=e.selection.rangeList.ranges,i=[];r.length<1&&(r=[e.selection.getRange()]);for(var o=0;o<r.length;o++)o==r.length-1&&(r[o].end.row!==t||r[o].end.column!==n)&&i.push(new s(r[o].end.row,r[o].end.column,t,n)),o===0?(r[o].start.row!==0||r[o].start.column!==0)&&i.push(new s(0,0,r[o].start.row,r[o].start.column)):i.push(new s(r[o-1].end.row,r[o-1].end.column,r[o].start.row,r[o].start.column));e.exitMultiSelectMode(),e.clearSelection();for(var o=0;o<i.length;o++)e.selection.addRange(i[o],!1)},readOnly:!0,scrollIntoView:"none"}]}),ace.define("ace/clipboard",["require","exports","module"],function(e,t,n){"use strict";n.exports={lineMode:!1}}),ace.define("ace/editor",["require","exports","module","ace/lib/fixoldbrowsers","ace/lib/oop","ace/lib/dom","ace/lib/lang","ace/lib/useragent","ace/keyboard/textinput","ace/mouse/mouse_handler","ace/mouse/fold_handler","ace/keyboard/keybinding","ace/edit_session","ace/search","ace/range","ace/lib/event_emitter","ace/commands/command_manager","ace/commands/default_commands","ace/config","ace/token_iterator","ace/clipboard"],function(e,t,n){"use strict";e("./lib/fixoldbrowsers");var r=e("./lib/oop"),i=e("./lib/dom"),s=e("./lib/lang"),o=e("./lib/useragent"),u=e("./keyboard/textinput").TextInput,a=e("./mouse/mouse_handler").MouseHandler,f=e("./mouse/fold_handler").FoldHandler,l=e("./keyboard/keybinding").KeyBinding,c=e("./edit_session").EditSession,h=e("./search").Search,p=e("./range").Range,d=e("./lib/event_emitter").EventEmitter,v=e("./commands/command_manager").CommandManager,m=e("./commands/default_commands").commands,g=e("./config"),y=e("./token_iterator").TokenIterator,b=e("./clipboard"),w=function(e,t,n){var r=e.getContainerElement();this.container=r,this.renderer=e,this.id="editor"+ ++w.$uid,this.commands=new v(o.isMac?"mac":"win",m),typeof document=="object"&&(this.textInput=new u(e.getTextAreaContainer(),this),this.renderer.textarea=this.textInput.getElement(),this.$mouseHandler=new a(this),new f(this)),this.keyBinding=new l(this),this.$search=(new h).set({wrap:!0}),this.$historyTracker=this.$historyTracker.bind(this),this.commands.on("exec",this.$historyTracker),this.$initOperationListeners(),this._$emitInputEvent=s.delayedCall(function(){this._signal("input",{}),this.session&&this.session.bgTokenizer&&this.session.bgTokenizer.scheduleStart()}.bind(this)),this.on("change",function(e,t){t._$emitInputEvent.schedule(31)}),this.setSession(t||n&&n.session||new c("")),g.resetOptions(this),n&&this.setOptions(n),g._signal("editor",this)};w.$uid=0,function(){r.implement(this,d),this.$initOperationListeners=function(){this.commands.on("exec",this.startOperation.bind(this),!0),this.commands.on("afterExec",this.endOperation.bind(this),!0),this.$opResetTimer=s.delayedCall(this.endOperation.bind(this)),this.on("change",function(){this.curOp||(this.startOperation(),this.curOp.selectionBefore=this.$lastSel),this.curOp.docChanged=!0}.bind(this),!0),this.on("changeSelection",function(){this.curOp||(this.startOperation(),this.curOp.selectionBefore=this.$lastSel),this.curOp.selectionChanged=!0}.bind(this),!0)},this.curOp=null,this.prevOp={},this.startOperation=function(e){if(this.curOp){if(!e||this.curOp.command)return;this.prevOp=this.curOp}e||(this.previousCommand=null,e={}),this.$opResetTimer.schedule(),this.curOp=this.session.curOp={command:e.command||{},args:e.args,scrollTop:this.renderer.scrollTop},this.curOp.selectionBefore=this.selection.toJSON()},this.endOperation=function(e){if(this.curOp){if(e&&e.returnValue===!1)return this.curOp=null;this._signal("beforeEndOperation");var t=this.curOp.command,n=t&&t.scrollIntoView;if(n){switch(n){case"center-animate":n="animate";case"center":this.renderer.scrollCursorIntoView(null,.5);break;case"animate":case"cursor":this.renderer.scrollCursorIntoView();break;case"selectionPart":var r=this.selection.getRange(),i=this.renderer.layerConfig;(r.start.row>=i.lastRow||r.end.row<=i.firstRow)&&this.renderer.scrollSelectionIntoView(this.selection.anchor,this.selection.lead);break;default:}n=="animate"&&this.renderer.animateScrolling(this.curOp.scrollTop)}var s=this.selection.toJSON();this.curOp.selectionAfter=s,this.$lastSel=this.selection.toJSON(),this.session.getUndoManager().addSelection(s),this.prevOp=this.curOp,this.curOp=null}},this.$mergeableCommands=["backspace","del","insertstring"],this.$historyTracker=function(e){if(!this.$mergeUndoDeltas)return;var t=this.prevOp,n=this.$mergeableCommands,r=t.command&&e.command.name==t.command.name;if(e.command.name=="insertstring"){var i=e.args;this.mergeNextCommand===undefined&&(this.mergeNextCommand=!0),r=r&&this.mergeNextCommand&&(!/\s/.test(i)||/\s/.test(t.args)),this.mergeNextCommand=!0}else r=r&&n.indexOf(e.command.name)!==-1;this.$mergeUndoDeltas!="always"&&Date.now()-this.sequenceStartTime>2e3&&(r=!1),r?this.session.mergeUndoDeltas=!0:n.indexOf(e.command.name)!==-1&&(this.sequenceStartTime=Date.now())},this.setKeyboardHandler=function(e,t){if(e&&typeof e=="string"&&e!="ace"){this.$keybindingId=e;var n=this;g.loadModule(["keybinding",e],function(r){n.$keybindingId==e&&n.keyBinding.setKeyboardHandler(r&&r.handler),t&&t()})}else this.$keybindingId=null,this.keyBinding.setKeyboardHandler(e),t&&t()},this.getKeyboardHandler=function(){return this.keyBinding.getKeyboardHandler()},this.setSession=function(e){if(this.session==e)return;this.curOp&&this.endOperation(),this.curOp={};var t=this.session;if(t){this.session.off("change",this.$onDocumentChange),this.session.off("changeMode",this.$onChangeMode),this.session.off("tokenizerUpdate",this.$onTokenizerUpdate),this.session.off("changeTabSize",this.$onChangeTabSize),this.session.off("changeWrapLimit",this.$onChangeWrapLimit),this.session.off("changeWrapMode",this.$onChangeWrapMode),this.session.off("changeFold",this.$onChangeFold),this.session.off("changeFrontMarker",this.$onChangeFrontMarker),this.session.off("changeBackMarker",this.$onChangeBackMarker),this.session.off("changeBreakpoint",this.$onChangeBreakpoint),this.session.off("changeAnnotation",this.$onChangeAnnotation),this.session.off("changeOverwrite",this.$onCursorChange),this.session.off("changeScrollTop",this.$onScrollTopChange),this.session.off("changeScrollLeft",this.$onScrollLeftChange);var n=this.session.getSelection();n.off("changeCursor",this.$onCursorChange),n.off("changeSelection",this.$onSelectionChange)}this.session=e,e?(this.$onDocumentChange=this.onDocumentChange.bind(this),e.on("change",this.$onDocumentChange),this.renderer.setSession(e),this.$onChangeMode=this.onChangeMode.bind(this),e.on("changeMode",this.$onChangeMode),this.$onTokenizerUpdate=this.onTokenizerUpdate.bind(this),e.on("tokenizerUpdate",this.$onTokenizerUpdate),this.$onChangeTabSize=this.renderer.onChangeTabSize.bind(this.renderer),e.on("changeTabSize",this.$onChangeTabSize),this.$onChangeWrapLimit=this.onChangeWrapLimit.bind(this),e.on("changeWrapLimit",this.$onChangeWrapLimit),this.$onChangeWrapMode=this.onChangeWrapMode.bind(this),e.on("changeWrapMode",this.$onChangeWrapMode),this.$onChangeFold=this.onChangeFold.bind(this),e.on("changeFold",this.$onChangeFold),this.$onChangeFrontMarker=this.onChangeFrontMarker.bind(this),this.session.on("changeFrontMarker",this.$onChangeFrontMarker),this.$onChangeBackMarker=this.onChangeBackMarker.bind(this),this.session.on("changeBackMarker",this.$onChangeBackMarker),this.$onChangeBreakpoint=this.onChangeBreakpoint.bind(this),this.session.on("changeBreakpoint",this.$onChangeBreakpoint),this.$onChangeAnnotation=this.onChangeAnnotation.bind(this),this.session.on("changeAnnotation",this.$onChangeAnnotation),this.$onCursorChange=this.onCursorChange.bind(this),this.session.on("changeOverwrite",this.$onCursorChange),this.$onScrollTopChange=this.onScrollTopChange.bind(this),this.session.on("changeScrollTop",this.$onScrollTopChange),this.$onScrollLeftChange=this.onScrollLeftChange.bind(this),this.session.on("changeScrollLeft",this.$onScrollLeftChange),this.selection=e.getSelection(),this.selection.on("changeCursor",this.$onCursorChange),this.$onSelectionChange=this.onSelectionChange.bind(this),this.selection.on("changeSelection",this.$onSelectionChange),this.onChangeMode(),this.onCursorChange(),this.onScrollTopChange(),this.onScrollLeftChange(),this.onSelectionChange(),this.onChangeFrontMarker(),this.onChangeBackMarker(),this.onChangeBreakpoint(),this.onChangeAnnotation(),this.session.getUseWrapMode()&&this.renderer.adjustWrapLimit(),this.renderer.updateFull()):(this.selection=null,this.renderer.setSession(e)),this._signal("changeSession",{session:e,oldSession:t}),this.curOp=null,t&&t._signal("changeEditor",{oldEditor:this}),e&&e._signal("changeEditor",{editor:this}),e&&e.bgTokenizer&&e.bgTokenizer.scheduleStart()},this.getSession=function(){return this.session},this.setValue=function(e,t){return this.session.doc.setValue(e),t?t==1?this.navigateFileEnd():t==-1&&this.navigateFileStart():this.selectAll(),e},this.getValue=function(){return this.session.getValue()},this.getSelection=function(){return this.selection},this.resize=function(e){this.renderer.onResize(e)},this.setTheme=function(e,t){this.renderer.setTheme(e,t)},this.getTheme=function(){return this.renderer.getTheme()},this.setStyle=function(e){this.renderer.setStyle(e)},this.unsetStyle=function(e){this.renderer.unsetStyle(e)},this.getFontSize=function(){return this.getOption("fontSize")||i.computedStyle(this.container,"fontSize")},this.setFontSize=function(e){this.setOption("fontSize",e)},this.$highlightBrackets=function(){this.session.$bracketHighlight&&(this.session.removeMarker(this.session.$bracketHighlight),this.session.$bracketHighlight=null);if(this.$highlightPending)return;var e=this;this.$highlightPending=!0,setTimeout(function(){e.$highlightPending=!1;var t=e.session;if(!t||!t.bgTokenizer)return;var n=t.findMatchingBracket(e.getCursorPosition());if(n)var r=new p(n.row,n.column,n.row,n.column+1);else if(t.$mode.getMatching)var r=t.$mode.getMatching(e.session);r&&(t.$bracketHighlight=t.addMarker(r,"ace_bracket","text"))},50)},this.$highlightTags=function(){if(this.$highlightTagPending)return;var e=this;this.$highlightTagPending=!0,setTimeout(function(){e.$highlightTagPending=!1;var t=e.session;if(!t||!t.bgTokenizer)return;var n=e.getCursorPosition(),r=new y(e.session,n.row,n.column),i=r.getCurrentToken();if(!i||!/\b(?:tag-open|tag-name)/.test(i.type)){t.removeMarker(t.$tagHighlight),t.$tagHighlight=null;return}if(i.type.indexOf("tag-open")!=-1){i=r.stepForward();if(!i)return}var s=i.value,o=0,u=r.stepBackward();if(u.value=="<"){do u=i,i=r.stepForward(),i&&i.value===s&&i.type.indexOf("tag-name")!==-1&&(u.value==="<"?o++:u.value==="</"&&o--);while(i&&o>=0)}else{do i=u,u=r.stepBackward(),i&&i.value===s&&i.type.indexOf("tag-name")!==-1&&(u.value==="<"?o++:u.value==="</"&&o--);while(u&&o<=0);r.stepForward()}if(!i){t.removeMarker(t.$tagHighlight),t.$tagHighlight=null;return}var a=r.getCurrentTokenRow(),f=r.getCurrentTokenColumn(),l=new p(a,f,a,f+i.value.length),c=t.$backMarkers[t.$tagHighlight];t.$tagHighlight&&c!=undefined&&l.compareRange(c.range)!==0&&(t.removeMarker(t.$tagHighlight),t.$tagHighlight=null),t.$tagHighlight||(t.$tagHighlight=t.addMarker(l,"ace_bracket","text"))},50)},this.focus=function(){var e=this;setTimeout(function(){e.isFocused()||e.textInput.focus()}),this.textInput.focus()},this.isFocused=function(){return this.textInput.isFocused()},this.blur=function(){this.textInput.blur()},this.onFocus=function(e){if(this.$isFocused)return;this.$isFocused=!0,this.renderer.showCursor(),this.renderer.visualizeFocus(),this._emit("focus",e)},this.onBlur=function(e){if(!this.$isFocused)return;this.$isFocused=!1,this.renderer.hideCursor(),this.renderer.visualizeBlur(),this._emit("blur",e)},this.$cursorChange=function(){this.renderer.updateCursor()},this.onDocumentChange=function(e){var t=this.session.$useWrapMode,n=e.start.row==e.end.row?e.end.row:Infinity;this.renderer.updateLines(e.start.row,n,t),this._signal("change",e),this.$cursorChange(),this.$updateHighlightActiveLine()},this.onTokenizerUpdate=function(e){var t=e.data;this.renderer.updateLines(t.first,t.last)},this.onScrollTopChange=function(){this.renderer.scrollToY(this.session.getScrollTop())},this.onScrollLeftChange=function(){this.renderer.scrollToX(this.session.getScrollLeft())},this.onCursorChange=function(){this.$cursorChange(),this.$highlightBrackets(),this.$highlightTags(),this.$updateHighlightActiveLine(),this._signal("changeSelection")},this.$updateHighlightActiveLine=function(){var e=this.getSession(),t;if(this.$highlightActiveLine){if(this.$selectionStyle!="line"||!this.selection.isMultiLine())t=this.getCursorPosition();this.renderer.theme&&this.renderer.theme.$selectionColorConflict&&!this.selection.isEmpty()&&(t=!1),this.renderer.$maxLines&&this.session.getLength()===1&&!(this.renderer.$minLines>1)&&(t=!1)}if(e.$highlightLineMarker&&!t)e.removeMarker(e.$highlightLineMarker.id),e.$highlightLineMarker=null;else if(!e.$highlightLineMarker&&t){var n=new p(t.row,t.column,t.row,Infinity);n.id=e.addMarker(n,"ace_active-line","screenLine"),e.$highlightLineMarker=n}else t&&(e.$highlightLineMarker.start.row=t.row,e.$highlightLineMarker.end.row=t.row,e.$highlightLineMarker.start.column=t.column,e._signal("changeBackMarker"))},this.onSelectionChange=function(e){var t=this.session;t.$selectionMarker&&t.removeMarker(t.$selectionMarker),t.$selectionMarker=null;if(!this.selection.isEmpty()){var n=this.selection.getRange(),r=this.getSelectionStyle();t.$selectionMarker=t.addMarker(n,"ace_selection",r)}else this.$updateHighlightActiveLine();var i=this.$highlightSelectedWord&&this.$getSelectionHighLightRegexp();this.session.highlight(i),this._signal("changeSelection")},this.$getSelectionHighLightRegexp=function(){var e=this.session,t=this.getSelectionRange();if(t.isEmpty()||t.isMultiLine())return;var n=t.start.column,r=t.end.column,i=e.getLine(t.start.row),s=i.substring(n,r);if(s.length>5e3||!/[\w\d]/.test(s))return;var o=this.$search.$assembleRegExp({wholeWord:!0,caseSensitive:!0,needle:s}),u=i.substring(n-1,r+1);if(!o.test(u))return;return o},this.onChangeFrontMarker=function(){this.renderer.updateFrontMarkers()},this.onChangeBackMarker=function(){this.renderer.updateBackMarkers()},this.onChangeBreakpoint=function(){this.renderer.updateBreakpoints()},this.onChangeAnnotation=function(){this.renderer.setAnnotations(this.session.getAnnotations())},this.onChangeMode=function(e){this.renderer.updateText(),this._emit("changeMode",e)},this.onChangeWrapLimit=function(){this.renderer.updateFull()},this.onChangeWrapMode=function(){this.renderer.onResize(!0)},this.onChangeFold=function(){this.$updateHighlightActiveLine(),this.renderer.updateFull()},this.getSelectedText=function(){return this.session.getTextRange(this.getSelectionRange())},this.getCopyText=function(){var e=this.getSelectedText(),t=this.session.doc.getNewLineCharacter(),n=!1;if(!e&&this.$copyWithEmptySelection){n=!0;var r=this.selection.getAllRanges();for(var i=0;i<r.length;i++){var s=r[i];if(i&&r[i-1].start.row==s.start.row)continue;e+=this.session.getLine(s.start.row)+t}}var o={text:e};return this._signal("copy",o),b.lineMode=n?o.text:"",o.text},this.onCopy=function(){this.commands.exec("copy",this)},this.onCut=function(){this.commands.exec("cut",this)},this.onPaste=function(e,t){var n={text:e,event:t};this.commands.exec("paste",this,n)},this.$handlePaste=function(e){typeof e=="string"&&(e={text:e}),this._signal("paste",e);var t=e.text,n=t==b.lineMode,r=this.session;if(!this.inMultiSelectMode||this.inVirtualSelectionMode)n?r.insert({row:this.selection.lead.row,column:0},t):this.insert(t);else if(n)this.selection.rangeList.ranges.forEach(function(e){r.insert({row:e.start.row,column:0},t)});else{var i=t.split(/\r\n|\r|\n/),s=this.selection.rangeList.ranges;if(i.length>s.length||i.length<2||!i[1])return this.commands.exec("insertstring",this,t);for(var o=s.length;o--;){var u=s[o];u.isEmpty()||r.remove(u),r.insert(u.start,i[o])}}},this.execCommand=function(e,t){return this.commands.exec(e,this,t)},this.insert=function(e,t){var n=this.session,r=n.getMode(),i=this.getCursorPosition();if(this.getBehavioursEnabled()&&!t){var s=r.transformAction(n.getState(i.row),"insertion",this,n,e);s&&(e!==s.text&&(this.inVirtualSelectionMode||(this.session.mergeUndoDeltas=!1,this.mergeNextCommand=!1)),e=s.text)}e=="	"&&(e=this.session.getTabString());if(!this.selection.isEmpty()){var o=this.getSelectionRange();i=this.session.remove(o),this.clearSelection()}else if(this.session.getOverwrite()&&e.indexOf("\n")==-1){var o=new p.fromPoints(i,i);o.end.column+=e.length,this.session.remove(o)}if(e=="\n"||e=="\r\n"){var u=n.getLine(i.row);if(i.column>u.search(/\S|$/)){var a=u.substr(i.column).search(/\S|$/);n.doc.removeInLine(i.row,i.column,i.column+a)}}this.clearSelection();var f=i.column,l=n.getState(i.row),u=n.getLine(i.row),c=r.checkOutdent(l,u,e),h=n.insert(i,e);s&&s.selection&&(s.selection.length==2?this.selection.setSelectionRange(new p(i.row,f+s.selection[0],i.row,f+s.selection[1])):this.selection.setSelectionRange(new p(i.row+s.selection[0],s.selection[1],i.row+s.selection[2],s.selection[3])));if(n.getDocument().isNewLine(e)){var d=r.getNextLineIndent(l,u.slice(0,i.column),n.getTabString());n.insert({row:i.row+1,column:0},d)}c&&r.autoOutdent(l,n,i.row)},this.onTextInput=function(e){this.keyBinding.onTextInput(e)},this.onCommandKey=function(e,t,n){this.keyBinding.onCommandKey(e,t,n)},this.setOverwrite=function(e){this.session.setOverwrite(e)},this.getOverwrite=function(){return this.session.getOverwrite()},this.toggleOverwrite=function(){this.session.toggleOverwrite()},this.setScrollSpeed=function(e){this.setOption("scrollSpeed",e)},this.getScrollSpeed=function(){return this.getOption("scrollSpeed")},this.setDragDelay=function(e){this.setOption("dragDelay",e)},this.getDragDelay=function(){return this.getOption("dragDelay")},this.setSelectionStyle=function(e){this.setOption("selectionStyle",e)},this.getSelectionStyle=function(){return this.getOption("selectionStyle")},this.setHighlightActiveLine=function(e){this.setOption("highlightActiveLine",e)},this.getHighlightActiveLine=function(){return this.getOption("highlightActiveLine")},this.setHighlightGutterLine=function(e){this.setOption("highlightGutterLine",e)},this.getHighlightGutterLine=function(){return this.getOption("highlightGutterLine")},this.setHighlightSelectedWord=function(e){this.setOption("highlightSelectedWord",e)},this.getHighlightSelectedWord=function(){return this.$highlightSelectedWord},this.setAnimatedScroll=function(e){this.renderer.setAnimatedScroll(e)},this.getAnimatedScroll=function(){return this.renderer.getAnimatedScroll()},this.setShowInvisibles=function(e){this.renderer.setShowInvisibles(e)},this.getShowInvisibles=function(){return this.renderer.getShowInvisibles()},this.setDisplayIndentGuides=function(e){this.renderer.setDisplayIndentGuides(e)},this.getDisplayIndentGuides=function(){return this.renderer.getDisplayIndentGuides()},this.setShowPrintMargin=function(e){this.renderer.setShowPrintMargin(e)},this.getShowPrintMargin=function(){return this.renderer.getShowPrintMargin()},this.setPrintMarginColumn=function(e){this.renderer.setPrintMarginColumn(e)},this.getPrintMarginColumn=function(){return this.renderer.getPrintMarginColumn()},this.setReadOnly=function(e){this.setOption("readOnly",e)},this.getReadOnly=function(){return this.getOption("readOnly")},this.setBehavioursEnabled=function(e){this.setOption("behavioursEnabled",e)},this.getBehavioursEnabled=function(){return this.getOption("behavioursEnabled")},this.setWrapBehavioursEnabled=function(e){this.setOption("wrapBehavioursEnabled",e)},this.getWrapBehavioursEnabled=function(){return this.getOption("wrapBehavioursEnabled")},this.setShowFoldWidgets=function(e){this.setOption("showFoldWidgets",e)},this.getShowFoldWidgets=function(){return this.getOption("showFoldWidgets")},this.setFadeFoldWidgets=function(e){this.setOption("fadeFoldWidgets",e)},this.getFadeFoldWidgets=function(){return this.getOption("fadeFoldWidgets")},this.remove=function(e){this.selection.isEmpty()&&(e=="left"?this.selection.selectLeft():this.selection.selectRight());var t=this.getSelectionRange();if(this.getBehavioursEnabled()){var n=this.session,r=n.getState(t.start.row),i=n.getMode().transformAction(r,"deletion",this,n,t);if(t.end.column===0){var s=n.getTextRange(t);if(s[s.length-1]=="\n"){var o=n.getLine(t.end.row);/^\s+$/.test(o)&&(t.end.column=o.length)}}i&&(t=i)}this.session.remove(t),this.clearSelection()},this.removeWordRight=function(){this.selection.isEmpty()&&this.selection.selectWordRight(),this.session.remove(this.getSelectionRange()),this.clearSelection()},this.removeWordLeft=function(){this.selection.isEmpty()&&this.selection.selectWordLeft(),this.session.remove(this.getSelectionRange()),this.clearSelection()},this.removeToLineStart=function(){this.selection.isEmpty()&&this.selection.selectLineStart(),this.session.remove(this.getSelectionRange()),this.clearSelection()},this.removeToLineEnd=function(){this.selection.isEmpty()&&this.selection.selectLineEnd();var e=this.getSelectionRange();e.start.column==e.end.column&&e.start.row==e.end.row&&(e.end.column=0,e.end.row++),this.session.remove(e),this.clearSelection()},this.splitLine=function(){this.selection.isEmpty()||(this.session.remove(this.getSelectionRange()),this.clearSelection());var e=this.getCursorPosition();this.insert("\n"),this.moveCursorToPosition(e)},this.transposeLetters=function(){if(!this.selection.isEmpty())return;var e=this.getCursorPosition(),t=e.column;if(t===0)return;var n=this.session.getLine(e.row),r,i;t<n.length?(r=n.charAt(t)+n.charAt(t-1),i=new p(e.row,t-1,e.row,t+1)):(r=n.charAt(t-1)+n.charAt(t-2),i=new p(e.row,t-2,e.row,t)),this.session.replace(i,r),this.session.selection.moveToPosition(i.end)},this.toLowerCase=function(){var e=this.getSelectionRange();this.selection.isEmpty()&&this.selection.selectWord();var t=this.getSelectionRange(),n=this.session.getTextRange(t);this.session.replace(t,n.toLowerCase()),this.selection.setSelectionRange(e)},this.toUpperCase=function(){var e=this.getSelectionRange();this.selection.isEmpty()&&this.selection.selectWord();var t=this.getSelectionRange(),n=this.session.getTextRange(t);this.session.replace(t,n.toUpperCase()),this.selection.setSelectionRange(e)},this.indent=function(){var e=this.session,t=this.getSelectionRange();if(t.start.row<t.end.row){var n=this.$getSelectedRows();e.indentRows(n.first,n.last,"	");return}if(t.start.column<t.end.column){var r=e.getTextRange(t);if(!/^\s+$/.test(r)){var n=this.$getSelectedRows();e.indentRows(n.first,n.last,"	");return}}var i=e.getLine(t.start.row),o=t.start,u=e.getTabSize(),a=e.documentToScreenColumn(o.row,o.column);if(this.session.getUseSoftTabs())var f=u-a%u,l=s.stringRepeat(" ",f);else{var f=a%u;while(i[t.start.column-1]==" "&&f)t.start.column--,f--;this.selection.setSelectionRange(t),l="	"}return this.insert(l)},this.blockIndent=function(){var e=this.$getSelectedRows();this.session.indentRows(e.first,e.last,"	")},this.blockOutdent=function(){var e=this.session.getSelection();this.session.outdentRows(e.getRange())},this.sortLines=function(){var e=this.$getSelectedRows(),t=this.session,n=[];for(var r=e.first;r<=e.last;r++)n.push(t.getLine(r));n.sort(function(e,t){return e.toLowerCase()<t.toLowerCase()?-1:e.toLowerCase()>t.toLowerCase()?1:0});var i=new p(0,0,0,0);for(var r=e.first;r<=e.last;r++){var s=t.getLine(r);i.start.row=r,i.end.row=r,i.end.column=s.length,t.replace(i,n[r-e.first])}},this.toggleCommentLines=function(){var e=this.session.getState(this.getCursorPosition().row),t=this.$getSelectedRows();this.session.getMode().toggleCommentLines(e,this.session,t.first,t.last)},this.toggleBlockComment=function(){var e=this.getCursorPosition(),t=this.session.getState(e.row),n=this.getSelectionRange();this.session.getMode().toggleBlockComment(t,this.session,n,e)},this.getNumberAt=function(e,t){var n=/[\-]?[0-9]+(?:\.[0-9]+)?/g;n.lastIndex=0;var r=this.session.getLine(e);while(n.lastIndex<t){var i=n.exec(r);if(i.index<=t&&i.index+i[0].length>=t){var s={value:i[0],start:i.index,end:i.index+i[0].length};return s}}return null},this.modifyNumber=function(e){var t=this.selection.getCursor().row,n=this.selection.getCursor().column,r=new p(t,n-1,t,n),i=this.session.getTextRange(r);if(!isNaN(parseFloat(i))&&isFinite(i)){var s=this.getNumberAt(t,n);if(s){var o=s.value.indexOf(".")>=0?s.start+s.value.indexOf(".")+1:s.end,u=s.start+s.value.length-o,a=parseFloat(s.value);a*=Math.pow(10,u),o!==s.end&&n<o?e*=Math.pow(10,s.end-n-1):e*=Math.pow(10,s.end-n),a+=e,a/=Math.pow(10,u);var f=a.toFixed(u),l=new p(t,s.start,t,s.end);this.session.replace(l,f),this.moveCursorTo(t,Math.max(s.start+1,n+f.length-s.value.length))}}},this.removeLines=function(){var e=this.$getSelectedRows();this.session.removeFullLines(e.first,e.last),this.clearSelection()},this.duplicateSelection=function(){var e=this.selection,t=this.session,n=e.getRange(),r=e.isBackwards();if(n.isEmpty()){var i=n.start.row;t.duplicateLines(i,i)}else{var s=r?n.start:n.end,o=t.insert(s,t.getTextRange(n),!1);n.start=s,n.end=o,e.setSelectionRange(n,r)}},this.moveLinesDown=function(){this.$moveLines(1,!1)},this.moveLinesUp=function(){this.$moveLines(-1,!1)},this.moveText=function(e,t,n){return this.session.moveText(e,t,n)},this.copyLinesUp=function(){this.$moveLines(-1,!0)},this.copyLinesDown=function(){this.$moveLines(1,!0)},this.$moveLines=function(e,t){var n,r,i=this.selection;if(!i.inMultiSelectMode||this.inVirtualSelectionMode){var s=i.toOrientedRange();n=this.$getSelectedRows(s),r=this.session.$moveLines(n.first,n.last,t?0:e),t&&e==-1&&(r=0),s.moveBy(r,0),i.fromOrientedRange(s)}else{var o=i.rangeList.ranges;i.rangeList.detach(this.session),this.inVirtualSelectionMode=!0;var u=0,a=0,f=o.length;for(var l=0;l<f;l++){var c=l;o[l].moveBy(u,0),n=this.$getSelectedRows(o[l]);var h=n.first,p=n.last;while(++l<f){a&&o[l].moveBy(a,0);var d=this.$getSelectedRows(o[l]);if(t&&d.first!=p)break;if(!t&&d.first>p+1)break;p=d.last}l--,u=this.session.$moveLines(h,p,t?0:e),t&&e==-1&&(c=l+1);while(c<=l)o[c].moveBy(u,0),c++;t||(u=0),a+=u}i.fromOrientedRange(i.ranges[0]),i.rangeList.attach(this.session),this.inVirtualSelectionMode=!1}},this.$getSelectedRows=function(e){return e=(e||this.getSelectionRange()).collapseRows(),{first:this.session.getRowFoldStart(e.start.row),last:this.session.getRowFoldEnd(e.end.row)}},this.onCompositionStart=function(e){this.renderer.showComposition(this.getCursorPosition())},this.onCompositionUpdate=function(e){this.renderer.setCompositionText(e)},this.onCompositionEnd=function(){this.renderer.hideComposition()},this.getFirstVisibleRow=function(){return this.renderer.getFirstVisibleRow()},this.getLastVisibleRow=function(){return this.renderer.getLastVisibleRow()},this.isRowVisible=function(e){return e>=this.getFirstVisibleRow()&&e<=this.getLastVisibleRow()},this.isRowFullyVisible=function(e){return e>=this.renderer.getFirstFullyVisibleRow()&&e<=this.renderer.getLastFullyVisibleRow()},this.$getVisibleRowCount=function(){return this.renderer.getScrollBottomRow()-this.renderer.getScrollTopRow()+1},this.$moveByPage=function(e,t){var n=this.renderer,r=this.renderer.layerConfig,i=e*Math.floor(r.height/r.lineHeight);t===!0?this.selection.$moveSelection(function(){this.moveCursorBy(i,0)}):t===!1&&(this.selection.moveCursorBy(i,0),this.selection.clearSelection());var s=n.scrollTop;n.scrollBy(0,i*r.lineHeight),t!=null&&n.scrollCursorIntoView(null,.5),n.animateScrolling(s)},this.selectPageDown=function(){this.$moveByPage(1,!0)},this.selectPageUp=function(){this.$moveByPage(-1,!0)},this.gotoPageDown=function(){this.$moveByPage(1,!1)},this.gotoPageUp=function(){this.$moveByPage(-1,!1)},this.scrollPageDown=function(){this.$moveByPage(1)},this.scrollPageUp=function(){this.$moveByPage(-1)},this.scrollToRow=function(e){this.renderer.scrollToRow(e)},this.scrollToLine=function(e,t,n,r){this.renderer.scrollToLine(e,t,n,r)},this.centerSelection=function(){var e=this.getSelectionRange(),t={row:Math.floor(e.start.row+(e.end.row-e.start.row)/2),column:Math.floor(e.start.column+(e.end.column-e.start.column)/2)};this.renderer.alignCursor(t,.5)},this.getCursorPosition=function(){return this.selection.getCursor()},this.getCursorPositionScreen=function(){return this.session.documentToScreenPosition(this.getCursorPosition())},this.getSelectionRange=function(){return this.selection.getRange()},this.selectAll=function(){this.selection.selectAll()},this.clearSelection=function(){this.selection.clearSelection()},this.moveCursorTo=function(e,t){this.selection.moveCursorTo(e,t)},this.moveCursorToPosition=function(e){this.selection.moveCursorToPosition(e)},this.jumpToMatching=function(e,t){var n=this.getCursorPosition(),r=new y(this.session,n.row,n.column),i=r.getCurrentToken(),s=i||r.stepForward();if(!s)return;var o,u=!1,a={},f=n.column-s.start,l,c={")":"(","(":"(","]":"[","[":"[","{":"{","}":"{"};do{if(s.value.match(/[{}()\[\]]/g))for(;f<s.value.length&&!u;f++){if(!c[s.value[f]])continue;l=c[s.value[f]]+"."+s.type.replace("rparen","lparen"),isNaN(a[l])&&(a[l]=0);switch(s.value[f]){case"(":case"[":case"{":a[l]++;break;case")":case"]":case"}":a[l]--,a[l]===-1&&(o="bracket",u=!0)}}else s.type.indexOf("tag-name")!==-1&&(isNaN(a[s.value])&&(a[s.value]=0),i.value==="<"?a[s.value]++:i.value==="</"&&a[s.value]--,a[s.value]===-1&&(o="tag",u=!0));u||(i=s,s=r.stepForward(),f=0)}while(s&&!u);if(!o)return;var h,d;if(o==="bracket"){h=this.session.getBracketRange(n);if(!h){h=new p(r.getCurrentTokenRow(),r.getCurrentTokenColumn()+f-1,r.getCurrentTokenRow(),r.getCurrentTokenColumn()+f-1),d=h.start;if(t||d.row===n.row&&Math.abs(d.column-n.column)<2)h=this.session.getBracketRange(d)}}else if(o==="tag"){if(!s||s.type.indexOf("tag-name")===-1)return;var v=s.value;h=new p(r.getCurrentTokenRow(),r.getCurrentTokenColumn()-2,r.getCurrentTokenRow(),r.getCurrentTokenColumn()-2);if(h.compare(n.row,n.column)===0){u=!1;do s=i,i=r.stepBackward(),i&&(i.type.indexOf("tag-close")!==-1&&h.setEnd(r.getCurrentTokenRow(),r.getCurrentTokenColumn()+1),s.value===v&&s.type.indexOf("tag-name")!==-1&&(i.value==="<"?a[v]++:i.value==="</"&&a[v]--,a[v]===0&&(u=!0)));while(i&&!u)}s&&s.type.indexOf("tag-name")&&(d=h.start,d.row==n.row&&Math.abs(d.column-n.column)<2&&(d=h.end))}d=h&&h.cursor||d,d&&(e?h&&t?this.selection.setRange(h):h&&h.isEqual(this.getSelectionRange())?this.clearSelection():this.selection.selectTo(d.row,d.column):this.selection.moveTo(d.row,d.column))},this.gotoLine=function(e,t,n){this.selection.clearSelection(),this.session.unfold({row:e-1,column:t||0}),this.exitMultiSelectMode&&this.exitMultiSelectMode(),this.moveCursorTo(e-1,t||0),this.isRowFullyVisible(e-1)||this.scrollToLine(e-1,!0,n)},this.navigateTo=function(e,t){this.selection.moveTo(e,t)},this.navigateUp=function(e){if(this.selection.isMultiLine()&&!this.selection.isBackwards()){var t=this.selection.anchor.getPosition();return this.moveCursorToPosition(t)}this.selection.clearSelection(),this.selection.moveCursorBy(-e||-1,0)},this.navigateDown=function(e){if(this.selection.isMultiLine()&&this.selection.isBackwards()){var t=this.selection.anchor.getPosition();return this.moveCursorToPosition(t)}this.selection.clearSelection(),this.selection.moveCursorBy(e||1,0)},this.navigateLeft=function(e){if(!this.selection.isEmpty()){var t=this.getSelectionRange().start;this.moveCursorToPosition(t)}else{e=e||1;while(e--)this.selection.moveCursorLeft()}this.clearSelection()},this.navigateRight=function(e){if(!this.selection.isEmpty()){var t=this.getSelectionRange().end;this.moveCursorToPosition(t)}else{e=e||1;while(e--)this.selection.moveCursorRight()}this.clearSelection()},this.navigateLineStart=function(){this.selection.moveCursorLineStart(),this.clearSelection()},this.navigateLineEnd=function(){this.selection.moveCursorLineEnd(),this.clearSelection()},this.navigateFileEnd=function(){this.selection.moveCursorFileEnd(),this.clearSelection()},this.navigateFileStart=function(){this.selection.moveCursorFileStart(),this.clearSelection()},this.navigateWordRight=function(){this.selection.moveCursorWordRight(),this.clearSelection()},this.navigateWordLeft=function(){this.selection.moveCursorWordLeft(),this.clearSelection()},this.replace=function(e,t){t&&this.$search.set(t);var n=this.$search.find(this.session),r=0;return n?(this.$tryReplace(n,e)&&(r=1),this.selection.setSelectionRange(n),this.renderer.scrollSelectionIntoView(n.start,n.end),r):r},this.replaceAll=function(e,t){t&&this.$search.set(t);var n=this.$search.findAll(this.session),r=0;if(!n.length)return r;var i=this.getSelectionRange();this.selection.moveTo(0,0);for(var s=n.length-1;s>=0;--s)this.$tryReplace(n[s],e)&&r++;return this.selection.setSelectionRange(i),r},this.$tryReplace=function(e,t){var n=this.session.getTextRange(e);return t=this.$search.replace(n,t),t!==null?(e.end=this.session.replace(e,t),e):null},this.getLastSearchOptions=function(){return this.$search.getOptions()},this.find=function(e,t,n){t||(t={}),typeof e=="string"||e instanceof RegExp?t.needle=e:typeof e=="object"&&r.mixin(t,e);var i=this.selection.getRange();t.needle==null&&(e=this.session.getTextRange(i)||this.$search.$options.needle,e||(i=this.session.getWordRange(i.start.row,i.start.column),e=this.session.getTextRange(i)),this.$search.set({needle:e})),this.$search.set(t),t.start||this.$search.set({start:i});var s=this.$search.find(this.session);if(t.preventScroll)return s;if(s)return this.revealRange(s,n),s;t.backwards?i.start=i.end:i.end=i.start,this.selection.setRange(i)},this.findNext=function(e,t){this.find({skipCurrent:!0,backwards:!1},e,t)},this.findPrevious=function(e,t){this.find(e,{skipCurrent:!0,backwards:!0},t)},this.revealRange=function(e,t){this.session.unfold(e),this.selection.setSelectionRange(e);var n=this.renderer.scrollTop;this.renderer.scrollSelectionIntoView(e.start,e.end,.5),t!==!1&&this.renderer.animateScrolling(n)},this.undo=function(){this.session.getUndoManager().undo(this.session),this.renderer.scrollCursorIntoView(null,.5)},this.redo=function(){this.session.getUndoManager().redo(this.session),this.renderer.scrollCursorIntoView(null,.5)},this.destroy=function(){this.renderer.destroy(),this._signal("destroy",this),this.session&&this.session.destroy()},this.setAutoScrollEditorIntoView=function(e){if(!e)return;var t,n=this,r=!1;this.$scrollAnchor||(this.$scrollAnchor=document.createElement("div"));var i=this.$scrollAnchor;i.style.cssText="position:absolute",this.container.insertBefore(i,this.container.firstChild);var s=this.on("changeSelection",function(){r=!0}),o=this.renderer.on("beforeRender",function(){r&&(t=n.renderer.container.getBoundingClientRect())}),u=this.renderer.on("afterRender",function(){if(r&&t&&(n.isFocused()||n.searchBox&&n.searchBox.isFocused())){var e=n.renderer,s=e.$cursorLayer.$pixelPos,o=e.layerConfig,u=s.top-o.offset;s.top>=0&&u+t.top<0?r=!0:s.top<o.height&&s.top+t.top+o.lineHeight>window.innerHeight?r=!1:r=null,r!=null&&(i.style.top=u+"px",i.style.left=s.left+"px",i.style.height=o.lineHeight+"px",i.scrollIntoView(r)),r=t=null}});this.setAutoScrollEditorIntoView=function(e){if(e)return;delete this.setAutoScrollEditorIntoView,this.off("changeSelection",s),this.renderer.off("afterRender",u),this.renderer.off("beforeRender",o)}},this.$resetCursorStyle=function(){var e=this.$cursorStyle||"ace",t=this.renderer.$cursorLayer;if(!t)return;t.setSmoothBlinking(/smooth/.test(e)),t.isBlinking=!this.$readOnly&&e!="wide",i.setCssClass(t.element,"ace_slim-cursors",/slim/.test(e))}}.call(w.prototype),g.defineOptions(w.prototype,"editor",{selectionStyle:{set:function(e){this.onSelectionChange(),this._signal("changeSelectionStyle",{data:e})},initialValue:"line"},highlightActiveLine:{set:function(){this.$updateHighlightActiveLine()},initialValue:!0},highlightSelectedWord:{set:function(e){this.$onSelectionChange()},initialValue:!0},readOnly:{set:function(e){this.textInput.setReadOnly(e),this.$resetCursorStyle()},initialValue:!1},copyWithEmptySelection:{set:function(e){this.textInput.setCopyWithEmptySelection(e)},initialValue:!1},cursorStyle:{set:function(e){this.$resetCursorStyle()},values:["ace","slim","smooth","wide"],initialValue:"ace"},mergeUndoDeltas:{values:[!1,!0,"always"],initialValue:!0},behavioursEnabled:{initialValue:!0},wrapBehavioursEnabled:{initialValue:!0},autoScrollEditorIntoView:{set:function(e){this.setAutoScrollEditorIntoView(e)}},keyboardHandler:{set:function(e){this.setKeyboardHandler(e)},get:function(){return this.$keybindingId},handlesSet:!0},value:{set:function(e){this.session.setValue(e)},get:function(){return this.getValue()},handlesSet:!0,hidden:!0},session:{set:function(e){this.setSession(e)},get:function(){return this.session},handlesSet:!0,hidden:!0},hScrollBarAlwaysVisible:"renderer",vScrollBarAlwaysVisible:"renderer",highlightGutterLine:"renderer",animatedScroll:"renderer",showInvisibles:"renderer",showPrintMargin:"renderer",printMarginColumn:"renderer",printMargin:"renderer",fadeFoldWidgets:"renderer",showFoldWidgets:"renderer",showLineNumbers:"renderer",showGutter:"renderer",displayIndentGuides:"renderer",fontSize:"renderer",fontFamily:"renderer",maxLines:"renderer",minLines:"renderer",scrollPastEnd:"renderer",fixedWidthGutter:"renderer",theme:"renderer",scrollSpeed:"$mouseHandler",dragDelay:"$mouseHandler",dragEnabled:"$mouseHandler",focusTimeout:"$mouseHandler",tooltipFollowsMouse:"$mouseHandler",firstLineNumber:"session",overwrite:"session",newLineMode:"session",useWorker:"session",useSoftTabs:"session",navigateWithinSoftTabs:"session",tabSize:"session",wrap:"session",indentedSoftWrap:"session",foldStyle:"session",mode:"session"}),t.Editor=w}),ace.define("ace/undomanager",["require","exports","module","ace/range"],function(e,t,n){"use strict";function i(e,t){for(var n=t;n--;){var r=e[n];if(r&&!r[0].ignore){while(n<t-1){var i=d(e[n],e[n+1]);e[n]=i[0],e[n+1]=i[1],n++}return!0}}}function a(e){var t=e.action=="insert",n=e.start,r=e.end,i=(r.row-n.row)*(t?1:-1),s=(r.column-n.column)*(t?1:-1);t&&(r=n);for(var o in this.marks){var a=this.marks[o],f=u(a,n);if(f<0)continue;if(f===0&&t){if(a.bias!=1){a.bias==-1;continue}f=1}var l=t?f:u(a,r);if(l>0){a.row+=i,a.column+=a.row==r.row?s:0;continue}!t&&l<=0&&(a.row=n.row,a.column=n.column,l===0&&(a.bias=1))}}function f(e){return{row:e.row,column:e.column}}function l(e){return{start:f(e.start),end:f(e.end),action:e.action,lines:e.lines.slice()}}function c(e){e=e||this;if(Array.isArray(e))return e.map(c).join("\n");var t="";e.action?(t=e.action=="insert"?"+":"-",t+="["+e.lines+"]"):e.value&&(Array.isArray(e.value)?t=e.value.map(h).join("\n"):t=h(e.value)),e.start&&(t+=h(e));if(e.id||e.rev)t+="	("+(e.id||e.rev)+")";return t}function h(e){return e.start.row+":"+e.start.column+"=>"+e.end.row+":"+e.end.column}function p(e,t){var n=e.action=="insert",r=t.action=="insert";if(n&&r)if(o(t.start,e.end)>=0)m(t,e,-1);else{if(!(o(t.start,e.start)<=0))return null;m(e,t,1)}else if(n&&!r)if(o(t.start,e.end)>=0)m(t,e,-1);else{if(!(o(t.end,e.start)<=0))return null;m(e,t,-1)}else if(!n&&r)if(o(t.start,e.start)>=0)m(t,e,1);else{if(!(o(t.start,e.start)<=0))return null;m(e,t,1)}else if(!n&&!r)if(o(t.start,e.start)>=0)m(t,e,1);else{if(!(o(t.end,e.start)<=0))return null;m(e,t,-1)}return[t,e]}function d(e,t){for(var n=e.length;n--;)for(var r=0;r<t.length;r++)if(!p(e[n],t[r])){while(n<e.length){while(r--)p(t[r],e[n]);r=t.length,n++}return[e,t]}return e.selectionBefore=t.selectionBefore=e.selectionAfter=t.selectionAfter=null,[t,e]}function v(e,t){var n=e.action=="insert",r=t.action=="insert";if(n&&r)o(e.start,t.start)<0?m(t,e,1):m(e,t,1);else if(n&&!r)o(e.start,t.end)>=0?m(e,t,-1):o(e.start,t.start)<=0?m(t,e,1):(m(e,s.fromPoints(t.start,e.start),-1),m(t,e,1));else if(!n&&r)o(t.start,e.end)>=0?m(t,e,-1):o(t.start,e.start)<=0?m(e,t,1):(m(t,s.fromPoints(e.start,t.start),-1),m(e,t,1));else if(!n&&!r)if(o(t.start,e.end)>=0)m(t,e,-1);else{if(!(o(t.end,e.start)<=0)){var i,u;return o(e.start,t.start)<0&&(i=e,e=y(e,t.start)),o(e.end,t.end)>0&&(u=y(e,t.end)),g(t.end,e.start,e.end,-1),u&&!i&&(e.lines=u.lines,e.start=u.start,e.end=u.end,u=e),[t,i,u].filter(Boolean)}m(e,t,-1)}return[t,e]}function m(e,t,n){g(e.start,t.start,t.end,n),g(e.end,t.start,t.end,n)}function g(e,t,n,r){e.row==(r==1?t:n).row&&(e.column+=r*(n.column-t.column)),e.row+=r*(n.row-t.row)}function y(e,t){var n=e.lines,r=e.end;e.end=f(t);var i=e.end.row-e.start.row,s=n.splice(i,n.length),o=i?t.column:t.column-e.start.column;n.push(s[0].substring(0,o)),s[0]=s[0].substr(o);var u={start:f(t),end:r,lines:s,action:e.action};return u}function b(e,t){t=l(t);for(var n=e.length;n--;){var r=e[n];for(var i=r.length;i-->0;){var s=r[i],o=v(s,t);t=o[0],o.length!=2&&(o[2]?(e.splice(i+1,1,o[1],o[2]),i++):o[1]||(e.splice(i,1),i--))}}return e}function w(e,t){for(var n=0;n<t.length;n++){var r=t[n];for(var i=0;i<r.length;i++)b(e,r[i])}}var r=function(){this.$maxRev=0,this.$fromUndo=!1,this.reset()};(function(){this.addSession=function(e){this.$session=e},this.add=function(e,t,n){if(this.$fromUndo)return;if(e==this.$lastDelta)return;if(t===!1||!this.lastDeltas)this.lastDeltas=[],this.$undoStack.push(this.lastDeltas),e.id=this.$rev=++this.$maxRev;if(e.action=="remove"||e.action=="insert")this.$lastDelta=e;this.lastDeltas.push(e)},this.addSelection=function(e,t){this.selections.push({value:e,rev:t||this.$rev})},this.startNewGroup=function(){return this.lastDeltas=null,this.$rev},this.markIgnored=function(e,t){t==null&&(t=this.$rev+1);var n=this.$undoStack;for(var r=n.length;r--;){var i=n[r][0];if(i.id<=e)break;i.id<t&&(i.ignore=!0)}this.lastDeltas=null},this.getSelection=function(e,t){var n=this.selections;for(var r=n.length;r--;){var i=n[r];if(i.rev<e)return t&&(i=n[r+1]),i}},this.getRevision=function(){return this.$rev},this.getDeltas=function(e,t){t==null&&(t=this.$rev+1);var n=this.$undoStack,r=null,i=0;for(var s=n.length;s--;){var o=n[s][0];o.id<t&&!r&&(r=s+1);if(o.id<=e){i=s+1;break}}return n.slice(i,r)},this.getChangedRanges=function(e,t){t==null&&(t=this.$rev+1)},this.getChangedLines=function(e,t){t==null&&(t=this.$rev+1)},this.undo=function(e,t){this.lastDeltas=null;var n=this.$undoStack;if(!i(n,n.length))return;e||(e=this.$session),this.$redoStackBaseRev!==this.$rev&&this.$redoStack.length&&(this.$redoStack=[]),this.$fromUndo=!0;var r=n.pop(),s=null;return r&&r.length&&(s=e.undoChanges(r,t),this.$redoStack.push(r),this.$syncRev()),this.$fromUndo=!1,s},this.redo=function(e,t){this.lastDeltas=null,e||(e=this.$session),this.$fromUndo=!0;if(this.$redoStackBaseRev!=this.$rev){var n=this.getDeltas(this.$redoStackBaseRev,this.$rev+1);w(this.$redoStack,n),this.$redoStackBaseRev=this.$rev,this.$redoStack.forEach(function(e){e[0].id=++this.$maxRev},this)}var r=this.$redoStack.pop(),i=null;return r&&(i=e.redoChanges(r,t),this.$undoStack.push(r),this.$syncRev()),this.$fromUndo=!1,i},this.$syncRev=function(){var e=this.$undoStack,t=e[e.length-1],n=t&&t[0].id||0;this.$redoStackBaseRev=n,this.$rev=n},this.reset=function(){this.lastDeltas=null,this.$lastDelta=null,this.$undoStack=[],this.$redoStack=[],this.$rev=0,this.mark=0,this.$redoStackBaseRev=this.$rev,this.selections=[]},this.canUndo=function(){return this.$undoStack.length>0},this.canRedo=function(){return this.$redoStack.length>0},this.bookmark=function(e){e==undefined&&(e=this.$rev),this.mark=e},this.isAtBookmark=function(){return this.$rev===this.mark},this.toJSON=function(){},this.fromJSON=function(){},this.hasUndo=this.canUndo,this.hasRedo=this.canRedo,this.isClean=this.isAtBookmark,this.markClean=this.bookmark}).call(r.prototype);var s=e("./range").Range,o=s.comparePoints,u=s.comparePoints;t.UndoManager=r}),ace.define("ace/layer/gutter",["require","exports","module","ace/lib/dom","ace/lib/oop","ace/lib/lang","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("../lib/dom"),i=e("../lib/oop"),s=e("../lib/lang"),o=e("../lib/event_emitter").EventEmitter,u=function(e){this.element=r.createElement("div"),this.element.className="ace_layer ace_gutter-layer",e.appendChild(this.element),this.setShowFoldWidgets(this.$showFoldWidgets),this.gutterWidth=0,this.$annotations=[],this.$updateAnnotations=this.$updateAnnotations.bind(this),this.$cells=[]};(function(){i.implement(this,o),this.setSession=function(e){this.session&&this.session.removeEventListener("change",this.$updateAnnotations),this.session=e,e&&e.on("change",this.$updateAnnotations)},this.addGutterDecoration=function(e,t){window.console&&console.warn&&console.warn("deprecated use session.addGutterDecoration"),this.session.addGutterDecoration(e,t)},this.removeGutterDecoration=function(e,t){window.console&&console.warn&&console.warn("deprecated use session.removeGutterDecoration"),this.session.removeGutterDecoration(e,t)},this.setAnnotations=function(e){this.$annotations=[];for(var t=0;t<e.length;t++){var n=e[t],r=n.row,i=this.$annotations[r];i||(i=this.$annotations[r]={text:[]});var o=n.text;o=o?s.escapeHTML(o):n.html||"",i.text.indexOf(o)===-1&&i.text.push(o);var u=n.type;u=="error"?i.className=" ace_error":u=="warning"&&i.className!=" ace_error"?i.className=" ace_warning":u=="info"&&!i.className&&(i.className=" ace_info")}},this.$updateAnnotations=function(e){if(!this.$annotations.length)return;var t=e.start.row,n=e.end.row-t;if(n!==0)if(e.action=="remove")this.$annotations.splice(t,n+1,null);else{var r=new Array(n+1);r.unshift(t,1),this.$annotations.splice.apply(this.$annotations,r)}},this.update=function(e){var t=this.session,n=e.firstRow,i=Math.min(e.lastRow+e.gutterOffset,t.getLength()-1),s=t.getNextFoldLine(n),o=s?s.start.row:Infinity,u=this.$showFoldWidgets&&t.foldWidgets,a=t.$breakpoints,f=t.$decorations,l=t.$firstLineNumber,c=0,h=t.gutterRenderer||this.$renderer,p=null,d=-1,v=n;for(;;){v>o&&(v=s.end.row+1,s=t.getNextFoldLine(v,s),o=s?s.start.row:Infinity);if(v>i){while(this.$cells.length>d+1)p=this.$cells.pop(),this.element.removeChild(p.element);break}p=this.$cells[++d],p||(p={element:null,textNode:null,foldWidget:null},p.element=r.createElement("div"),p.textNode=document.createTextNode(""),p.element.appendChild(p.textNode),this.element.appendChild(p.element),this.$cells[d]=p);var m="ace_gutter-cell ";a[v]&&(m+=a[v]),f[v]&&(m+=f[v]),this.$annotations[v]&&(m+=this.$annotations[v].className),p.element.className!=m&&(p.element.className=m);var g=t.getRowLength(v)*e.lineHeight+"px";g!=p.element.style.height&&(p.element.style.height=g);if(u){var y=u[v];y==null&&(y=u[v]=t.getFoldWidget(v))}if(y){p.foldWidget||(p.foldWidget=r.createElement("span"),p.element.appendChild(p.foldWidget));var m="ace_fold-widget ace_"+y;y=="start"&&v==o&&v<s.end.row?m+=" ace_closed":m+=" ace_open",p.foldWidget.className!=m&&(p.foldWidget.className=m);var g=e.lineHeight+"px";p.foldWidget.style.height!=g&&(p.foldWidget.style.height=g)}else p.foldWidget&&(p.element.removeChild(p.foldWidget),p.foldWidget=null);var b=c=h?h.getText(t,v):v+l;b!==p.textNode.data&&(p.textNode.data=b),v++}this.element.style.height=e.minHeight+"px";if(this.$fixedWidth||t.$useWrapMode)c=t.getLength()+l;var w=h?h.getWidth(t,c,e):c.toString().length*e.characterWidth,E=this.$padding||this.$computePadding();w+=E.left+E.right,w!==this.gutterWidth&&!isNaN(w)&&(this.gutterWidth=w,this.element.style.width=Math.ceil(this.gutterWidth)+"px",this._emit("changeGutterWidth",w))},this.$fixedWidth=!1,this.$showLineNumbers=!0,this.$renderer="",this.setShowLineNumbers=function(e){this.$renderer=!e&&{getWidth:function(){return""},getText:function(){return""}}},this.getShowLineNumbers=function(){return this.$showLineNumbers},this.$showFoldWidgets=!0,this.setShowFoldWidgets=function(e){e?r.addCssClass(this.element,"ace_folding-enabled"):r.removeCssClass(this.element,"ace_folding-enabled"),this.$showFoldWidgets=e,this.$padding=null},this.getShowFoldWidgets=function(){return this.$showFoldWidgets},this.$computePadding=function(){if(!this.element.firstChild)return{left:0,right:0};var e=r.computedStyle(this.element.firstChild);return this.$padding={},this.$padding.left=parseInt(e.paddingLeft)+1||0,this.$padding.right=parseInt(e.paddingRight)||0,this.$padding},this.getRegion=function(e){var t=this.$padding||this.$computePadding(),n=this.element.getBoundingClientRect();if(e.x<t.left+n.left)return"markers";if(this.$showFoldWidgets&&e.x>n.right-t.right)return"foldWidgets"}}).call(u.prototype),t.Gutter=u}),ace.define("ace/layer/marker",["require","exports","module","ace/range","ace/lib/dom"],function(e,t,n){"use strict";var r=e("../range").Range,i=e("../lib/dom"),s=function(e){this.element=i.createElement("div"),this.element.className="ace_layer ace_marker-layer",e.appendChild(this.element)};(function(){function e(e,t,n,r){return(e?1:0)|(t?2:0)|(n?4:0)|(r?8:0)}this.$padding=0,this.setPadding=function(e){this.$padding=e},this.setSession=function(e){this.session=e},this.setMarkers=function(e){this.markers=e},this.update=function(e){if(!e)return;this.config=e;var t=[];for(var n in this.markers){var r=this.markers[n];if(!r.range){r.update(t,this,this.session,e);continue}var i=r.range.clipRows(e.firstRow,e.lastRow);if(i.isEmpty())continue;i=i.toScreenRange(this.session);if(r.renderer){var s=this.$getTop(i.start.row,e),o=this.$padding+(this.session.$bidiHandler.isBidiRow(i.start.row)?this.session.$bidiHandler.getPosLeft(i.start.column):i.start.column*e.characterWidth);r.renderer(t,i,o,s,e)}else r.type=="fullLine"?this.drawFullLineMarker(t,i,r.clazz,e):r.type=="screenLine"?this.drawScreenLineMarker(t,i,r.clazz,e):i.isMultiLine()?r.type=="text"?this.drawTextMarker(t,i,r.clazz,e):this.drawMultiLineMarker(t,i,r.clazz,e):this.session.$bidiHandler.isBidiRow(i.start.row)?this.drawBidiSingleLineMarker(t,i,r.clazz+" ace_start"+" ace_br15",e):this.drawSingleLineMarker(t,i,r.clazz+" ace_start"+" ace_br15",e)}this.element.innerHTML=t.join("")},this.$getTop=function(e,t){return(e-t.firstRowScreen)*t.lineHeight},this.drawTextMarker=function(t,n,i,s,o){var u=this.session,a=n.start.row,f=n.end.row,l=a,c=0,h=0,p=u.getScreenLastRowColumn(l),d=null,v=new r(l,n.start.column,l,h);for(;l<=f;l++)v.start.row=v.end.row=l,v.start.column=l==a?n.start.column:u.getRowWrapIndent(l),v.end.column=p,c=h,h=p,p=l+1<f?u.getScreenLastRowColumn(l+1):l==f?0:n.end.column,d=i+(l==a?" ace_start":"")+" ace_br"+e(l==a||l==a+1&&n.start.column,c<h,h>p,l==f),this.session.$bidiHandler.isBidiRow(l)?this.drawBidiSingleLineMarker(t,v,d,s,l==f?0:1,o):this.drawSingleLineMarker(t,v,d,s,l==f?0:1,o)},this.drawMultiLineMarker=function(e,t,n,r,i){var s=this.$padding,o,u,a;i=i||"";if(this.session.$bidiHandler.isBidiRow(t.start.row)){var f=t.clone();f.end.row=f.start.row,f.end.column=this.session.getLine(f.start.row).length,this.drawBidiSingleLineMarker(e,f,n+" ace_br1 ace_start",r,null,i)}else o=r.lineHeight,u=this.$getTop(t.start.row,r),a=s+t.start.column*r.characterWidth,e.push("<div class='",n," ace_br1 ace_start' style='","height:",o,"px;","right:0;","top:",u,"px;","left:",a,"px;",i,"'></div>");if(this.session.$bidiHandler.isBidiRow(t.end.row)){var f=t.clone();f.start.row=f.end.row,f.start.column=0,this.drawBidiSingleLineMarker(e,f,n+" ace_br12",r,null,i)}else{var l=t.end.column*r.characterWidth;o=r.lineHeight,u=this.$getTop(t.end.row,r),e.push("<div class='",n," ace_br12' style='","height:",o,"px;","width:",l,"px;","top:",u,"px;","left:",s,"px;",i,"'></div>")}o=(t.end.row-t.start.row-1)*r.lineHeight;if(o<=0)return;u=this.$getTop(t.start.row+1,r);var c=(t.start.column?1:0)|(t.end.column?0:8);e.push("<div class='",n,c?" ace_br"+c:"","' style='","height:",o,"px;","right:0;","top:",u,"px;","left:",s,"px;",i,"'></div>")},this.drawSingleLineMarker=function(e,t,n,r,i,s){var o=r.lineHeight,u=(t.end.column+(i||0)-t.start.column)*r.characterWidth,a=this.$getTop(t.start.row,r),f=this.$padding+t.start.column*r.characterWidth;e.push("<div class='",n,"' style='","height:",o,"px;","width:",u,"px;","top:",a,"px;","left:",f,"px;",s||"","'></div>")},this.drawBidiSingleLineMarker=function(e,t,n,r,i,s){var o=r.lineHeight,u=this.$getTop(t.start.row,r),a=this.$padding,f=this.session.$bidiHandler.getSelections(t.start.column,t.end.column);f.forEach(function(t){e.push("<div class='",n,"' style='","height:",o,"px;","width:",t.width+(i||0),"px;","top:",u,"px;","left:",a+t.left,"px;",s||"","'></div>")})},this.drawFullLineMarker=function(e,t,n,r,i){var s=this.$getTop(t.start.row,r),o=r.lineHeight;t.start.row!=t.end.row&&(o+=this.$getTop(t.end.row,r)-s),e.push("<div class='",n,"' style='","height:",o,"px;","top:",s,"px;","left:0;right:0;",i||"","'></div>")},this.drawScreenLineMarker=function(e,t,n,r,i){var s=this.$getTop(t.start.row,r),o=r.lineHeight;e.push("<div class='",n,"' style='","height:",o,"px;","top:",s,"px;","left:0;right:0;",i||"","'></div>")}}).call(s.prototype),t.Marker=s}),ace.define("ace/layer/text",["require","exports","module","ace/lib/oop","ace/lib/dom","ace/lib/lang","ace/lib/useragent","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("../lib/oop"),i=e("../lib/dom"),s=e("../lib/lang"),o=e("../lib/useragent"),u=e("../lib/event_emitter").EventEmitter,a=function(e){this.element=i.createElement("div"),this.element.className="ace_layer ace_text-layer",e.appendChild(this.element),this.$updateEolChar=this.$updateEolChar.bind(this)};(function(){r.implement(this,u),this.EOF_CHAR="\u00b6",this.EOL_CHAR_LF="\u00ac",this.EOL_CHAR_CRLF="\u00a4",this.EOL_CHAR=this.EOL_CHAR_LF,this.TAB_CHAR="\u2014",this.SPACE_CHAR="\u00b7",this.$padding=0,this.MAX_LINE_LENGTH=1e4,this.$updateEolChar=function(){var e=this.session.doc.getNewLineCharacter()=="\n"?this.EOL_CHAR_LF:this.EOL_CHAR_CRLF;if(this.EOL_CHAR!=e)return this.EOL_CHAR=e,!0},this.setPadding=function(e){this.$padding=e,this.element.style.padding="0 "+e+"px"},this.getLineHeight=function(){return this.$fontMetrics.$characterSize.height||0},this.getCharacterWidth=function(){return this.$fontMetrics.$characterSize.width||0},this.$setFontMetrics=function(e){this.$fontMetrics=e,this.$fontMetrics.on("changeCharacterSize",function(e){this._signal("changeCharacterSize",e)}.bind(this)),this.$pollSizeChanges()},this.checkForSizeChanges=function(){this.$fontMetrics.checkForSizeChanges()},this.$pollSizeChanges=function(){return this.$pollSizeChangesTimer=this.$fontMetrics.$pollSizeChanges()},this.setSession=function(e){this.session=e,e&&this.$computeTabString()},this.showInvisibles=!1,this.setShowInvisibles=function(e){return this.showInvisibles==e?!1:(this.showInvisibles=e,this.$computeTabString(),!0)},this.displayIndentGuides=!0,this.setDisplayIndentGuides=function(e){return this.displayIndentGuides==e?!1:(this.displayIndentGuides=e,this.$computeTabString(),!0)},this.$tabStrings=[],this.onChangeTabSize=this.$computeTabString=function(){var e=this.session.getTabSize();this.tabSize=e;var t=this.$tabStrings=[0];for(var n=1;n<e+1;n++)this.showInvisibles?t.push("<span class='ace_invisible ace_invisible_tab'>"+s.stringRepeat(this.TAB_CHAR,n)+"</span>"):t.push(s.stringRepeat(" ",n));if(this.displayIndentGuides){this.$indentGuideRe=/\s\S| \t|\t |\s$/;var r="ace_indent-guide",i="",o="";if(this.showInvisibles){r+=" ace_invisible",i=" ace_invisible_space",o=" ace_invisible_tab";var u=s.stringRepeat(this.SPACE_CHAR,this.tabSize),a=s.stringRepeat(this.TAB_CHAR,this.tabSize)}else var u=s.stringRepeat(" ",this.tabSize),a=u;this.$tabStrings[" "]="<span class='"+r+i+"'>"+u+"</span>",this.$tabStrings["	"]="<span class='"+r+o+"'>"+a+"</span>"}},this.updateLines=function(e,t,n){(this.config.lastRow!=e.lastRow||this.config.firstRow!=e.firstRow)&&this.scrollLines(e),this.config=e;var r=Math.max(t,e.firstRow),i=Math.min(n,e.lastRow),s=this.element.childNodes,o=0;for(var u=e.firstRow;u<r;u++){var a=this.session.getFoldLine(u);if(a){if(a.containsRow(r)){r=a.start.row;break}u=a.end.row}o++}var u=r,a=this.session.getNextFoldLine(u),f=a?a.start.row:Infinity;for(;;){u>f&&(u=a.end.row+1,a=this.session.getNextFoldLine(u,a),f=a?a.start.row:Infinity);if(u>i)break;var l=s[o++];if(l){var c=[];this.$renderLine(c,u,!this.$useLineGroups(),u==f?a:!1),l.style.height=e.lineHeight*this.session.getRowLength(u)+"px",l.innerHTML=c.join("")}u++}},this.scrollLines=function(e){var t=this.config;this.config=e;if(!t||t.lastRow<e.firstRow)return this.update(e);if(e.lastRow<t.firstRow)return this.update(e);var n=this.element;if(t.firstRow<e.firstRow)for(var r=this.session.getFoldedRowCount(t.firstRow,e.firstRow-1);r>0;r--)n.removeChild(n.firstChild);if(t.lastRow>e.lastRow)for(var r=this.session.getFoldedRowCount(e.lastRow+1,t.lastRow);r>0;r--)n.removeChild(n.lastChild);if(e.firstRow<t.firstRow){var i=this.$renderLinesFragment(e,e.firstRow,t.firstRow-1);n.firstChild?n.insertBefore(i,n.firstChild):n.appendChild(i)}if(e.lastRow>t.lastRow){var i=this.$renderLinesFragment(e,t.lastRow+1,e.lastRow);n.appendChild(i)}},this.$renderLinesFragment=function(e,t,n){var r=this.element.ownerDocument.createDocumentFragment(),s=t,o=this.session.getNextFoldLine(s),u=o?o.start.row:Infinity;for(;;){s>u&&(s=o.end.row+1,o=this.session.getNextFoldLine(s,o),u=o?o.start.row:Infinity);if(s>n)break;var a=i.createElement("div"),f=[];this.$renderLine(f,s,!1,s==u?o:!1),a.innerHTML=f.join("");if(this.$useLineGroups())a.className="ace_line_group",r.appendChild(a),a.style.height=e.lineHeight*this.session.getRowLength(s)+"px";else while(a.firstChild)r.appendChild(a.firstChild);s++}return r},this.update=function(e){this.config=e;var t=[],n=e.firstRow,r=e.lastRow,i=n,s=this.session.getNextFoldLine(i),o=s?s.start.row:Infinity;for(;;){i>o&&(i=s.end.row+1,s=this.session.getNextFoldLine(i,s),o=s?s.start.row:Infinity);if(i>r)break;this.$useLineGroups()&&t.push("<div class='ace_line_group' style='height:",e.lineHeight*this.session.getRowLength(i),"px'>"),this.$renderLine(t,i,!1,i==o?s:!1),this.$useLineGroups()&&t.push("</div>"),i++}this.element.innerHTML=t.join("")},this.$textToken={text:!0,rparen:!0,lparen:!0},this.$renderToken=function(e,t,n,r){var i=this,o=/\t|&|<|>|( +)|([\x00-\x1f\x80-\xa0\xad\u1680\u180E\u2000-\u200f\u2028\u2029\u202F\u205F\u3000\uFEFF\uFFF9-\uFFFC])|[\u1100-\u115F\u11A3-\u11A7\u11FA-\u11FF\u2329-\u232A\u2E80-\u2E99\u2E9B-\u2EF3\u2F00-\u2FD5\u2FF0-\u2FFB\u3000-\u303E\u3041-\u3096\u3099-\u30FF\u3105-\u312D\u3131-\u318E\u3190-\u31BA\u31C0-\u31E3\u31F0-\u321E\u3220-\u3247\u3250-\u32FE\u3300-\u4DBF\u4E00-\uA48C\uA490-\uA4C6\uA960-\uA97C\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFAFF\uFE10-\uFE19\uFE30-\uFE52\uFE54-\uFE66\uFE68-\uFE6B\uFF01-\uFF60\uFFE0-\uFFE6]|[\uD800-\uDBFF][\uDC00-\uDFFF]/g,u=function(e,n,r,o,u){if(n)return i.showInvisibles?"<span class='ace_invisible ace_invisible_space'>"+s.stringRepeat(i.SPACE_CHAR,e.length)+"</span>":e;if(e=="&")return"&#38;";if(e=="<")return"&#60;";if(e==">")return"&#62;";if(e=="	"){var a=i.session.getScreenTabSize(t+o);return t+=a-1,i.$tabStrings[a]}if(e=="\u3000"){var f=i.showInvisibles?"ace_cjk ace_invisible ace_invisible_space":"ace_cjk",l=i.showInvisibles?i.SPACE_CHAR:"";return t+=1,"<span class='"+f+"' style='width:"+i.config.characterWidth*2+"px'>"+l+"</span>"}return r?"<span class='ace_invisible ace_invisible_space ace_invalid'>"+i.SPACE_CHAR+"</span>":(t+=1,"<span class='ace_cjk' style='width:"+i.config.characterWidth*2+"px'>"+e+"</span>")},a=r.replace(o,u);if(!this.$textToken[n.type]){var f="ace_"+n.type.replace(/\./g," ace_"),l="";n.type=="fold"&&(l=" style='width:"+n.value.length*this.config.characterWidth+"px;' "),e.push("<span class='",f,"'",l,">",a,"</span>")}else e.push(a);return t+r.length},this.renderIndentGuide=function(e,t,n){var r=t.search(this.$indentGuideRe);return r<=0||r>=n?t:t[0]==" "?(r-=r%this.tabSize,e.push(s.stringRepeat(this.$tabStrings[" "],r/this.tabSize)),t.substr(r)):t[0]=="	"?(e.push(s.stringRepeat(this.$tabStrings["	"],r)),t.substr(r)):t},this.$renderWrappedLine=function(e,t,n,r){var i=0,o=0,u=n[0],a=0;for(var f=0;f<t.length;f++){var l=t[f],c=l.value;if(f==0&&this.displayIndentGuides){i=c.length,c=this.renderIndentGuide(e,c,u);if(!c)continue;i-=c.length}if(i+c.length<u)a=this.$renderToken(e,a,l,c),i+=c.length;else{while(i+c.length>=u)a=this.$renderToken(e,a,l,c.substring(0,u-i)),c=c.substring(u-i),i=u,r||e.push("</div>","<div class='ace_line' style='height:",this.config.lineHeight,"px'>"),e.push(s.stringRepeat("\u00a0",n.indent)),o++,a=0,u=n[o]||Number.MAX_VALUE;c.length!=0&&(i+=c.length,a=this.$renderToken(e,a,l,c))}}},this.$renderSimpleLine=function(e,t){var n=0,r=t[0],i=r.value;this.displayIndentGuides&&(i=this.renderIndentGuide(e,i)),i&&(n=this.$renderToken(e,n,r,i));for(var s=1;s<t.length;s++){r=t[s],i=r.value;if(n+i.length>this.MAX_LINE_LENGTH)return this.$renderOverflowMessage(e,n,r,i);n=this.$renderToken(e,n,r,i)}},this.$renderOverflowMessage=function(e,t,n,r){this.$renderToken(e,t,n,r.slice(0,this.MAX_LINE_LENGTH-t)),e.push("<span style='position:absolute;right:0' class='ace_inline_button ace_keyword ace_toggle_wrap'>&lt;click to see more...&gt;</span>")},this.$renderLine=function(e,t,n,r){!r&&r!=0&&(r=this.session.getFoldLine(t));if(r)var i=this.$getFoldLineTokens(t,r);else var i=this.session.getTokens(t);n||e.push("<div class='ace_line' style='height:",this.config.lineHeight*(this.$useLineGroups()?1:this.session.getRowLength(t)),"px'>");if(i.length){var s=this.session.getRowSplitData(t);s&&s.length?this.$renderWrappedLine(e,i,s,n):this.$renderSimpleLine(e,i)}this.showInvisibles&&(r&&(t=r.end.row),e.push("<span class='ace_invisible ace_invisible_eol'>",t==this.session.getLength()-1?this.EOF_CHAR:this.EOL_CHAR,"</span>")),n||e.push("</div>")},this.$getFoldLineTokens=function(e,t){function i(e,t,n){var i=0,s=0;while(s+e[i].value.length<t){s+=e[i].value.length,i++;if(i==e.length)return}if(s!=t){var o=e[i].value.substring(t-s);o.length>n-t&&(o=o.substring(0,n-t)),r.push({type:e[i].type,value:o}),s=t+o.length,i+=1}while(s<n&&i<e.length){var o=e[i].value;o.length+s>n?r.push({type:e[i].type,value:o.substring(0,n-s)}):r.push(e[i]),s+=o.length,i+=1}}var n=this.session,r=[],s=n.getTokens(e);return t.walk(function(e,t,o,u,a){e!=null?r.push({type:"fold",value:e}):(a&&(s=n.getTokens(t)),s.length&&i(s,u,o))},t.end.row,this.session.getLine(t.end.row).length),r},this.$useLineGroups=function(){return this.session.getUseWrapMode()},this.destroy=function(){clearInterval(this.$pollSizeChangesTimer),this.$measureNode&&this.$measureNode.parentNode.removeChild(this.$measureNode),delete this.$measureNode}}).call(a.prototype),t.Text=a}),ace.define("ace/layer/cursor",["require","exports","module","ace/lib/dom"],function(e,t,n){"use strict";var r=e("../lib/dom"),i,s=function(e){this.element=r.createElement("div"),this.element.className="ace_layer ace_cursor-layer",e.appendChild(this.element),i===undefined&&(i=!("opacity"in this.element.style)),this.isVisible=!1,this.isBlinking=!0,this.blinkInterval=1e3,this.smoothBlinking=!1,this.cursors=[],this.cursor=this.addCursor(),r.addCssClass(this.element,"ace_hidden-cursors"),this.$updateCursors=(i?this.$updateVisibility:this.$updateOpacity).bind(this)};(function(){this.$updateVisibility=function(e){var t=this.cursors;for(var n=t.length;n--;)t[n].style.visibility=e?"":"hidden"},this.$updateOpacity=function(e){var t=this.cursors;for(var n=t.length;n--;)t[n].style.opacity=e?"":"0"},this.$padding=0,this.setPadding=function(e){this.$padding=e},this.setSession=function(e){this.session=e},this.setBlinking=function(e){e!=this.isBlinking&&(this.isBlinking=e,this.restartTimer())},this.setBlinkInterval=function(e){e!=this.blinkInterval&&(this.blinkInterval=e,this.restartTimer())},this.setSmoothBlinking=function(e){e!=this.smoothBlinking&&!i&&(this.smoothBlinking=e,r.setCssClass(this.element,"ace_smooth-blinking",e),this.$updateCursors(!0),this.$updateCursors=this.$updateOpacity.bind(this),this.restartTimer())},this.addCursor=function(){var e=r.createElement("div");return e.className="ace_cursor",this.element.appendChild(e),this.cursors.push(e),e},this.removeCursor=function(){if(this.cursors.length>1){var e=this.cursors.pop();return e.parentNode.removeChild(e),e}},this.hideCursor=function(){this.isVisible=!1,r.addCssClass(this.element,"ace_hidden-cursors"),this.restartTimer()},this.showCursor=function(){this.isVisible=!0,r.removeCssClass(this.element,"ace_hidden-cursors"),this.restartTimer()},this.restartTimer=function(){var e=this.$updateCursors;clearInterval(this.intervalId),clearTimeout(this.timeoutId),this.smoothBlinking&&r.removeCssClass(this.element,"ace_smooth-blinking"),e(!0);if(!this.isBlinking||!this.blinkInterval||!this.isVisible)return;this.smoothBlinking&&setTimeout(function(){r.addCssClass(this.element,"ace_smooth-blinking")}.bind(this));var t=function(){this.timeoutId=setTimeout(function(){e(!1)},.6*this.blinkInterval)}.bind(this);this.intervalId=setInterval(function(){e(!0),t()},this.blinkInterval),t()},this.getPixelPosition=function(e,t){if(!this.config||!this.session)return{left:0,top:0};e||(e=this.session.selection.getCursor());var n=this.session.documentToScreenPosition(e),r=this.$padding+(this.session.$bidiHandler.isBidiRow(n.row,e.row)?this.session.$bidiHandler.getPosLeft(n.column):n.column*this.config.characterWidth),i=(n.row-(t?this.config.firstRowScreen:0))*this.config.lineHeight;return{left:r,top:i}},this.update=function(e){this.config=e;var t=this.session.$selectionMarkers,n=0,r=0;if(t===undefined||t.length===0)t=[{cursor:null}];for(var n=0,i=t.length;n<i;n++){var s=this.getPixelPosition(t[n].cursor,!0);if((s.top>e.height+e.offset||s.top<0)&&n>1)continue;var o=(this.cursors[r++]||this.addCursor()).style;this.drawCursor?this.drawCursor(o,s,e,t[n],this.session):(o.left=s.left+"px",o.top=s.top+"px",o.width=e.characterWidth+"px",o.height=e.lineHeight+"px")}while(this.cursors.length>r)this.removeCursor();var u=this.session.getOverwrite();this.$setOverwrite(u),this.$pixelPos=s,this.restartTimer()},this.drawCursor=null,this.$setOverwrite=function(e){e!=this.overwrite&&(this.overwrite=e,e?r.addCssClass(this.element,"ace_overwrite-cursors"):r.removeCssClass(this.element,"ace_overwrite-cursors"))},this.destroy=function(){clearInterval(this.intervalId),clearTimeout(this.timeoutId)}}).call(s.prototype),t.Cursor=s}),ace.define("ace/scrollbar",["require","exports","module","ace/lib/oop","ace/lib/dom","ace/lib/event","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./lib/dom"),s=e("./lib/event"),o=e("./lib/event_emitter").EventEmitter,u=32768,a=function(e){this.element=i.createElement("div"),this.element.className="ace_scrollbar ace_scrollbar"+this.classSuffix,this.inner=i.createElement("div"),this.inner.className="ace_scrollbar-inner",this.element.appendChild(this.inner),e.appendChild(this.element),this.setVisible(!1),this.skipEvent=!1,s.addListener(this.element,"scroll",this.onScroll.bind(this)),s.addListener(this.element,"mousedown",s.preventDefault)};(function(){r.implement(this,o),this.setVisible=function(e){this.element.style.display=e?"":"none",this.isVisible=e,this.coeff=1}}).call(a.prototype);var f=function(e,t){a.call(this,e),this.scrollTop=0,this.scrollHeight=0,t.$scrollbarWidth=this.width=i.scrollbarWidth(e.ownerDocument),this.inner.style.width=this.element.style.width=(this.width||15)+5+"px",this.$minWidth=0};r.inherits(f,a),function(){this.classSuffix="-v",this.onScroll=function(){if(!this.skipEvent){this.scrollTop=this.element.scrollTop;if(this.coeff!=1){var e=this.element.clientHeight/this.scrollHeight;this.scrollTop=this.scrollTop*(1-e)/(this.coeff-e)}this._emit("scroll",{data:this.scrollTop})}this.skipEvent=!1},this.getWidth=function(){return Math.max(this.isVisible?this.width:0,this.$minWidth||0)},this.setHeight=function(e){this.element.style.height=e+"px"},this.setInnerHeight=this.setScrollHeight=function(e){this.scrollHeight=e,e>u?(this.coeff=u/e,e=u):this.coeff!=1&&(this.coeff=1),this.inner.style.height=e+"px"},this.setScrollTop=function(e){this.scrollTop!=e&&(this.skipEvent=!0,this.scrollTop=e,this.element.scrollTop=e*this.coeff)}}.call(f.prototype);var l=function(e,t){a.call(this,e),this.scrollLeft=0,this.height=t.$scrollbarWidth,this.inner.style.height=this.element.style.height=(this.height||15)+5+"px"};r.inherits(l,a),function(){this.classSuffix="-h",this.onScroll=function(){this.skipEvent||(this.scrollLeft=this.element.scrollLeft,this._emit("scroll",{data:this.scrollLeft})),this.skipEvent=!1},this.getHeight=function(){return this.isVisible?this.height:0},this.setWidth=function(e){this.element.style.width=e+"px"},this.setInnerWidth=function(e){this.inner.style.width=e+"px"},this.setScrollWidth=function(e){this.inner.style.width=e+"px"},this.setScrollLeft=function(e){this.scrollLeft!=e&&(this.skipEvent=!0,this.scrollLeft=this.element.scrollLeft=e)}}.call(l.prototype),t.ScrollBar=f,t.ScrollBarV=f,t.ScrollBarH=l,t.VScrollBar=f,t.HScrollBar=l}),ace.define("ace/renderloop",["require","exports","module","ace/lib/event"],function(e,t,n){"use strict";var r=e("./lib/event"),i=function(e,t){this.onRender=e,this.pending=!1,this.changes=0,this.window=t||window};(function(){this.schedule=function(e){this.changes=this.changes|e;if(!this.pending&&this.changes){this.pending=!0;var t=this;r.nextFrame(function(){t.pending=!1;var e;while(e=t.changes)t.changes=0,t.onRender(e)},this.window)}}}).call(i.prototype),t.RenderLoop=i}),ace.define("ace/layer/font_metrics",["require","exports","module","ace/lib/oop","ace/lib/dom","ace/lib/lang","ace/lib/useragent","ace/lib/event_emitter"],function(e,t,n){var r=e("../lib/oop"),i=e("../lib/dom"),s=e("../lib/lang"),o=e("../lib/useragent"),u=e("../lib/event_emitter").EventEmitter,a=256,f=typeof ResizeObserver=="function",l=t.FontMetrics=function(e){this.el=i.createElement("div"),this.$setMeasureNodeStyles(this.el.style,!0),this.$main=i.createElement("div"),this.$setMeasureNodeStyles(this.$main.style),this.$measureNode=i.createElement("div"),this.$setMeasureNodeStyles(this.$measureNode.style),this.el.appendChild(this.$main),this.el.appendChild(this.$measureNode),e.appendChild(this.el),this.$measureNode.innerHTML=s.stringRepeat("X",a),this.$characterSize={width:0,height:0},f?this.$addObserver():this.checkForSizeChanges()};(function(){r.implement(this,u),this.$characterSize={width:0,height:0},this.$setMeasureNodeStyles=function(e,t){e.width=e.height="auto",e.left=e.top="0px",e.visibility="hidden",e.position="absolute",e.whiteSpace="pre",o.isIE<8?e["font-family"]="inherit":e.font="inherit",e.overflow=t?"hidden":"visible"},this.checkForSizeChanges=function(e){e===undefined&&(e=this.$measureSizes());if(e&&(this.$characterSize.width!==e.width||this.$characterSize.height!==e.height)){this.$measureNode.style.fontWeight="bold";var t=this.$measureSizes();this.$measureNode.style.fontWeight="",this.$characterSize=e,this.charSizes=Object.create(null),this.allowBoldFonts=t&&t.width===e.width&&t.height===e.height,this._emit("changeCharacterSize",{data:e})}},this.$addObserver=function(){var e=this;this.$observer=new window.ResizeObserver(function(t){var n=t[0].contentRect;e.checkForSizeChanges({height:n.height,width:n.width/a})}),this.$observer.observe(this.$measureNode)},this.$pollSizeChanges=function(){if(this.$pollSizeChangesTimer||this.$observer)return this.$pollSizeChangesTimer;var e=this;return this.$pollSizeChangesTimer=setInterval(function(){e.checkForSizeChanges()},500)},this.setPolling=function(e){e?this.$pollSizeChanges():this.$pollSizeChangesTimer&&(clearInterval(this.$pollSizeChangesTimer),this.$pollSizeChangesTimer=0)},this.$measureSizes=function(e){var t={height:(e||this.$measureNode).clientHeight,width:(e||this.$measureNode).clientWidth/a};return t.width===0||t.height===0?null:t},this.$measureCharWidth=function(e){this.$main.innerHTML=s.stringRepeat(e,a);var t=this.$main.getBoundingClientRect();return t.width/a},this.getCharacterWidth=function(e){var t=this.charSizes[e];return t===undefined&&(t=this.charSizes[e]=this.$measureCharWidth(e)/this.$characterSize.width),t},this.destroy=function(){clearInterval(this.$pollSizeChangesTimer),this.el&&this.el.parentNode&&this.el.parentNode.removeChild(this.el)}}).call(l.prototype)}),ace.define("ace/virtual_renderer",["require","exports","module","ace/lib/oop","ace/lib/dom","ace/config","ace/lib/useragent","ace/layer/gutter","ace/layer/marker","ace/layer/text","ace/layer/cursor","ace/scrollbar","ace/scrollbar","ace/renderloop","ace/layer/font_metrics","ace/lib/event_emitter"],function(e,t,n){"use strict";var r=e("./lib/oop"),i=e("./lib/dom"),s=e("./config"),o=e("./lib/useragent"),u=e("./layer/gutter").Gutter,a=e("./layer/marker").Marker,f=e("./layer/text").Text,l=e("./layer/cursor").Cursor,c=e("./scrollbar").HScrollBar,h=e("./scrollbar").VScrollBar,p=e("./renderloop").RenderLoop,d=e("./layer/font_metrics").FontMetrics,v=e("./lib/event_emitter").EventEmitter,m='.ace_editor {position: relative;overflow: hidden;font: 12px/normal \'Monaco\', \'Menlo\', \'Ubuntu Mono\', \'Consolas\', \'source-code-pro\', monospace;direction: ltr;text-align: left;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);}.ace_scroller {position: absolute;overflow: hidden;top: 0;bottom: 0;background-color: inherit;-ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;user-select: none;cursor: text;}.ace_content {position: absolute;box-sizing: border-box;min-width: 100%;}.ace_dragging .ace_scroller:before{position: absolute;top: 0;left: 0;right: 0;bottom: 0;content: \'\';background: rgba(250, 250, 250, 0.01);z-index: 1000;}.ace_dragging.ace_dark .ace_scroller:before{background: rgba(0, 0, 0, 0.01);}.ace_selecting, .ace_selecting * {cursor: text !important;}.ace_gutter {position: absolute;overflow : hidden;width: auto;top: 0;bottom: 0;left: 0;cursor: default;z-index: 4;-ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;user-select: none;}.ace_gutter-active-line {position: absolute;left: 0;right: 0;}.ace_scroller.ace_scroll-left {box-shadow: 17px 0 16px -16px rgba(0, 0, 0, 0.4) inset;}.ace_gutter-cell {padding-left: 19px;padding-right: 6px;background-repeat: no-repeat;}.ace_gutter-cell.ace_error {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAABOFBMVEX/////////QRswFAb/Ui4wFAYwFAYwFAaWGAfDRymzOSH/PxswFAb/SiUwFAYwFAbUPRvjQiDllog5HhHdRybsTi3/Tyv9Tir+Syj/UC3////XurebMBIwFAb/RSHbPx/gUzfdwL3kzMivKBAwFAbbvbnhPx66NhowFAYwFAaZJg8wFAaxKBDZurf/RB6mMxb/SCMwFAYwFAbxQB3+RB4wFAb/Qhy4Oh+4QifbNRcwFAYwFAYwFAb/QRzdNhgwFAYwFAbav7v/Uy7oaE68MBK5LxLewr/r2NXewLswFAaxJw4wFAbkPRy2PyYwFAaxKhLm1tMwFAazPiQwFAaUGAb/QBrfOx3bvrv/VC/maE4wFAbRPBq6MRO8Qynew8Dp2tjfwb0wFAbx6eju5+by6uns4uH9/f36+vr/GkHjAAAAYnRSTlMAGt+64rnWu/bo8eAA4InH3+DwoN7j4eLi4xP99Nfg4+b+/u9B/eDs1MD1mO7+4PHg2MXa347g7vDizMLN4eG+Pv7i5evs/v79yu7S3/DV7/498Yv24eH+4ufQ3Ozu/v7+y13sRqwAAADLSURBVHjaZc/XDsFgGIBhtDrshlitmk2IrbHFqL2pvXf/+78DPokj7+Fz9qpU/9UXJIlhmPaTaQ6QPaz0mm+5gwkgovcV6GZzd5JtCQwgsxoHOvJO15kleRLAnMgHFIESUEPmawB9ngmelTtipwwfASilxOLyiV5UVUyVAfbG0cCPHig+GBkzAENHS0AstVF6bacZIOzgLmxsHbt2OecNgJC83JERmePUYq8ARGkJx6XtFsdddBQgZE2nPR6CICZhawjA4Fb/chv+399kfR+MMMDGOQAAAABJRU5ErkJggg==");background-repeat: no-repeat;background-position: 2px center;}.ace_gutter-cell.ace_warning {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAmVBMVEX///8AAAD///8AAAAAAABPSzb/5sAAAAB/blH/73z/ulkAAAAAAAD85pkAAAAAAAACAgP/vGz/rkDerGbGrV7/pkQICAf////e0IsAAAD/oED/qTvhrnUAAAD/yHD/njcAAADuv2r/nz//oTj/p064oGf/zHAAAAA9Nir/tFIAAAD/tlTiuWf/tkIAAACynXEAAAAAAAAtIRW7zBpBAAAAM3RSTlMAABR1m7RXO8Ln31Z36zT+neXe5OzooRDfn+TZ4p3h2hTf4t3k3ucyrN1K5+Xaks52Sfs9CXgrAAAAjklEQVR42o3PbQ+CIBQFYEwboPhSYgoYunIqqLn6/z8uYdH8Vmdnu9vz4WwXgN/xTPRD2+sgOcZjsge/whXZgUaYYvT8QnuJaUrjrHUQreGczuEafQCO/SJTufTbroWsPgsllVhq3wJEk2jUSzX3CUEDJC84707djRc5MTAQxoLgupWRwW6UB5fS++NV8AbOZgnsC7BpEAAAAABJRU5ErkJggg==");background-position: 2px center;}.ace_gutter-cell.ace_info {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAJ0Uk5TAAB2k804AAAAPklEQVQY02NgIB68QuO3tiLznjAwpKTgNyDbMegwisCHZUETUZV0ZqOquBpXj2rtnpSJT1AEnnRmL2OgGgAAIKkRQap2htgAAAAASUVORK5CYII=");background-position: 2px center;}.ace_dark .ace_gutter-cell.ace_info {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAJFBMVEUAAAChoaGAgIAqKiq+vr6tra1ZWVmUlJSbm5s8PDxubm56enrdgzg3AAAAAXRSTlMAQObYZgAAAClJREFUeNpjYMAPdsMYHegyJZFQBlsUlMFVCWUYKkAZMxZAGdxlDMQBAG+TBP4B6RyJAAAAAElFTkSuQmCC");}.ace_scrollbar {position: absolute;right: 0;bottom: 0;z-index: 6;}.ace_scrollbar-inner {position: absolute;cursor: text;left: 0;top: 0;}.ace_scrollbar-v{overflow-x: hidden;overflow-y: scroll;top: 0;}.ace_scrollbar-h {overflow-x: scroll;overflow-y: hidden;left: 0;}.ace_print-margin {position: absolute;height: 100%;}.ace_text-input {position: absolute;z-index: 0;width: 0.5em;height: 1em;opacity: 0;background: transparent;-moz-appearance: none;appearance: none;border: none;resize: none;outline: none;overflow: hidden;font: inherit;padding: 0 1px;margin: 0 -1px;text-indent: -1em;-ms-user-select: text;-moz-user-select: text;-webkit-user-select: text;user-select: text;white-space: pre!important;}.ace_text-input.ace_composition {background: inherit;color: inherit;z-index: 1000;opacity: 1;text-indent: 0;}[ace_nocontext=true] {transform: none!important;filter: none!important;perspective: none!important;clip-path: none!important;mask : none!important;contain: none!important;perspective: none!important;mix-blend-mode: initial!important;z-index: auto;}.ace_layer {z-index: 1;position: absolute;overflow: hidden;word-wrap: normal;white-space: pre;height: 100%;width: 100%;box-sizing: border-box;pointer-events: none;}.ace_gutter-layer {position: relative;width: auto;text-align: right;pointer-events: auto;}.ace_text-layer {font: inherit !important;}.ace_cjk {display: inline-block;text-align: center;}.ace_cursor-layer {z-index: 4;}.ace_cursor {z-index: 4;position: absolute;box-sizing: border-box;border-left: 2px solid;transform: translatez(0);}.ace_multiselect .ace_cursor {border-left-width: 1px;}.ace_slim-cursors .ace_cursor {border-left-width: 1px;}.ace_overwrite-cursors .ace_cursor {border-left-width: 0;border-bottom: 1px solid;}.ace_hidden-cursors .ace_cursor {opacity: 0.2;}.ace_smooth-blinking .ace_cursor {transition: opacity 0.18s;}.ace_marker-layer .ace_step, .ace_marker-layer .ace_stack {position: absolute;z-index: 3;}.ace_marker-layer .ace_selection {position: absolute;z-index: 5;}.ace_marker-layer .ace_bracket {position: absolute;z-index: 6;}.ace_marker-layer .ace_active-line {position: absolute;z-index: 2;}.ace_marker-layer .ace_selected-word {position: absolute;z-index: 4;box-sizing: border-box;}.ace_line .ace_fold {box-sizing: border-box;display: inline-block;height: 11px;margin-top: -2px;vertical-align: middle;background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAAJCAYAAADU6McMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJpJREFUeNpi/P//PwOlgAXGYGRklAVSokD8GmjwY1wasKljQpYACtpCFeADcHVQfQyMQAwzwAZI3wJKvCLkfKBaMSClBlR7BOQikCFGQEErIH0VqkabiGCAqwUadAzZJRxQr/0gwiXIal8zQQPnNVTgJ1TdawL0T5gBIP1MUJNhBv2HKoQHHjqNrA4WO4zY0glyNKLT2KIfIMAAQsdgGiXvgnYAAAAASUVORK5CYII="),url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAA3CAYAAADNNiA5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAACJJREFUeNpi+P//fxgTAwPDBxDxD078RSX+YeEyDFMCIMAAI3INmXiwf2YAAAAASUVORK5CYII=");background-repeat: no-repeat, repeat-x;background-position: center center, top left;color: transparent;border: 1px solid black;border-radius: 2px;cursor: pointer;pointer-events: auto;}.ace_dark .ace_fold {}.ace_fold:hover{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAAJCAYAAADU6McMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJpJREFUeNpi/P//PwOlgAXGYGRklAVSokD8GmjwY1wasKljQpYACtpCFeADcHVQfQyMQAwzwAZI3wJKvCLkfKBaMSClBlR7BOQikCFGQEErIH0VqkabiGCAqwUadAzZJRxQr/0gwiXIal8zQQPnNVTgJ1TdawL0T5gBIP1MUJNhBv2HKoQHHjqNrA4WO4zY0glyNKLT2KIfIMAAQsdgGiXvgnYAAAAASUVORK5CYII="),url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAA3CAYAAADNNiA5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAACBJREFUeNpi+P//fz4TAwPDZxDxD5X4i5fLMEwJgAADAEPVDbjNw87ZAAAAAElFTkSuQmCC");}.ace_tooltip {background-color: #FFF;background-image: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.1));border: 1px solid gray;border-radius: 1px;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);color: black;max-width: 100%;padding: 3px 4px;position: fixed;z-index: 999999;box-sizing: border-box;cursor: default;white-space: pre;word-wrap: break-word;line-height: normal;font-style: normal;font-weight: normal;letter-spacing: normal;pointer-events: none;}.ace_folding-enabled > .ace_gutter-cell {padding-right: 13px;}.ace_fold-widget {box-sizing: border-box;margin: 0 -12px 0 1px;display: none;width: 11px;vertical-align: top;background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAANElEQVR42mWKsQ0AMAzC8ixLlrzQjzmBiEjp0A6WwBCSPgKAXoLkqSot7nN3yMwR7pZ32NzpKkVoDBUxKAAAAABJRU5ErkJggg==");background-repeat: no-repeat;background-position: center;border-radius: 3px;border: 1px solid transparent;cursor: pointer;}.ace_folding-enabled .ace_fold-widget {display: inline-block;   }.ace_fold-widget.ace_end {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAANElEQVR42m3HwQkAMAhD0YzsRchFKI7sAikeWkrxwScEB0nh5e7KTPWimZki4tYfVbX+MNl4pyZXejUO1QAAAABJRU5ErkJggg==");}.ace_fold-widget.ace_closed {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAAGCAYAAAAG5SQMAAAAOUlEQVR42jXKwQkAMAgDwKwqKD4EwQ26sSOkVWjgIIHAzPiCgaqiqnJHZnKICBERHN194O5b9vbLuAVRL+l0YWnZAAAAAElFTkSuQmCCXA==");}.ace_fold-widget:hover {border: 1px solid rgba(0, 0, 0, 0.3);background-color: rgba(255, 255, 255, 0.2);box-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);}.ace_fold-widget:active {border: 1px solid rgba(0, 0, 0, 0.4);background-color: rgba(0, 0, 0, 0.05);box-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);}.ace_dark .ace_fold-widget {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHklEQVQIW2P4//8/AzoGEQ7oGCaLLAhWiSwB146BAQCSTPYocqT0AAAAAElFTkSuQmCC");}.ace_dark .ace_fold-widget.ace_end {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAH0lEQVQIW2P4//8/AxQ7wNjIAjDMgC4AxjCVKBirIAAF0kz2rlhxpAAAAABJRU5ErkJggg==");}.ace_dark .ace_fold-widget.ace_closed {background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAAFCAYAAACAcVaiAAAAHElEQVQIW2P4//+/AxAzgDADlOOAznHAKgPWAwARji8UIDTfQQAAAABJRU5ErkJggg==");}.ace_dark .ace_fold-widget:hover {box-shadow: 0 1px 1px rgba(255, 255, 255, 0.2);background-color: rgba(255, 255, 255, 0.1);}.ace_dark .ace_fold-widget:active {box-shadow: 0 1px 1px rgba(255, 255, 255, 0.2);}.ace_inline_button {border: 1px solid lightgray;display: inline-block;margin: -1px 8px;padding: 0 5px;pointer-events: auto;cursor: pointer;}.ace_inline_button:hover {border-color: gray;background: rgba(200,200,200,0.2);display: inline-block;pointer-events: auto;}.ace_fold-widget.ace_invalid {background-color: #FFB4B4;border-color: #DE5555;}.ace_fade-fold-widgets .ace_fold-widget {transition: opacity 0.4s ease 0.05s;opacity: 0;}.ace_fade-fold-widgets:hover .ace_fold-widget {transition: opacity 0.05s ease 0.05s;opacity:1;}.ace_underline {text-decoration: underline;}.ace_bold {font-weight: bold;}.ace_nobold .ace_bold {font-weight: normal;}.ace_italic {font-style: italic;}.ace_error-marker {background-color: rgba(255, 0, 0,0.2);position: absolute;z-index: 9;}.ace_highlight-marker {background-color: rgba(255, 255, 0,0.2);position: absolute;z-index: 8;}.ace_br1 {border-top-left-radius    : 3px;}.ace_br2 {border-top-right-radius   : 3px;}.ace_br3 {border-top-left-radius    : 3px; border-top-right-radius:    3px;}.ace_br4 {border-bottom-right-radius: 3px;}.ace_br5 {border-top-left-radius    : 3px; border-bottom-right-radius: 3px;}.ace_br6 {border-top-right-radius   : 3px; border-bottom-right-radius: 3px;}.ace_br7 {border-top-left-radius    : 3px; border-top-right-radius:    3px; border-bottom-right-radius: 3px;}.ace_br8 {border-bottom-left-radius : 3px;}.ace_br9 {border-top-left-radius    : 3px; border-bottom-left-radius:  3px;}.ace_br10{border-top-right-radius   : 3px; border-bottom-left-radius:  3px;}.ace_br11{border-top-left-radius    : 3px; border-top-right-radius:    3px; border-bottom-left-radius:  3px;}.ace_br12{border-bottom-right-radius: 3px; border-bottom-left-radius:  3px;}.ace_br13{border-top-left-radius    : 3px; border-bottom-right-radius: 3px; border-bottom-left-radius:  3px;}.ace_br14{border-top-right-radius   : 3px; border-bottom-right-radius: 3px; border-bottom-left-radius:  3px;}.ace_br15{border-top-left-radius    : 3px; border-top-right-radius:    3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px;}.ace_text-input-ios {position: absolute !important;top: -100000px !important;left: -100000px !important;}';i.importCssString(m,"ace_editor.css");var g=function(e,t){var n=this;this.container=e||i.createElement("div"),i.addCssClass(this.container,"ace_editor"),this.setTheme(t),this.$gutter=i.createElement("div"),this.$gutter.className="ace_gutter",this.container.appendChild(this.$gutter),this.$gutter.setAttribute("aria-hidden",!0),this.scroller=i.createElement("div"),this.scroller.className="ace_scroller",this.container.appendChild(this.scroller),this.content=i.createElement("div"),this.content.className="ace_content",this.scroller.appendChild(this.content),this.$gutterLayer=new u(this.$gutter),this.$gutterLayer.on("changeGutterWidth",this.onGutterResize.bind(this)),this.$markerBack=new a(this.content);var r=this.$textLayer=new f(this.content);this.canvas=r.element,this.$markerFront=new a(this.content),this.$cursorLayer=new l(this.content),this.$horizScroll=!1,this.$vScroll=!1,this.scrollBar=this.scrollBarV=new h(this.container,this),this.scrollBarH=new c(this.container,this),this.scrollBarV.addEventListener("scroll",function(e){n.$scrollAnimation||n.session.setScrollTop(e.data-n.scrollMargin.top)}),this.scrollBarH.addEventListener("scroll",function(e){n.$scrollAnimation||n.session.setScrollLeft(e.data-n.scrollMargin.left)}),this.scrollTop=0,this.scrollLeft=0,this.cursorPos={row:0,column:0},this.$fontMetrics=new d(this.container),this.$textLayer.$setFontMetrics(this.$fontMetrics),this.$textLayer.addEventListener("changeCharacterSize",function(e){n.updateCharacterSize(),n.onResize(!0,n.gutterWidth,n.$size.width,n.$size.height),n._signal("changeCharacterSize",e)}),this.$size={width:0,height:0,scrollerHeight:0,scrollerWidth:0,$dirty:!0},this.layerConfig={width:1,padding:0,firstRow:0,firstRowScreen:0,lastRow:0,lineHeight:0,characterWidth:0,minHeight:1,maxHeight:1,offset:0,height:1,gutterOffset:1},this.scrollMargin={left:0,right:0,top:0,bottom:0,v:0,h:0},this.$loop=new p(this.$renderChanges.bind(this),this.container.ownerDocument.defaultView),this.$loop.schedule(this.CHANGE_FULL),this.updateCharacterSize(),this.setPadding(4),s.resetOptions(this),s._emit("renderer",this)};(function(){this.CHANGE_CURSOR=1,this.CHANGE_MARKER=2,this.CHANGE_GUTTER=4,this.CHANGE_SCROLL=8,this.CHANGE_LINES=16,this.CHANGE_TEXT=32,this.CHANGE_SIZE=64,this.CHANGE_MARKER_BACK=128,this.CHANGE_MARKER_FRONT=256,this.CHANGE_FULL=512,this.CHANGE_H_SCROLL=1024,r.implement(this,v),this.updateCharacterSize=function(){this.$textLayer.allowBoldFonts!=this.$allowBoldFonts&&(this.$allowBoldFonts=this.$textLayer.allowBoldFonts,this.setStyle("ace_nobold",!this.$allowBoldFonts)),this.layerConfig.characterWidth=this.characterWidth=this.$textLayer.getCharacterWidth(),this.layerConfig.lineHeight=this.lineHeight=this.$textLayer.getLineHeight(),this.$updatePrintMargin()},this.setSession=function(e){this.session&&this.session.doc.off("changeNewLineMode",this.onChangeNewLineMode),this.session=e,e&&this.scrollMargin.top&&e.getScrollTop()<=0&&e.setScrollTop(-this.scrollMargin.top),this.$cursorLayer.setSession(e),this.$markerBack.setSession(e),this.$markerFront.setSession(e),this.$gutterLayer.setSession(e),this.$textLayer.setSession(e);if(!e)return;this.$loop.schedule(this.CHANGE_FULL),this.session.$setFontMetrics(this.$fontMetrics),this.scrollBarH.scrollLeft=this.scrollBarV.scrollTop=null,this.onChangeNewLineMode=this.onChangeNewLineMode.bind(this),this.onChangeNewLineMode(),this.session.doc.on("changeNewLineMode",this.onChangeNewLineMode)},this.updateLines=function(e,t,n){t===undefined&&(t=Infinity),this.$changedLines?(this.$changedLines.firstRow>e&&(this.$changedLines.firstRow=e),this.$changedLines.lastRow<t&&(this.$changedLines.lastRow=t)):this.$changedLines={firstRow:e,lastRow:t};if(this.$changedLines.lastRow<this.layerConfig.firstRow){if(!n)return;this.$changedLines.lastRow=this.layerConfig.lastRow}if(this.$changedLines.firstRow>this.layerConfig.lastRow)return;this.$loop.schedule(this.CHANGE_LINES)},this.onChangeNewLineMode=function(){this.$loop.schedule(this.CHANGE_TEXT),this.$textLayer.$updateEolChar(),this.session.$bidiHandler.setEolChar(this.$textLayer.EOL_CHAR)},this.onChangeTabSize=function(){this.$loop.schedule(this.CHANGE_TEXT|this.CHANGE_MARKER),this.$textLayer.onChangeTabSize()},this.updateText=function(){this.$loop.schedule(this.CHANGE_TEXT)},this.updateFull=function(e){e?this.$renderChanges(this.CHANGE_FULL,!0):this.$loop.schedule(this.CHANGE_FULL)},this.updateFontSize=function(){this.$textLayer.checkForSizeChanges()},this.$changes=0,this.$updateSizeAsync=function(){this.$loop.pending?this.$size.$dirty=!0:this.onResize()},this.onResize=function(e,t,n,r){if(this.resizing>2)return;this.resizing>0?this.resizing++:this.resizing=e?1:0;var i=this.container;r||(r=i.clientHeight||i.scrollHeight),n||(n=i.clientWidth||i.scrollWidth);var s=this.$updateCachedSize(e,t,n,r);if(!this.$size.scrollerHeight||!n&&!r)return this.resizing=0;e&&(this.$gutterLayer.$padding=null),e?this.$renderChanges(s|this.$changes,!0):this.$loop.schedule(s|this.$changes),this.resizing&&(this.resizing=0),this.scrollBarV.scrollLeft=this.scrollBarV.scrollTop=null},this.$updateCachedSize=function(e,t,n,r){r-=this.$extraHeight||0;var i=0,s=this.$size,o={width:s.width,height:s.height,scrollerHeight:s.scrollerHeight,scrollerWidth:s.scrollerWidth};r&&(e||s.height!=r)&&(s.height=r,i|=this.CHANGE_SIZE,s.scrollerHeight=s.height,this.$horizScroll&&(s.scrollerHeight-=this.scrollBarH.getHeight()),this.scrollBarV.element.style.bottom=this.scrollBarH.getHeight()+"px",i|=this.CHANGE_SCROLL);if(n&&(e||s.width!=n)){i|=this.CHANGE_SIZE,s.width=n,t==null&&(t=this.$showGutter?this.$gutter.offsetWidth:0),this.gutterWidth=t,this.scrollBarH.element.style.left=this.scroller.style.left=t+"px",s.scrollerWidth=Math.max(0,n-t-this.scrollBarV.getWidth()),this.scrollBarH.element.style.right=this.scroller.style.right=this.scrollBarV.getWidth()+"px",this.scroller.style.bottom=this.scrollBarH.getHeight()+"px";if(this.session&&this.session.getUseWrapMode()&&this.adjustWrapLimit()||e)i|=this.CHANGE_FULL}return s.$dirty=!n||!r,i&&this._signal("resize",o),i},this.onGutterResize=function(){var e=this.$showGutter?this.$gutter.offsetWidth:0;e!=this.gutterWidth&&(this.$changes|=this.$updateCachedSize(!0,e,this.$size.width,this.$size.height)),this.session.getUseWrapMode()&&this.adjustWrapLimit()?this.$loop.schedule(this.CHANGE_FULL):this.$size.$dirty?this.$loop.schedule(this.CHANGE_FULL):(this.$computeLayerConfig(),this.$loop.schedule(this.CHANGE_MARKER))},this.adjustWrapLimit=function(){var e=this.$size.scrollerWidth-this.$padding*2,t=Math.floor(e/this.characterWidth);return this.session.adjustWrapLimit(t,this.$showPrintMargin&&this.$printMarginColumn)},this.setAnimatedScroll=function(e){this.setOption("animatedScroll",e)},this.getAnimatedScroll=function(){return this.$animatedScroll},this.setShowInvisibles=function(e){this.setOption("showInvisibles",e),this.session.$bidiHandler.setShowInvisibles(e)},this.getShowInvisibles=function(){return this.getOption("showInvisibles")},this.getDisplayIndentGuides=function(){return this.getOption("displayIndentGuides")},this.setDisplayIndentGuides=function(e){this.setOption("displayIndentGuides",e)},this.setShowPrintMargin=function(e){this.setOption("showPrintMargin",e)},this.getShowPrintMargin=function(){return this.getOption("showPrintMargin")},this.setPrintMarginColumn=function(e){this.setOption("printMarginColumn",e)},this.getPrintMarginColumn=function(){return this.getOption("printMarginColumn")},this.getShowGutter=function(){return this.getOption("showGutter")},this.setShowGutter=function(e){return this.setOption("showGutter",e)},this.getFadeFoldWidgets=function(){return this.getOption("fadeFoldWidgets")},this.setFadeFoldWidgets=function(e){this.setOption("fadeFoldWidgets",e)},this.setHighlightGutterLine=function(e){this.setOption("highlightGutterLine",e)},this.getHighlightGutterLine=function(){return this.getOption("highlightGutterLine")},this.$updateGutterLineHighlight=function(){var e=this.$cursorLayer.$pixelPos,t=this.layerConfig.lineHeight;if(this.session.getUseWrapMode()){var n=this.session.selection.getCursor();n.column=0,e=this.$cursorLayer.getPixelPosition(n,!0),t*=this.session.getRowLength(n.row)}this.$gutterLineHighlight.style.top=e.top-this.layerConfig.offset+"px",this.$gutterLineHighlight.style.height=t+"px"},this.$updatePrintMargin=function(){if(!this.$showPrintMargin&&!this.$printMarginEl)return;if(!this.$printMarginEl){var e=i.createElement("div");e.className="ace_layer ace_print-margin-layer",this.$printMarginEl=i.createElement("div"),this.$printMarginEl.className="ace_print-margin",e.appendChild(this.$printMarginEl),this.content.insertBefore(e,this.content.firstChild)}var t=this.$printMarginEl.style;t.left=Math.round(this.characterWidth*this.$printMarginColumn+this.$padding)+"px",t.visibility=this.$showPrintMargin?"visible":"hidden",this.session&&this.session.$wrap==-1&&this.adjustWrapLimit()},this.getContainerElement=function(){return this.container},this.getMouseEventTarget=function(){return this.scroller},this.getTextAreaContainer=function(){return this.container},this.$moveTextAreaToCursor=function(){var e=this.textarea.style;if(!this.$keepTextAreaAtCursor){e.left="-100px";return}var t=this.layerConfig,n=this.$cursorLayer.$pixelPos.top,r=this.$cursorLayer.$pixelPos.left;n-=t.offset;var i=this.lineHeight;if(n<0||n>t.height-i){e.top=e.left="0";return}var s=this.characterWidth;if(this.$composition){var o=this.textarea.value.replace(/^\x01+/,"");s*=this.session.$getStringScreenWidth(o)[0]+2,i+=2}r-=this.scrollLeft,r>this.$size.scrollerWidth-s&&(r=this.$size.scrollerWidth-s),r+=this.gutterWidth,e.height=i+"px",e.width=s+"px",e.left=Math.min(r,this.$size.scrollerWidth-s)+"px",e.top=Math.min(n,this.$size.height-i)+"px"},this.getFirstVisibleRow=function(){return this.layerConfig.firstRow},this.getFirstFullyVisibleRow=function(){return this.layerConfig.firstRow+(this.layerConfig.offset===0?0:1)},this.getLastFullyVisibleRow=function(){var e=this.layerConfig,t=e.lastRow,n=this.session.documentToScreenRow(t,0)*e.lineHeight;return n-this.session.getScrollTop()>e.height-e.lineHeight?t-1:t},this.getLastVisibleRow=function(){return this.layerConfig.lastRow},this.$padding=null,this.setPadding=function(e){this.$padding=e,this.$textLayer.setPadding(e),this.$cursorLayer.setPadding(e),this.$markerFront.setPadding(e),this.$markerBack.setPadding(e),this.$loop.schedule(this.CHANGE_FULL),this.$updatePrintMargin()},this.setScrollMargin=function(e,t,n,r){var i=this.scrollMargin;i.top=e|0,i.bottom=t|0,i.right=r|0,i.left=n|0,i.v=i.top+i.bottom,i.h=i.left+i.right,i.top&&this.scrollTop<=0&&this.session&&this.session.setScrollTop(-i.top),this.updateFull()},this.getHScrollBarAlwaysVisible=function(){return this.$hScrollBarAlwaysVisible},this.setHScrollBarAlwaysVisible=function(e){this.setOption("hScrollBarAlwaysVisible",e)},this.getVScrollBarAlwaysVisible=function(){return this.$vScrollBarAlwaysVisible},this.setVScrollBarAlwaysVisible=function(e){this.setOption("vScrollBarAlwaysVisible",e)},this.$updateScrollBarV=function(){var e=this.layerConfig.maxHeight,t=this.$size.scrollerHeight;!this.$maxLines&&this.$scrollPastEnd&&(e-=(t-this.lineHeight)*this.$scrollPastEnd,this.scrollTop>e-t&&(e=this.scrollTop+t,this.scrollBarV.scrollTop=null)),this.scrollBarV.setScrollHeight(e+this.scrollMargin.v),this.scrollBarV.setScrollTop(this.scrollTop+this.scrollMargin.top)},this.$updateScrollBarH=function(){this.scrollBarH.setScrollWidth(this.layerConfig.width+2*this.$padding+this.scrollMargin.h),this.scrollBarH.setScrollLeft(this.scrollLeft+this.scrollMargin.left)},this.$frozen=!1,this.freeze=function(){this.$frozen=!0},this.unfreeze=function(){this.$frozen=!1},this.$renderChanges=function(e,t){this.$changes&&(e|=this.$changes,this.$changes=0);if(!this.session||!this.container.offsetWidth||this.$frozen||!e&&!t){this.$changes|=e;return}if(this.$size.$dirty)return this.$changes|=e,this.onResize(!0);this.lineHeight||this.$textLayer.checkForSizeChanges(),this._signal("beforeRender"),this.session&&this.session.$bidiHandler&&this.session.$bidiHandler.updateCharacterWidths(this.$fontMetrics);var n=this.layerConfig;if(e&this.CHANGE_FULL||e&this.CHANGE_SIZE||e&this.CHANGE_TEXT||e&this.CHANGE_LINES||e&this.CHANGE_SCROLL||e&this.CHANGE_H_SCROLL){e|=this.$computeLayerConfig();if(n.firstRow!=this.layerConfig.firstRow&&n.firstRowScreen==this.layerConfig.firstRowScreen){var r=this.scrollTop+(n.firstRow-this.layerConfig.firstRow)*this.lineHeight;r>0&&(this.scrollTop=r,e|=this.CHANGE_SCROLL,e|=this.$computeLayerConfig())}n=this.layerConfig,this.$updateScrollBarV(),e&this.CHANGE_H_SCROLL&&this.$updateScrollBarH(),this.$gutterLayer.element.style.marginTop=-n.offset+"px",this.content.style.marginTop=-n.offset+"px",this.content.style.width=n.width+2*this.$padding+"px",this.content.style.height=n.minHeight+"px"}e&this.CHANGE_H_SCROLL&&(this.content.style.marginLeft=-this.scrollLeft+"px",this.scroller.className=this.scrollLeft<=0?"ace_scroller":"ace_scroller ace_scroll-left");if(e&this.CHANGE_FULL){this.$textLayer.update(n),this.$showGutter&&this.$gutterLayer.update(n),this.$markerBack.update(n),this.$markerFront.update(n),this.$cursorLayer.update(n),this.$moveTextAreaToCursor(),this.$highlightGutterLine&&this.$updateGutterLineHighlight(),this._signal("afterRender");return}if(e&this.CHANGE_SCROLL){e&this.CHANGE_TEXT||e&this.CHANGE_LINES?this.$textLayer.update(n):this.$textLayer.scrollLines(n),this.$showGutter&&this.$gutterLayer.update(n),this.$markerBack.update(n),this.$markerFront.update(n),this.$cursorLayer.update(n),this.$highlightGutterLine&&this.$updateGutterLineHighlight(),this.$moveTextAreaToCursor(),this._signal("afterRender");return}e&this.CHANGE_TEXT?(this.$textLayer.update(n),this.$showGutter&&this.$gutterLayer.update(n)):e&this.CHANGE_LINES?(this.$updateLines()||e&this.CHANGE_GUTTER&&this.$showGutter)&&this.$gutterLayer.update(n):(e&this.CHANGE_TEXT||e&this.CHANGE_GUTTER)&&this.$showGutter&&this.$gutterLayer.update(n),e&this.CHANGE_CURSOR&&(this.$cursorLayer.update(n),this.$moveTextAreaToCursor(),this.$highlightGutterLine&&this.$updateGutterLineHighlight()),e&(this.CHANGE_MARKER|this.CHANGE_MARKER_FRONT)&&this.$markerFront.update(n),e&(this.CHANGE_MARKER|this.CHANGE_MARKER_BACK)&&this.$markerBack.update(n),this._signal("afterRender")},this.$autosize=function(){var e=this.session.getScreenLength()*this.lineHeight,t=this.$maxLines*this.lineHeight,n=Math.min(t,Math.max((this.$minLines||1)*this.lineHeight,e))+this.scrollMargin.v+(this.$extraHeight||0);this.$horizScroll&&(n+=this.scrollBarH.getHeight()),this.$maxPixelHeight&&n>this.$maxPixelHeight&&(n=this.$maxPixelHeight);var r=e>t;if(n!=this.desiredHeight||this.$size.height!=this.desiredHeight||r!=this.$vScroll){r!=this.$vScroll&&(this.$vScroll=r,this.scrollBarV.setVisible(r));var i=this.container.clientWidth;this.container.style.height=n+"px",this.$updateCachedSize(!0,this.$gutterWidth,i,n),this.desiredHeight=n,this._signal("autosize")}},this.$computeLayerConfig=function(){var e=this.session,t=this.$size,n=t.height<=2*this.lineHeight,r=this.session.getScreenLength(),i=r*this.lineHeight,s=this.$getLongestLine(),o=!n&&(this.$hScrollBarAlwaysVisible||t.scrollerWidth-s-2*this.$padding<0),u=this.$horizScroll!==o;u&&(this.$horizScroll=o,this.scrollBarH.setVisible(o));var a=this.$vScroll;this.$maxLines&&this.lineHeight>1&&this.$autosize();var f=this.scrollTop%this.lineHeight,l=t.scrollerHeight+this.lineHeight,c=!this.$maxLines&&this.$scrollPastEnd?(t.scrollerHeight-this.lineHeight)*this.$scrollPastEnd:0;i+=c;var h=this.scrollMargin;this.session.setScrollTop(Math.max(-h.top,Math.min(this.scrollTop,i-t.scrollerHeight+h.bottom))),this.session.setScrollLeft(Math.max(-h.left,Math.min(this.scrollLeft,s+2*this.$padding-t.scrollerWidth+h.right)));var p=!n&&(this.$vScrollBarAlwaysVisible||t.scrollerHeight-i+c<0||this.scrollTop>h.top),d=a!==p;d&&(this.$vScroll=p,this.scrollBarV.setVisible(p));var v=Math.ceil(l/this.lineHeight)-1,m=Math.max(0,Math.round((this.scrollTop-f)/this.lineHeight)),g=m+v,y,b,w=this.lineHeight;m=e.screenToDocumentRow(m,0);var E=e.getFoldLine(m);E&&(m=E.start.row),y=e.documentToScreenRow(m,0),b=e.getRowLength(m)*w,g=Math.min(e.screenToDocumentRow(g,0),e.getLength()-1),l=t.scrollerHeight+e.getRowLength(g)*w+b,f=this.scrollTop-y*w;var S=0;if(this.layerConfig.width!=s||u)S=this.CHANGE_H_SCROLL;if(u||d)S=this.$updateCachedSize(!0,this.gutterWidth,t.width,t.height),this._signal("scrollbarVisibilityChanged"),d&&(s=this.$getLongestLine());return this.layerConfig={width:s,padding:this.$padding,firstRow:m,firstRowScreen:y,lastRow:g,lineHeight:w,characterWidth:this.characterWidth,minHeight:l,maxHeight:i,offset:f,gutterOffset:w?Math.max(0,Math.ceil((f+t.height-t.scrollerHeight)/w)):0,height:this.$size.scrollerHeight},S},this.$updateLines=function(){if(!this.$changedLines)return;var e=this.$changedLines.firstRow,t=this.$changedLines.lastRow;this.$changedLines=null;var n=this.layerConfig;if(e>n.lastRow+1)return;if(t<n.firstRow)return;if(t===Infinity){this.$showGutter&&this.$gutterLayer.update(n),this.$textLayer.update(n);return}return this.$textLayer.updateLines(n,e,t),!0},this.$getLongestLine=function(){var e=this.session.getScreenWidth();return this.showInvisibles&&!this.session.$useWrapMode&&(e+=1),this.$textLayer&&e>this.$textLayer.MAX_LINE_LENGTH&&(e=this.$textLayer.MAX_LINE_LENGTH+30),Math.max(this.$size.scrollerWidth-2*this.$padding,Math.round(e*this.characterWidth))},this.updateFrontMarkers=function(){this.$markerFront.setMarkers(this.session.getMarkers(!0)),this.$loop.schedule(this.CHANGE_MARKER_FRONT)},this.updateBackMarkers=function(){this.$markerBack.setMarkers(this.session.getMarkers()),this.$loop.schedule(this.CHANGE_MARKER_BACK)},this.addGutterDecoration=function(e,t){this.$gutterLayer.addGutterDecoration(e,t)},this.removeGutterDecoration=function(e,t){this.$gutterLayer.removeGutterDecoration(e,t)},this.updateBreakpoints=function(e){this.$loop.schedule(this.CHANGE_GUTTER)},this.setAnnotations=function(e){this.$gutterLayer.setAnnotations(e),this.$loop.schedule(this.CHANGE_GUTTER)},this.updateCursor=function(){this.$loop.schedule(this.CHANGE_CURSOR)},this.hideCursor=function(){this.$cursorLayer.hideCursor()},this.showCursor=function(){this.$cursorLayer.showCursor()},this.scrollSelectionIntoView=function(e,t,n){this.scrollCursorIntoView(e,n),this.scrollCursorIntoView(t,n)},this.scrollCursorIntoView=function(e,t,n){if(this.$size.scrollerHeight===0)return;var r=this.$cursorLayer.getPixelPosition(e),i=r.left,s=r.top,o=n&&n.top||0,u=n&&n.bottom||0,a=this.$scrollAnimation?this.session.getScrollTop():this.scrollTop;a+o>s?(t&&a+o>s+this.lineHeight&&(s-=t*this.$size.scrollerHeight),s===0&&(s=-this.scrollMargin.top),this.session.setScrollTop(s)):a+this.$size.scrollerHeight-u<s+this.lineHeight&&(t&&a+this.$size.scrollerHeight-u<s-this.lineHeight&&(s+=t*this.$size.scrollerHeight),this.session.setScrollTop(s+this.lineHeight-this.$size.scrollerHeight));var f=this.scrollLeft;f>i?(i<this.$padding+2*this.layerConfig.characterWidth&&(i=-this.scrollMargin.left),this.session.setScrollLeft(i)):f+this.$size.scrollerWidth<i+this.characterWidth?this.session.setScrollLeft(Math.round(i+this.characterWidth-this.$size.scrollerWidth)):f<=this.$padding&&i-f<this.characterWidth&&this.session.setScrollLeft(0)},this.getScrollTop=function(){return this.session.getScrollTop()},this.getScrollLeft=function(){return this.session.getScrollLeft()},this.getScrollTopRow=function(){return this.scrollTop/this.lineHeight},this.getScrollBottomRow=function(){return Math.max(0,Math.floor((this.scrollTop+this.$size.scrollerHeight)/this.lineHeight)-1)},this.scrollToRow=function(e){this.session.setScrollTop(e*this.lineHeight)},this.alignCursor=function(e,t){typeof e=="number"&&(e={row:e,column:0});var n=this.$cursorLayer.getPixelPosition(e),r=this.$size.scrollerHeight-this.lineHeight,i=n.top-r*(t||0);return this.session.setScrollTop(i),i},this.STEPS=8,this.$calcSteps=function(e,t){var n=0,r=this.STEPS,i=[],s=function(e,t,n){return n*(Math.pow(e-1,3)+1)+t};for(n=0;n<r;++n)i.push(s(n/this.STEPS,e,t-e));return i},this.scrollToLine=function(e,t,n,r){var i=this.$cursorLayer.getPixelPosition({row:e,column:0}),s=i.top;t&&(s-=this.$size.scrollerHeight/2);var o=this.scrollTop;this.session.setScrollTop(s),n!==!1&&this.animateScrolling(o,r)},this.animateScrolling=function(e,t){var n=this.scrollTop;if(!this.$animatedScroll)return;var r=this;if(e==n)return;if(this.$scrollAnimation){var i=this.$scrollAnimation.steps;if(i.length){e=i[0];if(e==n)return}}var s=r.$calcSteps(e,n);this.$scrollAnimation={from:e,to:n,steps:s},clearInterval(this.$timer),r.session.setScrollTop(s.shift()),r.session.$scrollTop=n,this.$timer=setInterval(function(){s.length?(r.session.setScrollTop(s.shift()),r.session.$scrollTop=n):n!=null?(r.session.$scrollTop=-1,r.session.setScrollTop(n),n=null):(r.$timer=clearInterval(r.$timer),r.$scrollAnimation=null,t&&t())},10)},this.scrollToY=function(e){this.scrollTop!==e&&(this.$loop.schedule(this.CHANGE_SCROLL),this.scrollTop=e)},this.scrollToX=function(e){this.scrollLeft!==e&&(this.scrollLeft=e),this.$loop.schedule(this.CHANGE_H_SCROLL)},this.scrollTo=function(e,t){this.session.setScrollTop(t),this.session.setScrollLeft(t)},this.scrollBy=function(e,t){t&&this.session.setScrollTop(this.session.getScrollTop()+t),e&&this.session.setScrollLeft(this.session.getScrollLeft()+e)},this.isScrollableBy=function(e,t){if(t<0&&this.session.getScrollTop()>=1-this.scrollMargin.top)return!0;if(t>0&&this.session.getScrollTop()+this.$size.scrollerHeight-this.layerConfig.maxHeight<-1+this.scrollMargin.bottom)return!0;if(e<0&&this.session.getScrollLeft()>=1-this.scrollMargin.left)return!0;if(e>0&&this.session.getScrollLeft()+this.$size.scrollerWidth-this.layerConfig.width<-1+this.scrollMargin.right)return!0},this.pixelToScreenCoordinates=function(e,t){var n=this.scroller.getBoundingClientRect(),r=e+this.scrollLeft-n.left-this.$padding,i=r/this.characterWidth,s=Math.floor((t+this.scrollTop-n.top)/this.lineHeight),o=Math.round(i);return{row:s,column:o,side:i-o>0?1:-1,offsetX:r}},this.screenToTextCoordinates=function(e,t){var n=this.scroller.getBoundingClientRect(),r=e+this.scrollLeft-n.left-this.$padding,i=Math.round(r/this.characterWidth),s=(t+this.scrollTop-n.top)/this.lineHeight;return this.session.screenToDocumentPosition(s,Math.max(i,0),r)},this.textToScreenCoordinates=function(e,t){var n=this.scroller.getBoundingClientRect(),r=this.session.documentToScreenPosition(e,t),i=this.$padding+(this.session.$bidiHandler.isBidiRow(r.row,e)?this.session.$bidiHandler.getPosLeft(r.column):Math.round(r.column*this.characterWidth)),s=r.row*this.lineHeight;return{pageX:n.left+i-this.scrollLeft,pageY:n.top+s-this.scrollTop}},this.visualizeFocus=function(){i.addCssClass(this.container,"ace_focus")},this.visualizeBlur=function(){i.removeCssClass(this.container,"ace_focus")},this.showComposition=function(e){this.$composition||(this.$composition={keepTextAreaAtCursor:this.$keepTextAreaAtCursor,cssText:this.textarea.style.cssText}),this.$keepTextAreaAtCursor=!0,i.addCssClass(this.textarea,"ace_composition"),this.textarea.style.cssText="",this.$moveTextAreaToCursor()},this.setCompositionText=function(e){this.$moveTextAreaToCursor()},this.hideComposition=function(){if(!this.$composition)return;i.removeCssClass(this.textarea,"ace_composition"),this.$keepTextAreaAtCursor=this.$composition.keepTextAreaAtCursor,this.textarea.style.cssText=this.$composition.cssText,this.$composition=null},this.setTheme=function(e,t){function o(r){if(n.$themeId!=e)return t&&t();if(!r||!r.cssClass)throw new Error("couldn't load module "+e+" or it didn't call define");r.$id&&(n.$themeId=r.$id),i.importCssString(r.cssText,r.cssClass,n.container.ownerDocument),n.theme&&i.removeCssClass(n.container,n.theme.cssClass);var s="padding"in r?r.padding:"padding"in(n.theme||{})?4:n.$padding;n.$padding&&s!=n.$padding&&n.setPadding(s),n.$theme=r.cssClass,n.theme=r,i.addCssClass(n.container,r.cssClass),i.setCssClass(n.container,"ace_dark",r.isDark),n.$size&&(n.$size.width=0,n.$updateSizeAsync()),n._dispatchEvent("themeLoaded",{theme:r}),t&&t()}var n=this;this.$themeId=e,n._dispatchEvent("themeChange",{theme:e});if(!e||typeof e=="string"){var r=e||this.$options.theme.initialValue;s.loadModule(["theme",r],o)}else o(e)},this.getTheme=function(){return this.$themeId},this.setStyle=function(e,t){i.setCssClass(this.container,e,t!==!1)},this.unsetStyle=function(e){i.removeCssClass(this.container,e)},this.setCursorStyle=function(e){this.scroller.style.cursor!=e&&(this.scroller.style.cursor=e)},this.setMouseCursor=function(e){this.scroller.style.cursor=e},this.destroy=function(){this.$textLayer.destroy(),this.$cursorLayer.destroy()}}).call(g.prototype),s.defineOptions(g.prototype,"renderer",{animatedScroll:{initialValue:!1},showInvisibles:{set:function(e){this.$textLayer.setShowInvisibles(e)&&this.$loop.schedule(this.CHANGE_TEXT)},initialValue:!1},showPrintMargin:{set:function(){this.$updatePrintMargin()},initialValue:!0},printMarginColumn:{set:function(){this.$updatePrintMargin()},initialValue:80},printMargin:{set:function(e){typeof e=="number"&&(this.$printMarginColumn=e),this.$showPrintMargin=!!e,this.$updatePrintMargin()},get:function(){return this.$showPrintMargin&&this.$printMarginColumn}},showGutter:{set:function(e){this.$gutter.style.display=e?"block":"none",this.$loop.schedule(this.CHANGE_FULL),this.onGutterResize()},initialValue:!0},fadeFoldWidgets:{set:function(e){i.setCssClass(this.$gutter,"ace_fade-fold-widgets",e)},initialValue:!1},showFoldWidgets:{set:function(e){this.$gutterLayer.setShowFoldWidgets(e)},initialValue:!0},showLineNumbers:{set:function(e){this.$gutterLayer.setShowLineNumbers(e),this.$loop.schedule(this.CHANGE_GUTTER)},initialValue:!0},displayIndentGuides:{set:function(e){this.$textLayer.setDisplayIndentGuides(e)&&this.$loop.schedule(this.CHANGE_TEXT)},initialValue:!0},highlightGutterLine:{set:function(e){if(!this.$gutterLineHighlight){this.$gutterLineHighlight=i.createElement("div"),this.$gutterLineHighlight.className="ace_gutter-active-line",this.$gutter.appendChild(this.$gutterLineHighlight);return}this.$gutterLineHighlight.style.display=e?"":"none",this.$cursorLayer.$pixelPos&&this.$updateGutterLineHighlight()},initialValue:!1,value:!0},hScrollBarAlwaysVisible:{set:function(e){(!this.$hScrollBarAlwaysVisible||!this.$horizScroll)&&this.$loop.schedule(this.CHANGE_SCROLL)},initialValue:!1},vScrollBarAlwaysVisible:{set:function(e){(!this.$vScrollBarAlwaysVisible||!this.$vScroll)&&this.$loop.schedule(this.CHANGE_SCROLL)},initialValue:!1},fontSize:{set:function(e){typeof e=="number"&&(e+="px"),this.container.style.fontSize=e,this.updateFontSize()},initialValue:12},fontFamily:{set:function(e){this.container.style.fontFamily=e,this.updateFontSize()}},maxLines:{set:function(e){this.updateFull()}},minLines:{set:function(e){this.updateFull()}},maxPixelHeight:{set:function(e){this.updateFull()},initialValue:0},scrollPastEnd:{set:function(e){e=+e||0;if(this.$scrollPastEnd==e)return;this.$scrollPastEnd=e,this.$loop.schedule(this.CHANGE_SCROLL)},initialValue:0,handlesSet:!0},fixedWidthGutter:{set:function(e){this.$gutterLayer.$fixedWidth=!!e,this.$loop.schedule(this.CHANGE_GUTTER)}},theme:{set:function(e){this.setTheme(e)},get:function(){return this.$themeId||this.theme},initialValue:"./theme/textmate",handlesSet:!0}}),t.VirtualRenderer=g}),ace.define("ace/worker/worker_client",["require","exports","module","ace/lib/oop","ace/lib/net","ace/lib/event_emitter","ace/config"],function(e,t,n){"use strict";function u(e){var t="importScripts('"+i.qualifyURL(e)+"');";try{return new Blob([t],{type:"application/javascript"})}catch(n){var r=window.BlobBuilder||window.WebKitBlobBuilder||window.MozBlobBuilder,s=new r;return s.append(t),s.getBlob("application/javascript")}}function a(e){if(typeof Worker=="undefined")return{postMessage:function(){},terminate:function(){}};var t=u(e),n=window.URL||window.webkitURL,r=n.createObjectURL(t);return new Worker(r)}var r=e("../lib/oop"),i=e("../lib/net"),s=e("../lib/event_emitter").EventEmitter,o=e("../config"),f=function(t,n,r,i,s){this.$sendDeltaQueue=this.$sendDeltaQueue.bind(this),this.changeListener=this.changeListener.bind(this),this.onMessage=this.onMessage.bind(this),e.nameToUrl&&!e.toUrl&&(e.toUrl=e.nameToUrl);if(o.get("packaged")||!e.toUrl)i=i||o.moduleUrl(n,"worker");else{var u=this.$normalizePath;i=i||u(e.toUrl("ace/worker/worker.js",null,"_"));var f={};t.forEach(function(t){f[t]=u(e.toUrl(t,null,"_").replace(/(\.js)?(\?.*)?$/,""))})}this.$worker=a(i),s&&this.send("importScripts",s),this.$worker.postMessage({init:!0,tlns:f,module:n,classname:r}),this.callbackId=1,this.callbacks={},this.$worker.onmessage=this.onMessage};(function(){r.implement(this,s),this.onMessage=function(e){var t=e.data;switch(t.type){case"event":this._signal(t.name,{data:t.data});break;case"call":var n=this.callbacks[t.id];n&&(n(t.data),delete this.callbacks[t.id]);break;case"error":this.reportError(t.data);break;case"log":window.console&&console.log&&console.log.apply(console,t.data)}},this.reportError=function(e){window.console&&console.error&&console.error(e)},this.$normalizePath=function(e){return i.qualifyURL(e)},this.terminate=function(){this._signal("terminate",{}),this.deltaQueue=null,this.$worker.terminate(),this.$worker=null,this.$doc&&this.$doc.off("change",this.changeListener),this.$doc=null},this.send=function(e,t){this.$worker.postMessage({command:e,args:t})},this.call=function(e,t,n){if(n){var r=this.callbackId++;this.callbacks[r]=n,t.push(r)}this.send(e,t)},this.emit=function(e,t){try{this.$worker.postMessage({event:e,data:{data:t.data}})}catch(n){console.error(n.stack)}},this.attachToDocument=function(e){this.$doc&&this.terminate(),this.$doc=e,this.call("setValue",[e.getValue()]),e.on("change",this.changeListener)},this.changeListener=function(e){this.deltaQueue||(this.deltaQueue=[],setTimeout(this.$sendDeltaQueue,0)),e.action=="insert"?this.deltaQueue.push(e.start,e.lines):this.deltaQueue.push(e.start,e.end)},this.$sendDeltaQueue=function(){var e=this.deltaQueue;if(!e)return;this.deltaQueue=null,e.length>50&&e.length>this.$doc.getLength()>>1?this.call("setValue",[this.$doc.getValue()]):this.emit("change",{data:e})}}).call(f.prototype);var l=function(e,t,n){this.$sendDeltaQueue=this.$sendDeltaQueue.bind(this),this.changeListener=this.changeListener.bind(this),this.callbackId=1,this.callbacks={},this.messageBuffer=[];var r=null,i=!1,u=Object.create(s),a=this;this.$worker={},this.$worker.terminate=function(){},this.$worker.postMessage=function(e){a.messageBuffer.push(e),r&&(i?setTimeout(f):f())},this.setEmitSync=function(e){i=e};var f=function(){var e=a.messageBuffer.shift();e.command?r[e.command].apply(r,e.args):e.event&&u._signal(e.event,e.data)};u.postMessage=function(e){a.onMessage({data:e})},u.callback=function(e,t){this.postMessage({type:"call",id:t,data:e})},u.emit=function(e,t){this.postMessage({type:"event",name:e,data:t})},o.loadModule(["worker",t],function(e){r=new e[n](u);while(a.messageBuffer.length)f()})};l.prototype=f.prototype,t.UIWorkerClient=l,t.WorkerClient=f,t.createWorker=a}),ace.define("ace/placeholder",["require","exports","module","ace/range","ace/lib/event_emitter","ace/lib/oop"],function(e,t,n){"use strict";var r=e("./range").Range,i=e("./lib/event_emitter").EventEmitter,s=e("./lib/oop"),o=function(e,t,n,r,i,s){var o=this;this.length=t,this.session=e,this.doc=e.getDocument(),this.mainClass=i,this.othersClass=s,this.$onUpdate=this.onUpdate.bind(this),this.doc.on("change",this.$onUpdate),this.$others=r,this.$onCursorChange=function(){setTimeout(function(){o.onCursorChange()})},this.$pos=n;var u=e.getUndoManager().$undoStack||e.getUndoManager().$undostack||{length:-1};this.$undoStackDepth=u.length,this.setup(),e.selection.on("changeCursor",this.$onCursorChange)};(function(){s.implement(this,i),this.setup=function(){var e=this,t=this.doc,n=this.session;this.selectionBefore=n.selection.toJSON(),n.selection.inMultiSelectMode&&n.selection.toSingleRange(),this.pos=t.createAnchor(this.$pos.row,this.$pos.column);var i=this.pos;i.$insertRight=!0,i.detach(),i.markerId=n.addMarker(new r(i.row,i.column,i.row,i.column+this.length),this.mainClass,null,!1),this.others=[],this.$others.forEach(function(n){var r=t.createAnchor(n.row,n.column);r.$insertRight=!0,r.detach(),e.others.push(r)}),n.setUndoSelect(!1)},this.showOtherMarkers=function(){if(this.othersActive)return;var e=this.session,t=this;this.othersActive=!0,this.others.forEach(function(n){n.markerId=e.addMarker(new r(n.row,n.column,n.row,n.column+t.length),t.othersClass,null,!1)})},this.hideOtherMarkers=function(){if(!this.othersActive)return;this.othersActive=!1;for(var e=0;e<this.others.length;e++)this.session.removeMarker(this.others[e].markerId)},this.onUpdate=function(e){if(this.$updating)return this.updateAnchors(e);var t=e;if(t.start.row!==t.end.row)return;if(t.start.row!==this.pos.row)return;this.$updating=!0;var n=e.action==="insert"?t.end.column-t.start.column:t.start.column-t.end.column,i=t.start.column>=this.pos.column&&t.start.column<=this.pos.column+this.length+1,s=t.start.column-this.pos.column;this.updateAnchors(e),i&&(this.length+=n);if(i&&!this.session.$fromUndo)if(e.action==="insert")for(var o=this.others.length-1;o>=0;o--){var u=this.others[o],a={row:u.row,column:u.column+s};this.doc.insertMergedLines(a,e.lines)}else if(e.action==="remove")for(var o=this.others.length-1;o>=0;o--){var u=this.others[o],a={row:u.row,column:u.column+s};this.doc.remove(new r(a.row,a.column,a.row,a.column-n))}this.$updating=!1,this.updateMarkers()},this.updateAnchors=function(e){this.pos.onChange(e);for(var t=this.others.length;t--;)this.others[t].onChange(e);this.updateMarkers()},this.updateMarkers=function(){if(this.$updating)return;var e=this,t=this.session,n=function(n,i){t.removeMarker(n.markerId),n.markerId=t.addMarker(new r(n.row,n.column,n.row,n.column+e.length),i,null,!1)};n(this.pos,this.mainClass);for(var i=this.others.length;i--;)n(this.others[i],this.othersClass)},this.onCursorChange=function(e){if(this.$updating||!this.session)return;var t=this.session.selection.getCursor();t.row===this.pos.row&&t.column>=this.pos.column&&t.column<=this.pos.column+this.length?(this.showOtherMarkers(),this._emit("cursorEnter",e)):(this.hideOtherMarkers(),this._emit("cursorLeave",e))},this.detach=function(){this.session.removeMarker(this.pos&&this.pos.markerId),this.hideOtherMarkers(),this.doc.removeEventListener("change",this.$onUpdate),this.session.selection.removeEventListener("changeCursor",this.$onCursorChange),this.session.setUndoSelect(!0),this.session=null},this.cancel=function(){if(this.$undoStackDepth===-1)return;var e=this.session.getUndoManager(),t=(e.$undoStack||e.$undostack).length-this.$undoStackDepth;for(var n=0;n<t;n++)e.undo(this.session,!0);this.selectionBefore&&this.session.selection.fromJSON(this.selectionBefore)}}).call(o.prototype),t.PlaceHolder=o}),ace.define("ace/mouse/multi_select_handler",["require","exports","module","ace/lib/event","ace/lib/useragent"],function(e,t,n){function s(e,t){return e.row==t.row&&e.column==t.column}function o(e){var t=e.domEvent,n=t.altKey,o=t.shiftKey,u=t.ctrlKey,a=e.getAccelKey(),f=e.getButton();u&&i.isMac&&(f=t.button);if(e.editor.inMultiSelectMode&&f==2){e.editor.textInput.onContextMenu(e.domEvent);return}if(!u&&!n&&!a){f===0&&e.editor.inMultiSelectMode&&e.editor.exitMultiSelectMode();return}if(f!==0)return;var l=e.editor,c=l.selection,h=l.inMultiSelectMode,p=e.getDocumentPosition(),d=c.getCursor(),v=e.inSelection()||c.isEmpty()&&s(p,d),m=e.x,g=e.y,y=function(e){m=e.clientX,g=e.clientY},b=l.session,w=l.renderer.pixelToScreenCoordinates(m,g),E=w,S;if(l.$mouseHandler.$enableJumpToDef)u&&n||a&&n?S=o?"block":"add":n&&l.$blockSelectEnabled&&(S="block");else if(a&&!n){S="add";if(!h&&o)return}else n&&l.$blockSelectEnabled&&(S="block");S&&i.isMac&&t.ctrlKey&&l.$mouseHandler.cancelContextMenu();if(S=="add"){if(!h&&v)return;if(!h){var x=c.toOrientedRange();l.addSelectionMarker(x)}var T=c.rangeList.rangeAtPoint(p);l.inVirtualSelectionMode=!0,o&&(T=null,x=c.ranges[0]||x,l.removeSelectionMarker(x)),l.once("mouseup",function(){var e=c.toOrientedRange();T&&e.isEmpty()&&s(T.cursor,e.cursor)?c.substractPoint(e.cursor):(o?c.substractPoint(x.cursor):x&&(l.removeSelectionMarker(x),c.addRange(x)),c.addRange(e)),l.inVirtualSelectionMode=!1})}else if(S=="block"){e.stop(),l.inVirtualSelectionMode=!0;var N,C=[],k=function(){var e=l.renderer.pixelToScreenCoordinates(m,g),t=b.screenToDocumentPosition(e.row,e.column,e.offsetX);if(s(E,e)&&s(t,c.lead))return;E=e,l.selection.moveToPosition(t),l.renderer.scrollCursorIntoView(),l.removeSelectionMarkers(C),C=c.rectangularRangeBlock(E,w),l.$mouseHandler.$clickSelection&&C.length==1&&C[0].isEmpty()&&(C[0]=l.$mouseHandler.$clickSelection.clone()),C.forEach(l.addSelectionMarker,l),l.updateSelectionMarkers()};h&&!a?c.toSingleRange():!h&&a&&(N=c.toOrientedRange(),l.addSelectionMarker(N)),o?w=b.documentToScreenPosition(c.lead):c.moveToPosition(p),E={row:-1,column:-1};var L=function(e){clearInterval(O),l.removeSelectionMarkers(C),C.length||(C=[c.toOrientedRange()]),N&&(l.removeSelectionMarker(N),c.toSingleRange(N));for(var t=0;t<C.length;t++)c.addRange(C[t]);l.inVirtualSelectionMode=!1,l.$mouseHandler.$clickSelection=null},A=k;r.capture(l.container,y,L);var O=setInterval(function(){A()},20);return e.preventDefault()}}var r=e("../lib/event"),i=e("../lib/useragent");t.onMouseDown=o}),ace.define("ace/commands/multi_select_commands",["require","exports","module","ace/keyboard/hash_handler"],function(e,t,n){t.defaultCommands=[{name:"addCursorAbove",exec:function(e){e.selectMoreLines(-1)},bindKey:{win:"Ctrl-Alt-Up",mac:"Ctrl-Alt-Up"},scrollIntoView:"cursor",readOnly:!0},{name:"addCursorBelow",exec:function(e){e.selectMoreLines(1)},bindKey:{win:"Ctrl-Alt-Down",mac:"Ctrl-Alt-Down"},scrollIntoView:"cursor",readOnly:!0},{name:"addCursorAboveSkipCurrent",exec:function(e){e.selectMoreLines(-1,!0)},bindKey:{win:"Ctrl-Alt-Shift-Up",mac:"Ctrl-Alt-Shift-Up"},scrollIntoView:"cursor",readOnly:!0},{name:"addCursorBelowSkipCurrent",exec:function(e){e.selectMoreLines(1,!0)},bindKey:{win:"Ctrl-Alt-Shift-Down",mac:"Ctrl-Alt-Shift-Down"},scrollIntoView:"cursor",readOnly:!0},{name:"selectMoreBefore",exec:function(e){e.selectMore(-1)},bindKey:{win:"Ctrl-Alt-Left",mac:"Ctrl-Alt-Left"},scrollIntoView:"cursor",readOnly:!0},{name:"selectMoreAfter",exec:function(e){e.selectMore(1)},bindKey:{win:"Ctrl-Alt-Right",mac:"Ctrl-Alt-Right"},scrollIntoView:"cursor",readOnly:!0},{name:"selectNextBefore",exec:function(e){e.selectMore(-1,!0)},bindKey:{win:"Ctrl-Alt-Shift-Left",mac:"Ctrl-Alt-Shift-Left"},scrollIntoView:"cursor",readOnly:!0},{name:"selectNextAfter",exec:function(e){e.selectMore(1,!0)},bindKey:{win:"Ctrl-Alt-Shift-Right",mac:"Ctrl-Alt-Shift-Right"},scrollIntoView:"cursor",readOnly:!0},{name:"splitIntoLines",exec:function(e){e.multiSelect.splitIntoLines()},bindKey:{win:"Ctrl-Alt-L",mac:"Ctrl-Alt-L"},readOnly:!0},{name:"alignCursors",exec:function(e){e.alignCursors()},bindKey:{win:"Ctrl-Alt-A",mac:"Ctrl-Alt-A"},scrollIntoView:"cursor"},{name:"findAll",exec:function(e){e.findAll()},bindKey:{win:"Ctrl-Alt-K",mac:"Ctrl-Alt-G"},scrollIntoView:"cursor",readOnly:!0}],t.multiSelectCommands=[{name:"singleSelection",bindKey:"esc",exec:function(e){e.exitMultiSelectMode()},scrollIntoView:"cursor",readOnly:!0,isAvailable:function(e){return e&&e.inMultiSelectMode}}];var r=e("../keyboard/hash_handler").HashHandler;t.keyboardHandler=new r(t.multiSelectCommands)}),ace.define("ace/multi_select",["require","exports","module","ace/range_list","ace/range","ace/selection","ace/mouse/multi_select_handler","ace/lib/event","ace/lib/lang","ace/commands/multi_select_commands","ace/search","ace/edit_session","ace/editor","ace/config"],function(e,t,n){function h(e,t,n){return c.$options.wrap=!0,c.$options.needle=t,c.$options.backwards=n==-1,c.find(e)}function v(e,t){return e.row==t.row&&e.column==t.column}function m(e){if(e.$multiselectOnSessionChange)return;e.$onAddRange=e.$onAddRange.bind(e),e.$onRemoveRange=e.$onRemoveRange.bind(e),e.$onMultiSelect=e.$onMultiSelect.bind(e),e.$onSingleSelect=e.$onSingleSelect.bind(e),e.$multiselectOnSessionChange=t.onSessionChange.bind(e),e.$checkMultiselectChange=e.$checkMultiselectChange.bind(e),e.$multiselectOnSessionChange(e),e.on("changeSession",e.$multiselectOnSessionChange),e.on("mousedown",o),e.commands.addCommands(f.defaultCommands),g(e)}function g(e){function r(t){n&&(e.renderer.setMouseCursor(""),n=!1)}var t=e.textInput.getElement(),n=!1;u.addListener(t,"keydown",function(t){var i=t.keyCode==18&&!(t.ctrlKey||t.shiftKey||t.metaKey);e.$blockSelectEnabled&&i?n||(e.renderer.setMouseCursor("crosshair"),n=!0):n&&r()}),u.addListener(t,"keyup",r),u.addListener(t,"blur",r)}var r=e("./range_list").RangeList,i=e("./range").Range,s=e("./selection").Selection,o=e("./mouse/multi_select_handler").onMouseDown,u=e("./lib/event"),a=e("./lib/lang"),f=e("./commands/multi_select_commands");t.commands=f.defaultCommands.concat(f.multiSelectCommands);var l=e("./search").Search,c=new l,p=e("./edit_session").EditSession;(function(){this.getSelectionMarkers=function(){return this.$selectionMarkers}}).call(p.prototype),function(){this.ranges=null,this.rangeList=null,this.addRange=function(e,t){if(!e)return;if(!this.inMultiSelectMode&&this.rangeCount===0){var n=this.toOrientedRange();this.rangeList.add(n),this.rangeList.add(e);if(this.rangeList.ranges.length!=2)return this.rangeList.removeAll(),t||this.fromOrientedRange(e);this.rangeList.removeAll(),this.rangeList.add(n),this.$onAddRange(n)}e.cursor||(e.cursor=e.end);var r=this.rangeList.add(e);return this.$onAddRange(e),r.length&&this.$onRemoveRange(r),this.rangeCount>1&&!this.inMultiSelectMode&&(this._signal("multiSelect"),this.inMultiSelectMode=!0,this.session.$undoSelect=!1,this.rangeList.attach(this.session)),t||this.fromOrientedRange(e)},this.toSingleRange=function(e){e=e||this.ranges[0];var t=this.rangeList.removeAll();t.length&&this.$onRemoveRange(t),e&&this.fromOrientedRange(e)},this.substractPoint=function(e){var t=this.rangeList.substractPoint(e);if(t)return this.$onRemoveRange(t),t[0]},this.mergeOverlappingRanges=function(){var e=this.rangeList.merge();e.length?this.$onRemoveRange(e):this.ranges[0]&&this.fromOrientedRange(this.ranges[0])},this.$onAddRange=function(e){this.rangeCount=this.rangeList.ranges.length,this.ranges.unshift(e),this._signal("addRange",{range:e})},this.$onRemoveRange=function(e){this.rangeCount=this.rangeList.ranges.length;if(this.rangeCount==1&&this.inMultiSelectMode){var t=this.rangeList.ranges.pop();e.push(t),this.rangeCount=0}for(var n=e.length;n--;){var r=this.ranges.indexOf(e[n]);this.ranges.splice(r,1)}this._signal("removeRange",{ranges:e}),this.rangeCount===0&&this.inMultiSelectMode&&(this.inMultiSelectMode=!1,this._signal("singleSelect"),this.session.$undoSelect=!0,this.rangeList.detach(this.session)),t=t||this.ranges[0],t&&!t.isEqual(this.getRange())&&this.fromOrientedRange(t)},this.$initRangeList=function(){if(this.rangeList)return;this.rangeList=new r,this.ranges=[],this.rangeCount=0},this.getAllRanges=function(){return this.rangeCount?this.rangeList.ranges.concat():[this.getRange()]},this.splitIntoLines=function(){if(this.rangeCount>1){var e=this.rangeList.ranges,t=e[e.length-1],n=i.fromPoints(e[0].start,t.end);this.toSingleRange(),this.setSelectionRange(n,t.cursor==t.start)}else{var n=this.getRange(),r=this.isBackwards(),s=n.start.row,o=n.end.row;if(s==o){if(r)var u=n.end,a=n.start;else var u=n.start,a=n.end;this.addRange(i.fromPoints(a,a)),this.addRange(i.fromPoints(u,u));return}var f=[],l=this.getLineRange(s,!0);l.start.column=n.start.column,f.push(l);for(var c=s+1;c<o;c++)f.push(this.getLineRange(c,!0));l=this.getLineRange(o,!0),l.end.column=n.end.column,f.push(l),f.forEach(this.addRange,this)}},this.toggleBlockSelection=function(){if(this.rangeCount>1){var e=this.rangeList.ranges,t=e[e.length-1],n=i.fromPoints(e[0].start,t.end);this.toSingleRange(),this.setSelectionRange(n,t.cursor==t.start)}else{var r=this.session.documentToScreenPosition(this.cursor),s=this.session.documentToScreenPosition(this.anchor),o=this.rectangularRangeBlock(r,s);o.forEach(this.addRange,this)}},this.rectangularRangeBlock=function(e,t,n){var r=[],s=e.column<t.column;if(s)var o=e.column,u=t.column,a=e.offsetX,f=t.offsetX;else var o=t.column,u=e.column,a=t.offsetX,f=e.offsetX;var l=e.row<t.row;if(l)var c=e.row,h=t.row;else var c=t.row,h=e.row;o<0&&(o=0),c<0&&(c=0),c==h&&(n=!0);var p;for(var d=c;d<=h;d++){var m=i.fromPoints(this.session.screenToDocumentPosition(d,o,a),this.session.screenToDocumentPosition(d,u,f));if(m.isEmpty()){if(p&&v(m.end,p))break;p=m.end}m.cursor=s?m.start:m.end,r.push(m)}l&&r.reverse();if(!n){var g=r.length-1;while(r[g].isEmpty()&&g>0)g--;if(g>0){var y=0;while(r[y].isEmpty())y++}for(var b=g;b>=y;b--)r[b].isEmpty()&&r.splice(b,1)}return r}}.call(s.prototype);var d=e("./editor").Editor;(function(){this.updateSelectionMarkers=function(){this.renderer.updateCursor(),this.renderer.updateBackMarkers()},this.addSelectionMarker=function(e){e.cursor||(e.cursor=e.end);var t=this.getSelectionStyle();return e.marker=this.session.addMarker(e,"ace_selection",t),this.session.$selectionMarkers.push(e),this.session.selectionMarkerCount=this.session.$selectionMarkers.length,e},this.removeSelectionMarker=function(e){if(!e.marker)return;this.session.removeMarker(e.marker);var t=this.session.$selectionMarkers.indexOf(e);t!=-1&&this.session.$selectionMarkers.splice(t,1),this.session.selectionMarkerCount=this.session.$selectionMarkers.length},this.removeSelectionMarkers=function(e){var t=this.session.$selectionMarkers;for(var n=e.length;n--;){var r=e[n];if(!r.marker)continue;this.session.removeMarker(r.marker);var i=t.indexOf(r);i!=-1&&t.splice(i,1)}this.session.selectionMarkerCount=t.length},this.$onAddRange=function(e){this.addSelectionMarker(e.range),this.renderer.updateCursor(),this.renderer.updateBackMarkers()},this.$onRemoveRange=function(e){this.removeSelectionMarkers(e.ranges),this.renderer.updateCursor(),this.renderer.updateBackMarkers()},this.$onMultiSelect=function(e){if(this.inMultiSelectMode)return;this.inMultiSelectMode=!0,this.setStyle("ace_multiselect"),this.keyBinding.addKeyboardHandler(f.keyboardHandler),this.commands.setDefaultHandler("exec",this.$onMultiSelectExec),this.renderer.updateCursor(),this.renderer.updateBackMarkers()},this.$onSingleSelect=function(e){if(this.session.multiSelect.inVirtualMode)return;this.inMultiSelectMode=!1,this.unsetStyle("ace_multiselect"),this.keyBinding.removeKeyboardHandler(f.keyboardHandler),this.commands.removeDefaultHandler("exec",this.$onMultiSelectExec),this.renderer.updateCursor(),this.renderer.updateBackMarkers(),this._emit("changeSelection")},this.$onMultiSelectExec=function(e){var t=e.command,n=e.editor;if(!n.multiSelect)return;if(!t.multiSelectAction){var r=t.exec(n,e.args||{});n.multiSelect.addRange(n.multiSelect.toOrientedRange()),n.multiSelect.mergeOverlappingRanges()}else t.multiSelectAction=="forEach"?r=n.forEachSelection(t,e.args):t.multiSelectAction=="forEachLine"?r=n.forEachSelection(t,e.args,!0):t.multiSelectAction=="single"?(n.exitMultiSelectMode(),r=t.exec(n,e.args||{})):r=t.multiSelectAction(n,e.args||{});return r},this.forEachSelection=function(e,t,n){if(this.inVirtualSelectionMode)return;var r=n&&n.keepOrder,i=n==1||n&&n.$byLines,o=this.session,u=this.selection,a=u.rangeList,f=(r?u:a).ranges,l;if(!f.length)return e.exec?e.exec(this,t||{}):e(this,t||{});var c=u._eventRegistry;u._eventRegistry={};var h=new s(o);this.inVirtualSelectionMode=!0;for(var p=f.length;p--;){if(i)while(p>0&&f[p].start.row==f[p-1].end.row)p--;h.fromOrientedRange(f[p]),h.index=p,this.selection=o.selection=h;var d=e.exec?e.exec(this,t||{}):e(this,t||{});!l&&d!==undefined&&(l=d),h.toOrientedRange(f[p])}h.detach(),this.selection=o.selection=u,this.inVirtualSelectionMode=!1,u._eventRegistry=c,u.mergeOverlappingRanges();var v=this.renderer.$scrollAnimation;return this.onCursorChange(),this.onSelectionChange(),v&&v.from==v.to&&this.renderer.animateScrolling(v.from),l},this.exitMultiSelectMode=function(){if(!this.inMultiSelectMode||this.inVirtualSelectionMode)return;this.multiSelect.toSingleRange()},this.getSelectedText=function(){var e="";if(this.inMultiSelectMode&&!this.inVirtualSelectionMode){var t=this.multiSelect.rangeList.ranges,n=[];for(var r=0;r<t.length;r++)n.push(this.session.getTextRange(t[r]));var i=this.session.getDocument().getNewLineCharacter();e=n.join(i),e.length==(n.length-1)*i.length&&(e="")}else this.selection.isEmpty()||(e=this.session.getTextRange(this.getSelectionRange()));return e},this.$checkMultiselectChange=function(e,t){if(this.inMultiSelectMode&&!this.inVirtualSelectionMode){var n=this.multiSelect.ranges[0];if(this.multiSelect.isEmpty()&&t==this.multiSelect.anchor)return;var r=t==this.multiSelect.anchor?n.cursor==n.start?n.end:n.start:n.cursor;(r.row!=t.row||this.session.$clipPositionToDocument(r.row,r.column).column!=t.column)&&this.multiSelect.toSingleRange(this.multiSelect.toOrientedRange())}},this.findAll=function(e,t,n){t=t||{},t.needle=e||t.needle;if(t.needle==undefined){var r=this.selection.isEmpty()?this.selection.getWordRange():this.selection.getRange();t.needle=this.session.getTextRange(r)}this.$search.set(t);var i=this.$search.findAll(this.session);if(!i.length)return 0;var s=this.multiSelect;n||s.toSingleRange(i[0]);for(var o=i.length;o--;)s.addRange(i[o],!0);return r&&s.rangeList.rangeAtPoint(r.start)&&s.addRange(r,!0),i.length},this.selectMoreLines=function(e,t){var n=this.selection.toOrientedRange(),r=n.cursor==n.end,s=this.session.documentToScreenPosition(n.cursor);this.selection.$desiredColumn&&(s.column=this.selection.$desiredColumn);var o=this.session.screenToDocumentPosition(s.row+e,s.column);if(!n.isEmpty())var u=this.session.documentToScreenPosition(r?n.end:n.start),a=this.session.screenToDocumentPosition(u.row+e,u.column);else var a=o;if(r){var f=i.fromPoints(o,a);f.cursor=f.start}else{var f=i.fromPoints(a,o);f.cursor=f.end}f.desiredColumn=s.column;if(!this.selection.inMultiSelectMode)this.selection.addRange(n);else if(t)var l=n.cursor;this.selection.addRange(f),l&&this.selection.substractPoint(l)},this.transposeSelections=function(e){var t=this.session,n=t.multiSelect,r=n.ranges;for(var i=r.length;i--;){var s=r[i];if(s.isEmpty()){var o=t.getWordRange(s.start.row,s.start.column);s.start.row=o.start.row,s.start.column=o.start.column,s.end.row=o.end.row,s.end.column=o.end.column}}n.mergeOverlappingRanges();var u=[];for(var i=r.length;i--;){var s=r[i];u.unshift(t.getTextRange(s))}e<0?u.unshift(u.pop()):u.push(u.shift());for(var i=r.length;i--;){var s=r[i],o=s.clone();t.replace(s,u[i]),s.start.row=o.start.row,s.start.column=o.start.column}},this.selectMore=function(e,t,n){var r=this.session,i=r.multiSelect,s=i.toOrientedRange();if(s.isEmpty()){s=r.getWordRange(s.start.row,s.start.column),s.cursor=e==-1?s.start:s.end,this.multiSelect.addRange(s);if(n)return}var o=r.getTextRange(s),u=h(r,o,e);u&&(u.cursor=e==-1?u.start:u.end,this.session.unfold(u),this.multiSelect.addRange(u),this.renderer.scrollCursorIntoView(null,.5)),t&&this.multiSelect.substractPoint(s.cursor)},this.alignCursors=function(){var e=this.session,t=e.multiSelect,n=t.ranges,r=-1,s=n.filter(function(e){if(e.cursor.row==r)return!0;r=e.cursor.row});if(!n.length||s.length==n.length-1){var o=this.selection.getRange(),u=o.start.row,f=o.end.row,l=u==f;if(l){var c=this.session.getLength(),h;do h=this.session.getLine(f);while(/[=:]/.test(h)&&++f<c);do h=this.session.getLine(u);while(/[=:]/.test(h)&&--u>0);u<0&&(u=0),f>=c&&(f=c-1)}var p=this.session.removeFullLines(u,f);p=this.$reAlignText(p,l),this.session.insert({row:u,column:0},p.join("\n")+"\n"),l||(o.start.column=0,o.end.column=p[p.length-1].length),this.selection.setRange(o)}else{s.forEach(function(e){t.substractPoint(e.cursor)});var d=0,v=Infinity,m=n.map(function(t){var n=t.cursor,r=e.getLine(n.row),i=r.substr(n.column).search(/\S/g);return i==-1&&(i=0),n.column>d&&(d=n.column),i<v&&(v=i),i});n.forEach(function(t,n){var r=t.cursor,s=d-r.column,o=m[n]-v;s>o?e.insert(r,a.stringRepeat(" ",s-o)):e.remove(new i(r.row,r.column,r.row,r.column-s+o)),t.start.column=t.end.column=d,t.start.row=t.end.row=r.row,t.cursor=t.end}),t.fromOrientedRange(n[0]),this.renderer.updateCursor(),this.renderer.updateBackMarkers()}},this.$reAlignText=function(e,t){function u(e){return a.stringRepeat(" ",e)}function f(e){return e[2]?u(i)+e[2]+u(s-e[2].length+o)+e[4].replace(/^([=:])\s+/,"$1 "):e[0]}function l(e){return e[2]?u(i+s-e[2].length)+e[2]+u(o)+e[4].replace(/^([=:])\s+/,"$1 "):e[0]}function c(e){return e[2]?u(i)+e[2]+u(o)+e[4].replace(/^([=:])\s+/,"$1 "):e[0]}var n=!0,r=!0,i,s,o;return e.map(function(e){var t=e.match(/(\s*)(.*?)(\s*)([=:].*)/);return t?i==null?(i=t[1].length,s=t[2].length,o=t[3].length,t):(i+s+o!=t[1].length+t[2].length+t[3].length&&(r=!1),i!=t[1].length&&(n=!1),i>t[1].length&&(i=t[1].length),s<t[2].length&&(s=t[2].length),o>t[3].length&&(o=t[3].length),t):[e]}).map(t?f:n?r?l:f:c)}}).call(d.prototype),t.onSessionChange=function(e){var t=e.session;t&&!t.multiSelect&&(t.$selectionMarkers=[],t.selection.$initRangeList(),t.multiSelect=t.selection),this.multiSelect=t&&t.multiSelect;var n=e.oldSession;n&&(n.multiSelect.off("addRange",this.$onAddRange),n.multiSelect.off("removeRange",this.$onRemoveRange),n.multiSelect.off("multiSelect",this.$onMultiSelect),n.multiSelect.off("singleSelect",this.$onSingleSelect),n.multiSelect.lead.off("change",this.$checkMultiselectChange),n.multiSelect.anchor.off("change",this.$checkMultiselectChange)),t&&(t.multiSelect.on("addRange",this.$onAddRange),t.multiSelect.on("removeRange",this.$onRemoveRange),t.multiSelect.on("multiSelect",this.$onMultiSelect),t.multiSelect.on("singleSelect",this.$onSingleSelect),t.multiSelect.lead.on("change",this.$checkMultiselectChange),t.multiSelect.anchor.on("change",this.$checkMultiselectChange)),t&&this.inMultiSelectMode!=t.selection.inMultiSelectMode&&(t.selection.inMultiSelectMode?this.$onMultiSelect():this.$onSingleSelect())},t.MultiSelect=m,e("./config").defineOptions(d.prototype,"editor",{enableMultiselect:{set:function(e){m(this),e?(this.on("changeSession",this.$multiselectOnSessionChange),this.on("mousedown",o)):(this.off("changeSession",this.$multiselectOnSessionChange),this.off("mousedown",o))},value:!0},enableBlockSelect:{set:function(e){this.$blockSelectEnabled=e},value:!0}})}),ace.define("ace/mode/folding/fold_mode",["require","exports","module","ace/range"],function(e,t,n){"use strict";var r=e("../../range").Range,i=t.FoldMode=function(){};(function(){this.foldingStartMarker=null,this.foldingStopMarker=null,this.getFoldWidget=function(e,t,n){var r=e.getLine(n);return this.foldingStartMarker.test(r)?"start":t=="markbeginend"&&this.foldingStopMarker&&this.foldingStopMarker.test(r)?"end":""},this.getFoldWidgetRange=function(e,t,n){return null},this.indentationBlock=function(e,t,n){var i=/\S/,s=e.getLine(t),o=s.search(i);if(o==-1)return;var u=n||s.length,a=e.getLength(),f=t,l=t;while(++t<a){var c=e.getLine(t).search(i);if(c==-1)continue;if(c<=o)break;l=t}if(l>f){var h=e.getLine(l).length;return new r(f,u,l,h)}},this.openingBracketBlock=function(e,t,n,i,s){var o={row:n,column:i+1},u=e.$findClosingBracket(t,o,s);if(!u)return;var a=e.foldWidgets[u.row];return a==null&&(a=e.getFoldWidget(u.row)),a=="start"&&u.row>o.row&&(u.row--,u.column=e.getLine(u.row).length),r.fromPoints(o,u)},this.closingBracketBlock=function(e,t,n,i,s){var o={row:n,column:i},u=e.$findOpeningBracket(t,o);if(!u)return;return u.column++,o.column--,r.fromPoints(u,o)}}).call(i.prototype)}),ace.define("ace/theme/textmate",["require","exports","module","ace/lib/dom"],function(e,t,n){"use strict";t.isDark=!1,t.cssClass="ace-tm",t.cssText='.ace-tm .ace_gutter {background: #f0f0f0;color: #333;}.ace-tm .ace_print-margin {width: 1px;background: #e8e8e8;}.ace-tm .ace_fold {background-color: #6B72E6;}.ace-tm {background-color: #FFFFFF;color: black;}.ace-tm .ace_cursor {color: black;}.ace-tm .ace_invisible {color: rgb(191, 191, 191);}.ace-tm .ace_storage,.ace-tm .ace_keyword {color: blue;}.ace-tm .ace_constant {color: rgb(197, 6, 11);}.ace-tm .ace_constant.ace_buildin {color: rgb(88, 72, 246);}.ace-tm .ace_constant.ace_language {color: rgb(88, 92, 246);}.ace-tm .ace_constant.ace_library {color: rgb(6, 150, 14);}.ace-tm .ace_invalid {background-color: rgba(255, 0, 0, 0.1);color: red;}.ace-tm .ace_support.ace_function {color: rgb(60, 76, 114);}.ace-tm .ace_support.ace_constant {color: rgb(6, 150, 14);}.ace-tm .ace_support.ace_type,.ace-tm .ace_support.ace_class {color: rgb(109, 121, 222);}.ace-tm .ace_keyword.ace_operator {color: rgb(104, 118, 135);}.ace-tm .ace_string {color: rgb(3, 106, 7);}.ace-tm .ace_comment {color: rgb(76, 136, 107);}.ace-tm .ace_comment.ace_doc {color: rgb(0, 102, 255);}.ace-tm .ace_comment.ace_doc.ace_tag {color: rgb(128, 159, 191);}.ace-tm .ace_constant.ace_numeric {color: rgb(0, 0, 205);}.ace-tm .ace_variable {color: rgb(49, 132, 149);}.ace-tm .ace_xml-pe {color: rgb(104, 104, 91);}.ace-tm .ace_entity.ace_name.ace_function {color: #0000A2;}.ace-tm .ace_heading {color: rgb(12, 7, 255);}.ace-tm .ace_list {color:rgb(185, 6, 144);}.ace-tm .ace_meta.ace_tag {color:rgb(0, 22, 142);}.ace-tm .ace_string.ace_regex {color: rgb(255, 0, 0)}.ace-tm .ace_marker-layer .ace_selection {background: rgb(181, 213, 255);}.ace-tm.ace_multiselect .ace_selection.ace_start {box-shadow: 0 0 3px 0px white;}.ace-tm .ace_marker-layer .ace_step {background: rgb(252, 255, 0);}.ace-tm .ace_marker-layer .ace_stack {background: rgb(164, 229, 101);}.ace-tm .ace_marker-layer .ace_bracket {margin: -1px 0 0 -1px;border: 1px solid rgb(192, 192, 192);}.ace-tm .ace_marker-layer .ace_active-line {background: rgba(0, 0, 0, 0.07);}.ace-tm .ace_gutter-active-line {background-color : #dcdcdc;}.ace-tm .ace_marker-layer .ace_selected-word {background: rgb(250, 250, 255);border: 1px solid rgb(200, 200, 250);}.ace-tm .ace_indent-guide {background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAE0lEQVQImWP4////f4bLly//BwAmVgd1/w11/gAAAABJRU5ErkJggg==") right repeat-y;}',t.$id="ace/theme/textmate";var r=e("../lib/dom");r.importCssString(t.cssText,t.cssClass)}),ace.define("ace/line_widgets",["require","exports","module","ace/lib/oop","ace/lib/dom","ace/range"],function(e,t,n){"use strict";function o(e){this.session=e,this.session.widgetManager=this,this.session.getRowLength=this.getRowLength,this.session.$getWidgetScreenLength=this.$getWidgetScreenLength,this.updateOnChange=this.updateOnChange.bind(this),this.renderWidgets=this.renderWidgets.bind(this),this.measureWidgets=this.measureWidgets.bind(this),this.session._changedWidgets=[],this.$onChangeEditor=this.$onChangeEditor.bind(this),this.session.on("change",this.updateOnChange),this.session.on("changeFold",this.updateOnFold),this.session.on("changeEditor",this.$onChangeEditor)}var r=e("./lib/oop"),i=e("./lib/dom"),s=e("./range").Range;(function(){this.getRowLength=function(e){var t;return this.lineWidgets?t=this.lineWidgets[e]&&this.lineWidgets[e].rowCount||0:t=0,!this.$useWrapMode||!this.$wrapData[e]?1+t:this.$wrapData[e].length+1+t},this.$getWidgetScreenLength=function(){var e=0;return this.lineWidgets.forEach(function(t){t&&t.rowCount&&!t.hidden&&(e+=t.rowCount)}),e},this.$onChangeEditor=function(e){this.attach(e.editor)},this.attach=function(e){e&&e.widgetManager&&e.widgetManager!=this&&e.widgetManager.detach();if(this.editor==e)return;this.detach(),this.editor=e,e&&(e.widgetManager=this,e.renderer.on("beforeRender",this.measureWidgets),e.renderer.on("afterRender",this.renderWidgets))},this.detach=function(e){var t=this.editor;if(!t)return;this.editor=null,t.widgetManager=null,t.renderer.off("beforeRender",this.measureWidgets),t.renderer.off("afterRender",this.renderWidgets);var n=this.session.lineWidgets;n&&n.forEach(function(e){e&&e.el&&e.el.parentNode&&(e._inDocument=!1,e.el.parentNode.removeChild(e.el))})},this.updateOnFold=function(e,t){var n=t.lineWidgets;if(!n||!e.action)return;var r=e.data,i=r.start.row,s=r.end.row,o=e.action=="add";for(var u=i+1;u<s;u++)n[u]&&(n[u].hidden=o);n[s]&&(o?n[i]?n[s].hidden=o:n[i]=n[s]:(n[i]==n[s]&&(n[i]=undefined),n[s].hidden=o))},this.updateOnChange=function(e){var t=this.session.lineWidgets;if(!t)return;var n=e.start.row,r=e.end.row-n;if(r!==0)if(e.action=="remove"){var i=t.splice(n+1,r);i.forEach(function(e){e&&this.removeLineWidget(e)},this),this.$updateRows()}else{var s=new Array(r);s.unshift(n,0),t.splice.apply(t,s),this.$updateRows()}},this.$updateRows=function(){var e=this.session.lineWidgets;if(!e)return;var t=!0;e.forEach(function(e,n){if(e){t=!1,e.row=n;while(e.$oldWidget)e.$oldWidget.row=n,e=e.$oldWidget}}),t&&(this.session.lineWidgets=null)},this.addLineWidget=function(e){this.session.lineWidgets||(this.session.lineWidgets=new Array(this.session.getLength()));var t=this.session.lineWidgets[e.row];t&&(e.$oldWidget=t,t.el&&t.el.parentNode&&(t.el.parentNode.removeChild(t.el),t._inDocument=!1)),this.session.lineWidgets[e.row]=e,e.session=this.session;var n=this.editor.renderer;e.html&&!e.el&&(e.el=i.createElement("div"),e.el.innerHTML=e.html),e.el&&(i.addCssClass(e.el,"ace_lineWidgetContainer"),e.el.style.position="absolute",e.el.style.zIndex=5,n.container.appendChild(e.el),e._inDocument=!0),e.coverGutter||(e.el.style.zIndex=3),e.pixelHeight==null&&(e.pixelHeight=e.el.offsetHeight),e.rowCount==null&&(e.rowCount=e.pixelHeight/n.layerConfig.lineHeight);var r=this.session.getFoldAt(e.row,0);e.$fold=r;if(r){var s=this.session.lineWidgets;e.row==r.end.row&&!s[r.start.row]?s[r.start.row]=e:e.hidden=!0}return this.session._emit("changeFold",{data:{start:{row:e.row}}}),this.$updateRows(),this.renderWidgets(null,n),this.onWidgetChanged(e),e},this.removeLineWidget=function(e){e._inDocument=!1,e.session=null,e.el&&e.el.parentNode&&e.el.parentNode.removeChild(e.el);if(e.editor&&e.editor.destroy)try{e.editor.destroy()}catch(t){}if(this.session.lineWidgets){var n=this.session.lineWidgets[e.row];if(n==e)this.session.lineWidgets[e.row]=e.$oldWidget,e.$oldWidget&&this.onWidgetChanged(e.$oldWidget);else while(n){if(n.$oldWidget==e){n.$oldWidget=e.$oldWidget;break}n=n.$oldWidget}}this.session._emit("changeFold",{data:{start:{row:e.row}}}),this.$updateRows()},this.getWidgetsAtRow=function(e){var t=this.session.lineWidgets,n=t&&t[e],r=[];while(n)r.push(n),n=n.$oldWidget;return r},this.onWidgetChanged=function(e){this.session._changedWidgets.push(e),this.editor&&this.editor.renderer.updateFull()},this.measureWidgets=function(e,t){var n=this.session._changedWidgets,r=t.layerConfig;if(!n||!n.length)return;var i=Infinity;for(var s=0;s<n.length;s++){var o=n[s];if(!o||!o.el)continue;if(o.session!=this.session)continue;if(!o._inDocument){if(this.session.lineWidgets[o.row]!=o)continue;o._inDocument=!0,t.container.appendChild(o.el)}o.h=o.el.offsetHeight,o.fixedWidth||(o.w=o.el.offsetWidth,o.screenWidth=Math.ceil(o.w/r.characterWidth));var u=o.h/r.lineHeight;o.coverLine&&(u-=this.session.getRowLineCount(o.row),u<0&&(u=0)),o.rowCount!=u&&(o.rowCount=u,o.row<i&&(i=o.row))}i!=Infinity&&(this.session._emit("changeFold",{data:{start:{row:i}}}),this.session.lineWidgetWidth=null),this.session._changedWidgets=[]},this.renderWidgets=function(e,t){var n=t.layerConfig,r=this.session.lineWidgets;if(!r)return;var i=Math.min(this.firstRow,n.firstRow),s=Math.max(this.lastRow,n.lastRow,r.length);while(i>0&&!r[i])i--;this.firstRow=n.firstRow,this.lastRow=n.lastRow,t.$cursorLayer.config=n;for(var o=i;o<=s;o++){var u=r[o];if(!u||!u.el)continue;if(u.hidden){u.el.style.top=-100-(u.pixelHeight||0)+"px";continue}u._inDocument||(u._inDocument=!0,t.container.appendChild(u.el));var a=t.$cursorLayer.getPixelPosition({row:o,column:0},!0).top;u.coverLine||(a+=n.lineHeight*this.session.getRowLineCount(u.row)),u.el.style.top=a-n.offset+"px";var f=u.coverGutter?0:t.gutterWidth;u.fixedWidth||(f-=t.scrollLeft),u.el.style.left=f+"px",u.fullWidth&&u.screenWidth&&(u.el.style.minWidth=n.width+2*n.padding+"px"),u.fixedWidth?u.el.style.right=t.scrollBar.getWidth()+"px":u.el.style.right=""}}}).call(o.prototype),t.LineWidgets=o}),ace.define("ace/ext/error_marker",["require","exports","module","ace/line_widgets","ace/lib/dom","ace/range"],function(e,t,n){"use strict";function o(e,t,n){var r=0,i=e.length-1;while(r<=i){var s=r+i>>1,o=n(t,e[s]);if(o>0)r=s+1;else{if(!(o<0))return s;i=s-1}}return-(r+1)}function u(e,t,n){var r=e.getAnnotations().sort(s.comparePoints);if(!r.length)return;var i=o(r,{row:t,column:-1},s.comparePoints);i<0&&(i=-i-1),i>=r.length?i=n>0?0:r.length-1:i===0&&n<0&&(i=r.length-1);var u=r[i];if(!u||!n)return;if(u.row===t){do u=r[i+=n];while(u&&u.row===t);if(!u)return r.slice()}var a=[];t=u.row;do a[n<0?"unshift":"push"](u),u=r[i+=n];while(u&&u.row==t);return a.length&&a}var r=e("../line_widgets").LineWidgets,i=e("../lib/dom"),s=e("../range").Range;t.showErrorMarker=function(e,t){var n=e.session;n.widgetManager||(n.widgetManager=new r(n),n.widgetManager.attach(e));var s=e.getCursorPosition(),o=s.row,a=n.widgetManager.getWidgetsAtRow(o).filter(function(e){return e.type=="errorMarker"})[0];a?a.destroy():o-=t;var f=u(n,o,t),l;if(f){var c=f[0];s.column=(c.pos&&typeof c.column!="number"?c.pos.sc:c.column)||0,s.row=c.row,l=e.renderer.$gutterLayer.$annotations[s.row]}else{if(a)return;l={text:["Looks good!"],className:"ace_ok"}}e.session.unfold(s.row),e.selection.moveToPosition(s);var h={row:s.row,fixedWidth:!0,coverGutter:!0,el:i.createElement("div"),type:"errorMarker"},p=h.el.appendChild(i.createElement("div")),d=h.el.appendChild(i.createElement("div"));d.className="error_widget_arrow "+l.className;var v=e.renderer.$cursorLayer.getPixelPosition(s).left;d.style.left=v+e.renderer.gutterWidth-5+"px",h.el.className="error_widget_wrapper",p.className="error_widget "+l.className,p.innerHTML=l.text.join("<br>"),p.appendChild(i.createElement("div"));var m=function(e,t,n){if(t===0&&(n==="esc"||n==="return"))return h.destroy(),{command:"null"}};h.destroy=function(){if(e.$mouseHandler.isMousePressed)return;e.keyBinding.removeKeyboardHandler(m),n.widgetManager.removeLineWidget(h),e.off("changeSelection",h.destroy),e.off("changeSession",h.destroy),e.off("mouseup",h.destroy),e.off("change",h.destroy)},e.keyBinding.addKeyboardHandler(m),e.on("changeSelection",h.destroy),e.on("changeSession",h.destroy),e.on("mouseup",h.destroy),e.on("change",h.destroy),e.session.widgetManager.addLineWidget(h),h.el.onmousedown=e.focus.bind(e),e.renderer.scrollCursorIntoView(null,.5,{bottom:h.el.offsetHeight})},i.importCssString("    .error_widget_wrapper {        background: inherit;        color: inherit;        border:none    }    .error_widget {        border-top: solid 2px;        border-bottom: solid 2px;        margin: 5px 0;        padding: 10px 40px;        white-space: pre-wrap;    }    .error_widget.ace_error, .error_widget_arrow.ace_error{        border-color: #ff5a5a    }    .error_widget.ace_warning, .error_widget_arrow.ace_warning{        border-color: #F1D817    }    .error_widget.ace_info, .error_widget_arrow.ace_info{        border-color: #5a5a5a    }    .error_widget.ace_ok, .error_widget_arrow.ace_ok{        border-color: #5aaa5a    }    .error_widget_arrow {        position: absolute;        border: solid 5px;        border-top-color: transparent!important;        border-right-color: transparent!important;        border-left-color: transparent!important;        top: -5px;    }","")}),ace.define("ace/ace",["require","exports","module","ace/lib/fixoldbrowsers","ace/lib/dom","ace/lib/event","ace/range","ace/editor","ace/edit_session","ace/undomanager","ace/virtual_renderer","ace/worker/worker_client","ace/keyboard/hash_handler","ace/placeholder","ace/multi_select","ace/mode/folding/fold_mode","ace/theme/textmate","ace/ext/error_marker","ace/config"],function(e,t,n){"use strict";e("./lib/fixoldbrowsers");var r=e("./lib/dom"),i=e("./lib/event"),s=e("./range").Range,o=e("./editor").Editor,u=e("./edit_session").EditSession,a=e("./undomanager").UndoManager,f=e("./virtual_renderer").VirtualRenderer;e("./worker/worker_client"),e("./keyboard/hash_handler"),e("./placeholder"),e("./multi_select"),e("./mode/folding/fold_mode"),e("./theme/textmate"),e("./ext/error_marker"),t.config=e("./config"),t.require=e,typeof define=="function"&&(t.define=define),t.edit=function(e,n){if(typeof e=="string"){var s=e;e=document.getElementById(s);if(!e)throw new Error("ace.edit can't find div #"+s)}if(e&&e.env&&e.env.editor instanceof o)return e.env.editor;var u="";if(e&&/input|textarea/i.test(e.tagName)){var a=e;u=a.value,e=r.createElement("pre"),a.parentNode.replaceChild(e,a)}else e&&(u=e.textContent,e.innerHTML="");var l=t.createEditSession(u),c=new o(new f(e),l,n),h={document:l,editor:c,onResize:c.resize.bind(c,null)};return a&&(h.textarea=a),i.addListener(window,"resize",h.onResize),c.on("destroy",function(){i.removeListener(window,"resize",h.onResize),h.editor.container.env=null}),c.container.env=c.env=h,c},t.createEditSession=function(e,t){var n=new u(e,t);return n.setUndoManager(new a),n},t.Range=s,t.EditSession=u,t.UndoManager=a,t.VirtualRenderer=f,t.version="1.3.1"});
            (function() {
                ace.require(["ace/ace"], function(a) {
                    if (a) {
                        a.config.init(true);
                        a.define = ace.define;
                    }
                    if (!window.ace)
                        window.ace = a;
                    for (var key in a) if (a.hasOwnProperty(key))
                        window.ace[key] = a[key];
                });
            })();
        
			
			/**
 * IFM constructor
 *
 * @param object params - object with some configuration values, currently you only can set the api url
 */
function IFM( params ) {
	// reference to ourself, because "this" does not work within callbacks
	var self = this;

	params = params || {};
	// set the backend for the application
	self.api = params.api || window.location.pathname;

	this.editor = null;		// global ace editor
	this.fileChanged = false;	// flag for check if file was changed already
	this.currentDir = "";		// this is the global variable for the current directory; it is used for AJAX requests
	this.rootElement = "";		// global root element, currently not used
	this.fileCache = [];		// holds the current set of files
	this.variantCache = [];		// holds the current set of variants
	this.search = {};		// holds the last search query, as well as the search results

	// This indicates if the modal was closed by a button or not, to prevent the user
	// from accidentially close it while editing a file.
	this.isModalClosedByButton = false;

	this.filedatatable = null; // Reference for the file data table
	this.variantdatatable = null; // Reference for the variant  table

	this.allowedfiles = ["png", "jpg", "jpeg", "bmp", "gif", 
						 "3ds", "amf", "awd", "babylon", "babylonmeshdata", "ctm",
						 "dae", "fbx", "glb", "gltf", "bin", "jf", "json", "3geo", "3mat",
						 "3obj", "3scn", "kmz", "md2", "obj", "mtl", "playcanvas", "ply",
						 "stl", "svg", "vtk", "wrl"];

	/**
	 * Shows a bootstrap modal
	 *
	 * @param {string} content - HTML content of the modal
	 * @param {object} options - options for the modal ({ large: false })
	 */
	this.showModal = function( content, options ) {
		options = options || {};
		var modal = document.createElement( 'div' );
		modal.classList.add( 'modal', 'fade' );
		modal.id = 'ifmmodal';
		modal.attributes.role = 'dialog';
		var modalDialog = document.createElement( 'div' );
		modalDialog.classList.add( 'modal-dialog' );
		modalDialog.attributes.role = 'document';
		if( options.large == true ) modalDialog.classList.add( 'modal-lg' );
		var modalContent = document.createElement('div');
		modalContent.classList.add( 'modal-content' );
		modalContent.innerHTML = content;
		modalDialog.appendChild( modalContent );
		modal.appendChild( modalDialog );
		document.body.appendChild( modal );

		// For this we have to use jquery, because bootstrap modals depend on them. Also the bs.modal
		// events require jquery, as they cannot be handled by addEventListener()
		$(modal)
			.on( 'hide.bs.modal', function( e ) {
				if( document.forms.formFile && self.fileChanged && !self.isModalClosedByButton ) {
					console.log( "Prevented closing modal because the file was changed and no button was clicked." );
					e.preventDefault();
				} else
					$(this).remove();
			})
			.on( 'shown.bs.modal', function( e ) {
				var formElements = $(this).find('input, button');
				if( formElements.length > 0 ) {
					formElements.first().focus();
				}
			})
			.modal('show');
	};

	/**
	 * Hides a the current bootstrap modal
	 */
	this.hideModal = function() {
		// Hide the modal via jquery to get the hide.bs.modal event triggered
		$( '#ifmmodal' ).modal( 'hide' );
		self.isModalClosedByButton = false;
	};

	/**
	 * Refreshes the file table
	 */
	this.refreshFileTable = function () {
		var taskid = self.generateGuid();
		self.task_add( { id: taskid, name: self.i18n.refresh } );
		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				api: "getFiles",
				dir: self.currentDir
			},
			dataType: "json",
			success: self.rebuildFileTable,
			error: function() { self.showMessage( self.i18n.general_error, "e" ); },
			complete: function() { self.task_done( taskid ); }
		});
	};

	/**
	 * Refreshes the file table
	 */
	this.refreshVariantTable = function () {
		var taskid = self.generateGuid();
		self.task_add( { id: taskid, name: self.i18n.refresh } );
		let currentModelPromise = i2ModelBuilder.getModelByPath(self.currentDir);
		currentModelPromise.then(async (currentModel) => {
			let variantsPromise = currentModel.getVariants();
			variantsPromise.then((variants) => {
				self.rebuildVariantTable(variants);
			}, () => {
				self.rebuildVariantTable([]);
			});
		}, ()=>{}).then(() => {
			self.task_done( taskid );
		});
	};

	/**
	 * Rebuilds the file table with fetched items
	 *
	 * @param object data - object with items
	 */
	this.rebuildFileTable = function( data ) {
		if( data.status == "ERROR" ) {
			this.showMessage( data.message, "e" );
			return;
		} else if ( ! Array.isArray( data ) ) {
			this.showMessage( self.i18n.invalid_data, "e" );
			return;
		}
		data.forEach( function( item ) {
			item.guid = self.generateGuid();
			item.linkname = ( item.name == ".." ) ? "[ up ]" : item.name;
			if( item.name == ".." )
				item.fixtop = 100;
			item.download = {};
			item.download.name = ( item.name == ".." ) ? "." : item.name;
			item.download.currentDir = self.currentDir;
			if( self.config.isDocroot )
				item.href = 'href="'+self.hrefEncode( self.pathCombine( self.currentDir, item.name ) )+'"';
			if( ! self.config.chmod )
				item.readonly = "readonly";
			if( self.config.edit || self.config.rename || self.config.delete || self.config.extract || self.config.copymove ) {
				item.ftbuttons = true;
				item.button = [];
			}
			if( item.type == "dir" ) {
				if( self.config.download && self.config.zipnload ) {
					item.download.action = "zipnload";
					item.download.icon = "icon icon-download-cloud";
				}
				item.rowclasses = "isDir";
			} else {
				if( self.config.download && self.config.zipnload ) {
					item.download.action = "download";
					item.download.icon = "icon icon-download";
				}
				if( item.icon.indexOf( 'file-image' ) !== -1  ) {
					item.tooltip = 'data-toggle="tooltip"';
				}
				if( self.config.extract && self.inArray( item.ext, ["zip","tar","tgz","tar.gz","tar.xz","tar.bz2"] ) ) {
					item.eaction = "extract";
					item.button.push({
						action: "extract",
						icon: "icon icon-archive",
						title: "extract"
					});
				} else if(
					self.config.edit &&
					(
						self.config.disable_mime_detection ||
						(
							typeof item.mime_type === "string" && (
								item.mime_type.substr( 0, 4 ) == "text"
								|| item.mime_type == "inode/x-empty"
								|| item.mime_type.indexOf( "xml" ) != -1
								|| item.mime_type.indexOf( "json" ) != -1
							)
						)
					)
				) {
					item.eaction = "edit";
					item.button.push({
						action: "edit",
						icon: "icon icon-pencil",
						title: "edit"
					});
				}
			}
			if( ! self.inArray( item.name, [".", ".."] ) ) {
				item.dragdrop = 'draggable="true"';
				if( self.config.copymove )
					item.button.push({
						action: "copymove",
						icon: "icon icon-folder-open-empty",
						title: "copy/move"
					});
				if( self.config.rename )
					item.button.push({
						action: "rename",
						icon: "icon icon-terminal",
						title: "rename"
					});
				if( self.config.delete )
					item.button.push({
						action: "delete",
						icon: "icon icon-trash",
						title: "delete"
					});
			}
		});

		// save items to file cache
		self.fileCache = data;


		// build new tbody and replace the old one with the new
		var newTBody = Mustache.render( self.templates.filetable, { items: data, config: self.config, i18n: self.i18n, api: self.api } );
		var filetable = document.getElementById( 'filetable' );
		filetable.tBodies[0].remove();
		filetable.append( document.createElement( 'tbody' ) );
		filetable.tBodies[0].innerHTML = newTBody;

		if( self.filedatatable ) self.filedatatable.destroy();
		self.filedatatable = $('#filetable').DataTable({
			paging: false,
			info: false,
			autoWidth: false,
			columnDefs: [
				{ "orderable": false, "targets": ["th-download","th-permissions","th-buttons"] }
			],
			orderFixed: [0, 'desc'],
			language: {
				"search": self.i18n.filter
			}
		});


		// add event listeners
		filetable.tBodies[0].addEventListener( 'keypress', function( e ) {
			if( e.target.name == 'newpermissions' && !!self.config.chmod && e.key == 'Enter' )
				self.changePermissions( e.target.dataset.filename, e.target.value );
		});
		filetable.tBodies[0].addEventListener( 'click', function( e ) {
			if( e.target.tagName == "TD" && e.target.parentElement.classList.contains( 'clickable-file-row' ) && e.target.parentElement.dataset.filename !== ".." && e.ctrlKey )
				e.target.parentElement.classList.toggle( 'selectedItem' );
			else if( e.target.classList.contains( 'ifmitem' ) || e.target.parentElement.classList.contains( 'ifmitem' ) ) {
				e.stopPropagation();
				e.preventDefault();
				ifmitem = ( e.target.classList.contains( 'ifmitem' ) ? e.target : e.target.parentElement );
				if( ifmitem.dataset.type == "dir" )
					self.changeDirectory( ifmitem.parentElement.parentElement.dataset.filename );
				else
					if( self.config.isDocroot )
						window.location.href = self.hrefEncode( self.pathCombine( self.currentDir, ifmitem.parentElement.parentElement.dataset.filename ) );
					else
						document.forms["d_"+ifmitem.id].submit();
			} else if( e.target.parentElement.name == 'start_download' ) {
				e.stopPropagation();
				e.preventDefault();
				document.forms["d_"+e.target.parentElement.dataset.guid].submit();
			} else if( e.target.parentElement.name && e.target.parentElement.name.substring(0, 3) == "do-" ) {
				e.stopPropagation();
				e.preventDefault();
				var item = self.fileCache.find( function( x ) { if( x.guid === e.target.parentElement.dataset.id ) return x; } );
				switch( e.target.parentElement.name.substr( 3 ) ) {
					case "rename":
						self.showRenameFileDialog( item.name );
						break;
					case "extract":
						self.showExtractFileDialog( item.name );
						break;
					case "edit":
						self.editFile( item.name );
						break;
					case "delete":
						self.showDeleteDialog( item );
						break;
					case "copymove":
						self.showCopyMoveDialog( item );
						break;
				}
			}
		});
		// has to be jquery, since this is a bootstrap feature
		$( 'a[data-toggle="tooltip"]' ).tooltip({
			title: function() {
				var item = self.fileCache.find( x => x.guid == $(this).attr('id') );
				var tooltip = document.createElement( 'img' );
				if( self.config.isDocroot )
					tooltip.src = encodeURI( self.pathCombine( self.currentDir, item.name ) ).replace( '#', '%23' ).replace( '?', '%3F' );
				else
					tooltip.src = self.api + "?api=proxy&dir=" + encodeURIComponent( self.currentDir ) + "&filename=" + encodeURIComponent( item.name );
				tooltip.classList.add( 'imgpreview' );
				return tooltip;
			},
			animated: 'fade',
			placement: 'right',
			html: true
		});

		if( self.config.contextmenu && !!( self.config.edit || self.config.extract || self.config.rename || self.config.copymove || self.config.download || self.config.delete ) ) {
			// create the context menu, this also uses jquery, AFAIK
			var contextMenu = new BootstrapMenu( '.clickable-file-row', {
				fetchElementData: function( row ) {
					var data = {};
					data.selected =
						Array.prototype.slice.call( document.getElementsByClassName( 'selectedItem' ) )
						.map( function(e){ return self.fileCache.find( x => x.guid == e.children[0].children[0].id ); } );
					data.clicked = self.fileCache.find( x => x.guid == row[0].children[0].children[0].id );
					return data;
				},
				actionsGroups:[
					['edit', 'extract', 'rename'],
					['copymove', 'download', 'createarchive', 'delete']
				],
				actions: {
					edit: {
						name: self.i18n.edit,
						onClick: function( data ) {
							self.editFile( data.clicked.name );
						},
						iconClass: "icon icon-pencil",
						isShown: function( data ) {
							return !!( self.config.edit && data.clicked.eaction == "edit" && !data.selected.length );
						}
					},
					extract: {
						name: self.i18n.extract,
						onClick: function( data ) {
							self.showExtractFileDialog( data.clicked.name );
						},
						iconClass: "icon icon-archive",
						isShown: function( data ) {
							return !!( self.config.extract && data.clicked.eaction == "extract" && !data.selected.length );
						}
					},
					rename: {
						name: self.i18n.rename,
						onClick: function( data ) {
							self.showRenameFileDialog( data.clicked.name );
						},
						iconClass: "icon icon-terminal",
						isShown: function( data ) { return !!( self.config.rename && !data.selected.length && data.clicked.name != ".." ); }
					},
					copymove: {
						name: function( data ) {
							if( data.selected.length > 0 )
								return self.i18n.copy+'/'+self.i18n.move+' <span class="badge">'+data.selected.length+'</span>';
							else
								return self.i18n.copy+'/'+self.i18n.move;
						},
						onClick: function( data ) {
							if( data.selected.length > 0 )
								self.showCopyMoveDialog( data.selected );
							else
								self.showCopyMoveDialog( data.clicked );
						},
						iconClass: "icon icon-folder-empty",
						isShown: function( data ) { return !!( self.config.copymove && data.clicked.name != ".." ); }
					},
					download: {
						name: function( data ) {
							if( data.selected.length > 0 )
								return self.i18n.download+' <span class="badge">'+data.selected.length+'</span>';
							else
								return self.i18n.download;
						},
						onClick: function( data ) {
							if( data.selected.length > 0 )
								self.showMessage( "At the moment it is not possible to download a set of files." );
							else
								document.forms["d_"+data.clicked.guid].submit();
						},
						iconClass: "icon icon-download",
						isShown: function() { return !!self.config.download; }
					},
					createarchive: {
						name: function( data ) {
							if( data.selected.length > 0 )
								return self.i18n.create_archive+' <span class="badge">'+data.selected.length+'</span>';
							else
								return self.i18n.create_archive;
						},
						onClick: function( data ) {
							if( data.selected.length > 0 )
								self.showCreateArchiveDialog( data.selected );
							else
								self.showCreateArchiveDialog( data.clicked );
						},
						iconClass: "icon icon-archive",
						isShown: function( data ) { return !!( self.config.createarchive && data.clicked.name != ".." ); }
					},
					'delete': {
						name: function( data ) {
							if( data.selected.length > 0 )
								return self.i18n.delete+' <span class="badge">'+data.selected.length+'</span>';
							else
								return self.i18n.delete;
						},
						onClick: function( data ) {
							if( data.selected.length > 0 )
								self.showDeleteDialog( data.selected );
							else
								self.showDeleteDialog( data.clicked );
						},
						iconClass: "icon icon-trash",
						isShown: function( data ) { return !!( self.config.delete && data.clicked.name != ".." ); }
					}
				}
			});
		}
	};

	/**
	 * Rebuilds the file table with fetched items
	 *
	 * @param object data - object with items
	 */
	this.rebuildVariantTable = function( variants ) {
		variants.forEach( function( item ) {
			item.variantid = item.data.id;
			item.guid = self.generateGuid();
		});

		// save items to file cache
		self.variantCache = variants;


		// build new tbody and replace the old one with the new
		var newTBody = Mustache.render( self.templates.varianttable, { items: variants, config: self.config, i18n: self.i18n, api: self.api } );
		var filetable = document.getElementById( 'varianttable' );
		filetable.tBodies[0].remove();
		filetable.append( document.createElement( 'tbody' ) );
		filetable.tBodies[0].innerHTML = newTBody;

		if( self.variantdatatable ) self.variantdatatable.destroy();
		self.variantdatatable = $('#varianttable').DataTable({
			paging: false,
			info: false,
			autoWidth: false,
			columnDefs: [
				{ "orderable": false, "targets": ["th-download","th-permissions","th-buttons"] }
			],
			orderFixed: [0, 'desc'],
			language: {
				"search": self.i18n.filter
			}
		});

		// add event listeners
		filetable.tBodies[0].addEventListener( 'keypress', function( e ) {
			if( e.target.name == 'newpermissions' && !!self.config.chmod && e.key == 'Enter' )
				self.changePermissions( e.target.dataset.filename, e.target.value );
		});
		filetable.tBodies[0].addEventListener( 'click', function( e ) {
			if( e.target.tagName == "TD" && e.target.parentElement.classList.contains( 'clickable-variant-row' ) && e.target.parentElement.dataset.filename !== ".." && e.ctrlKey )
				e.target.parentElement.classList.toggle( 'selectedItem' );
			else if( e.target.classList.contains( 'ifmitem' ) || e.target.parentElement.classList.contains( 'ifmitem' ) ) {
				e.stopPropagation();
				e.preventDefault();
				ifmitem = ( e.target.classList.contains( 'ifmitem' ) ? e.target : e.target.parentElement );
				if( ifmitem.dataset.type == "dir" )
					self.changeDirectory( ifmitem.parentElement.parentElement.dataset.filename );
				else
					if( self.config.isDocroot )
						window.location.href = self.hrefEncode( self.pathCombine( self.currentDir, ifmitem.parentElement.parentElement.dataset.filename ) );
					else
						document.forms["d_"+ifmitem.id].submit();
			} else if( e.target.parentElement.name == 'start_download' ) {
				e.stopPropagation();
				e.preventDefault();
				document.forms["d_"+e.target.parentElement.dataset.guid].submit();
			} else if( e.target.parentElement.name && e.target.parentElement.name.substring(0, 3) == "do-" ) {
				e.stopPropagation();
				e.preventDefault();
				var item = self.variantCache.find( function( x ) { if( x.guid === e.target.parentElement.dataset.id ) return x; } );
				switch( e.target.parentElement.name.substr( 3 ) ) {
					case "rename":
						self.showRenameFileDialog( item.name );
						break;
					case "extract":
						self.showExtractFileDialog( item.name );
						break;
					case "edit":
						self.editFile( item.name );
						break;
					case "delete":
						self.showDeleteDialog( item );
						break;
					case "copymove":
						self.showCopyMoveDialog( item );
						break;
				}
			}
		});
		// has to be jquery, since this is a bootstrap feature
		$( 'a[data-toggle="tooltip"]' ).tooltip({
			title: function() {
				var item = self.variantCache.find( x => x.guid == $(this).attr('id') );
				var tooltip = document.createElement( 'img' );
				if( self.config.isDocroot )
					tooltip.src = encodeURI( self.pathCombine( self.currentDir, item.name ) ).replace( '#', '%23' ).replace( '?', '%3F' );
				else
					tooltip.src = self.api + "?api=proxy&dir=" + encodeURIComponent( self.currentDir ) + "&filename=" + encodeURIComponent( item.name );
				tooltip.classList.add( 'imgpreview' );
				return tooltip;
			},
			animated: 'fade',
			placement: 'right',
			html: true
		});

		if( self.config.contextmenu && !!( self.config.edit || self.config.extract || self.config.rename || self.config.copymove || self.config.download || self.config.delete ) ) {
			// create the context menu, this also uses jquery, AFAIK
			var contextMenu = new BootstrapMenu( '.clickable-variant-row', {
				fetchElementData: function( row ) {
					var data = {};
					data.selected =
						Array.prototype.slice.call( document.getElementsByClassName( 'selectedItem' ) )
						.map( function(e){ return self.variantCache.find( x => x.guid == e.children[0].children[0].id ); } );
					data.clicked = self.variantCache.find( x => x.guid == row[0].children[0].children[0].id );
					return data;
				},
				actionsGroups:[
					['view'],
					['new', 'edit', 'rename', 'duplicate'],
					['delete']
				],
				actions: {
					view: {
						name: self.i18n.view,
						onClick: async function( data ) {
							let currModel = await i2ModelBuilder.getModelByPath(self.currentDir);
							window.location = "editor.html?variantid="+data.clicked.variantid+"&modelid="+currModel.getID();
						},
						iconClass: "icon icon-search"
					},
					new: {
						name: self.i18n.new,
						onClick: function( data ) {
							self.showNewVariantDialog();
						},
						iconClass: "icon icon-plus"
					},
					edit: {
						name: self.i18n.edit,
						onClick: async function( data ) {
							let currModel = await i2ModelBuilder.getModelByPath(self.currentDir);
							window.location = "editor.html?variantid="+data.clicked.variantid+"&modelid="+currModel.getID();
						},
						iconClass: "icon icon-pencil"
					},
					rename: {
						name: self.i18n.rename,
						onClick: function( data ) {
							self.showRenameVariantDialog(data.clicked);
						},
						iconClass: "icon icon-terminal"
					},
					duplicate: {
						name: function( data ) {
							if( data.selected.length > 0 )
								return self.i18n.duplicate+' <span class="badge">'+data.selected.length+'</span>';
							else
								return self.i18n.duplicate;
						},
						onClick: function( data ) {
							if( data.selected.length > 0 ) {
								self.duplicateVariants(data.selected);
							} else {
								self.duplicateVariants(data.clicked);
							}
						},
						iconClass: "icon icon-folder-empty"
					},
					delete: {
						name: function( data ) {
							if( data.selected.length > 0 )
								return self.i18n.delete+' <span class="badge">'+data.selected.length+'</span>';
							else
								return self.i18n.delete;
						},
						onClick: function( data ) {
							if( data.selected.length > 0 ) {
								self.showDeleteVariantDialog( data.selected );
							} else {
								self.showDeleteVariantDialog( data.clicked );
							}
						},
						iconClass: "icon icon-trash"
					}
				}
			});
		}
	};

	/**
	 * Changes the current directory
	 *
	 * @param string newdir - target directory
	 * @param object options - options for changing the directory
	 */
	this.changeDirectory = function( newdir, options ) {
		options = options || {};
		config = { absolute: false, pushState: true };
		jQuery.extend( config, options );
		if( ! config.absolute ) newdir = self.pathCombine( self.currentDir, newdir );
		$.ajax({
			url: self.api,
			type: "POST",
			data: ({
				api: "getRealpath",
				dir: newdir
			}),
			dataType: "json",
			success: function( data ) {
				self.currentDir = data.realpath;
				if(self.currentDir == "") {
					$("#variantcontainer").css("display", "none");
					$("#upload").css("display", "none");
					$("#modeltoolscontainer").css("display", "block");
				} else {
					$("#variantcontainer").css("display", "block");
					$("#upload").css("display", "block");
					$("#modeltoolscontainer").css("display", "none");
				}
				self.refreshFileTable();
				self.refreshVariantTable();
				$( "#currentDir" ).val( self.currentDir );
				if( config.pushState ) history.pushState( { dir: self.currentDir }, self.currentDir, "#"+encodeURIComponent( self.currentDir ) );
			},
			error: function() { self.showMessage( self.i18n.general_error, "e" ); }
		});
	};

	/**
	 * Shows a file, either a new file or an existing
	 */
	this.showFileDialog = function () {
		var filename = arguments.length > 0 ? arguments[0] : "newfile.txt";
		var content = arguments.length > 1 ? arguments[1] : "";
		self.showModal( Mustache.render( self.templates.file, { filename: filename, i18n: self.i18n } ), { large: true } );

		var form = document.getElementById( 'formFile' );
		form.addEventListener( 'keypress', function( e ) {
			if( e.target.name == 'filename' && e.key == 'Enter' )
				e.preventDefault();
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == "buttonSave" ) {
				e.preventDefault();
				self.saveFile( document.querySelector( '#formFile input[name=filename]' ).value, self.editor.getValue() );
				self.isModalClosedByButton = true;
				self.hideModal();
			} else if( e.target.id == "buttonSaveNotClose" ) {
				e.preventDefault();
				self.saveFile( document.querySelector( '#formFile input[name=filename]' ).value, self.editor.getValue() );
			} else if( e.target.id == "buttonClose" ) {
				e.preventDefault();
				self.isModalClosedByButton = true;
				self.hideModal();
			}
		});

		$('#editoroptions').popover({
			html: true,
			title: self.i18n.options,
			content: function() {
				// see https://github.com/twbs/bootstrap/issues/12571
				var ihatethisfuckingpopoverworkaround = $('#editoroptions').data('bs.popover');
				ihatethisfuckingpopoverworkaround.$tip.find( '.popover-content' ).empty();

				var aceSession = self.editor.getSession();
				var content = self.getNodesFromString(
					Mustache.render(
						self.templates.file_editoroptions,
						{
							wordwrap: ( aceSession.getOption( 'wrap' ) == 'off' ? false : true ),
							softtabs: aceSession.getOption( 'useSoftTabs' ),
							tabsize: aceSession.getOption( 'tabSize' ),
							ace_includes: self.ace,
							ace_mode_selected: function() {
								return ( aceSession.$modeId == "ace/mode/"+this ) ? 'selected="selected"' : '';
							},
							i18n: self.i18n
						}
					)
				);
				content.forEach( function( el ) {
					if( el.id == "editor-wordwrap" )
						el.addEventListener( 'change', function( e ) {
							self.editor.setOption( 'wrap', e.srcElement.checked );
						});
					else if( el.id == "editor-softtabs" )
						el.addEventListener( 'change', function( e ) {
							self.editor.setOption( 'useSoftTabs', e.srcElement.checked );
						});
					else if( el.lastChild && el.lastChild.id == "editor-tabsize" )
						el.lastChild.addEventListener( 'keydown', function( e ) {
							if( e.key == 'Enter' ) {
								e.preventDefault();
								self.editor.setOption( 'tabSize', e.srcElement.value );
							}
						});
					else if( el.id == "editor-syntax" )
						el.addEventListener( 'change', function( e ) {
							self.editor.getSession().setMode( e.target.value );
						});
				});
				return content;
			}
		});

		// Start ACE
		self.editor = ace.edit("content");
		self.editor.$blockScrolling = 'Infinity';
		self.editor.getSession().setValue(content);
		self.editor.focus();
		self.editor.on("change", function() { self.fileChanged = true; });
		if( self.ace && self.inArray( "ext-modelist", self.ace.files ) ) {
			var mode = ace.require( "ace/ext/modelist" ).getModeForPath( filename ).mode;
			if( self.inArray( mode, self.ace.modes.map( x => "ace/mode/"+x ) ) )
				self.editor.getSession().setMode( mode );
		}
	};

	/**
	 * Saves a file
	 */
	this.saveFile = function( filename, content ) {
		$.ajax({
			url: self.api,
			type: "POST",
			data: ({
				api: "saveFile",
				dir: self.currentDir,
				filename: filename,
				content: content
			}),
			dataType: "json",
			success: function( data ) {
						if( data.status == "OK" ) {
							self.showMessage( self.i18n.file_save_success, "s" );
							self.refreshFileTable();
						} else self.showMessage( self.i18n.file_save_error + data.message, "e" );
					},
			error: function() { self.showMessage( self.i18n.general_error, "e" ); }
		});
		self.fileChanged = false;
	};

	/**
	 * Edit a file
	 *
	 * @params string name - name of the file
	 */
	this.editFile = function( filename ) {
		$.ajax({
			url: self.api,
			type: "POST",
			dataType: "json",
			data: ({
				api: "getContent",
				dir: self.currentDir,
				filename: filename
			}),
			success: function( data ) {
						if( data.status == "OK" && data.data.content != null ) {
							self.showFileDialog( data.data.filename, data.data.content );
						}
						else if( data.status == "OK" && data.data.content == null ) {
							self.showMessage( self.i18n.file_load_error, "e" );
						}
						else self.showMessage( self.i18n.error +data.message, "e" );
					},
			error: function() { self.showMessage( self.i18n.file_display_error, "e" ); }
		});
	};

	/**
	 * Shows the create directory dialog
	 */
	this.showCreateDirDialog = function() {
		self.showModal( Mustache.render( self.templates.createdir, { i18n: self.i18n } ) );
		var form = document.forms.formCreateDir;
		form.elements.dirname.addEventListener( 'keypress', function( e ) {
			if(e.key == 'Enter' ) {
				e.preventDefault();
				self.createDir( e.target.value );
				self.hideModal();
			}
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonSave' ) {
				e.preventDefault();
				self.createDir( form.elements.dirname.value );
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		}, false );
	};

	/**
	 * Create a directory
	 */
	this.createDir = async function( dirname ) {
		let newModel = await i2ModelBuilder.createNewModel();
		newModel.setName(dirname);
		await newModel.save();
		$.ajax({
			url: self.api,
			type: "POST",
			data: ({
				api: "createDir",
				dir: self.currentDir,
				dirname: newModel.getPath()
			}),
			dataType: "json",
			success: function( data ){
				if( data.status == "OK" ) {
					self.showMessage( self.i18n.folder_create_success, "s" );
					self.refreshFileTable();
				}
				else {
					self.showMessage( self.i18n.folder_create_error +data.message, "e" );
				}
			},
			error: function() { self.showMessage( self.i18n.general_error, "e" ); }
		});
	};

	/**
	 * Shows the delete dialog
	 */
	this.showDeleteDialog = function( items ) {
		self.showModal(	Mustache.render( self.templates.deletefile, {
			multiple: ( items.length > 1 ),
			count: items.length,
			filename: ( Array.isArray( items ) ? items[0].name : items.name ),
			i18n: self.i18n
		}));
		var form = document.forms.formDeleteFiles;
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonYes' ) {
				e.preventDefault();
				self.deleteFiles( items );
				self.hideModal();
			} else if( e.target.id == 'buttonNo' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Deletes files
	 *
	 * @params {array} items - array with objects from the fileCache
	 */
	this.deleteFiles = function( items ) {
		if( ! Array.isArray( items ) )
			items = [items];
		$.ajax({
			url: self.api,
			type: "POST",
			data: ({
				api: "delete",
				dir: self.currentDir,
				filenames: items.map( function( e ){ return e.name; } )
			}),
			dataType: "json",
			success: function( data ) {
						if( data.status == "OK" ) {
							if(self.currentDir == "") {
								var paths = [];
								for(var d in items) {
									paths.push(items[d].name);
								}
								paths.forEach(async (p) => {
									let delModel = await i2ModelBuilder.getModelByPath(p);
									await delModel.delete();
									self.showMessage( self.i18n.model_delete_success, "s" );
									self.refreshFileTable();
								})
							} else {
								self.showMessage( self.i18n.file_delete_success, "s" );
								self.refreshFileTable();
							}
						} else self.showMessage( self.i18n.file_delete_error, "e" );
					},
			error: function() { self.showMessage( self.i18n.general_error, "e" ); }
		});
	};

	/**
	 * Duplicate variants
	 *
	 * @params {array} items - array with objects from the variantCache
	 */
	this.duplicateVariants = function( variants ) {
		if( ! Array.isArray( variants ) )
			variants = [variants];
		variants.forEach(async(variant) => {
			let newVariant = await i2VariantBuilder.createNewVariant();
			let newVariantID = newVariant.getID();
			newVariant.data = variant.data;
			newVariant.setID(newVariantID);
			newVariant.setName(newVariant.getName() + " - copy");
			newVariant.save();
			self.refreshVariantTable();
		});
	};

	/**
	 * Shows the delete variant dialog
	 */
	this.showDeleteVariantDialog = function( items ) {
		self.showModal(	Mustache.render( self.templates.deletevariant, {
			multiple: ( items.length > 1 ),
			count: items.length,
			variantname: ( Array.isArray( items ) ? items[0].name : items.name ),
			i18n: self.i18n
		}));
		var form = document.forms.formDeleteVariants;
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonYes' ) {
				e.preventDefault();
				self.deleteVariants( items );
				self.hideModal();
			} else if( e.target.id == 'buttonNo' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Deletes variants
	 *
	 * @params {array} items - array with objects from the variantCache
	 */
	this.deleteVariants = function( variants ) {
		if( ! Array.isArray( variants ) )
			variants = [variants];
		variants.forEach(async(variant) => {
			await variant.delete();
			self.refreshVariantTable();
		});
	};

	/**
	 * Show the rename file dialog
	 *
	 * @params string name - name of the file
	 */
	this.showRenameFileDialog = function( filename ) {
		self.showModal( Mustache.render( self.templates.renamefile, { filename: filename, i18n: self.i18n } ) );
		var form = document.forms.formRenameFile;
		form.elements.newname.addEventListener( 'keypress', function( e ) {
			if( e.key == 'Enter' ) {
				e.preventDefault();
				self.renameFile( filename, e.target.value );
				self.hideModal();
			}
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonRename' ) {
				e.preventDefault();
				self.renameFile( filename, form.elements.newname.value );
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Show the rename variant dialog
	 *
	 * @params variantid - variant id
	 */
	this.showRenameVariantDialog = function( variant ) {
		self.showModal( Mustache.render( self.templates.renamevariant, { variantname: variant.name, i18n: self.i18n } ) );
		var form = document.forms.formRenameVariant;
		form.elements.newname.addEventListener( 'keypress', function( e ) {
			if( e.key == 'Enter' ) {
				e.preventDefault();
				self.renameVariant(variant, form.elements.newname.value);
				self.hideModal();
			}
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonRename' ) {
				e.preventDefault();
				self.renameVariant(variant, form.elements.newname.value);
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Show the rename variant dialog
	 *
	 * @params variantid - variant id
	 */
	this.showNewVariantDialog = function() {
		let currentModelPromise = i2ModelBuilder.getModelByPath(self.currentDir);
		currentModelPromise.then((currentModel) => {
			self.showModal( Mustache.render( self.templates.newvariant, { i18n: self.i18n } ) );
			var form = document.forms.formNewVariant;
			form.elements.newname.addEventListener( 'keypress', function( e ) {
				if( e.key == 'Enter' ) {
					e.preventDefault();
					self.newVariant(currentModel, form.elements.newname.value);
					self.hideModal();
				}
			});
			form.addEventListener( 'click', function( e ) {
				if( e.target.id == 'buttonRename' ) {
					e.preventDefault();
					self.newVariant(currentModel, form.elements.newname.value);
					self.hideModal();
				} else if( e.target.id == 'buttonCancel' ) {
					e.preventDefault();
					self.hideModal();
				}
			});
		});
	};

	/**
	 * Renames a file
	 *
	 * @params string name - name of the file
	 */
	this.renameFile = function( filename, newname ) {
		$.ajax({
			url: ifm.api,
			type: "POST",
			data: ({
				api: "rename",
				dir: ifm.currentDir,
				filename: filename,
				newname: newname
			}),
			dataType: "json",
			success: function(data) {
						if(data.status == "OK") {
							ifm.showMessage( self.i18n.file_rename_success, "s");
							ifm.refreshFileTable();
						} else ifm.showMessage( self.i18n.file_rename_error +data.message, "e");
					},
			error: function() { ifm.showMessage( self.i18n.general_error, "e"); }
		});
	};

	/**
	 * Renames a variant
	 *
	 * @params string name - name of the variant
	 */
	this.renameVariant = async function( variant, name ) {
		variant.setName(name);
		await variant.save();
		self.refreshVariantTable();
	};

	/**
	 * New variant
	 *
	 * @params string name - name of the variant
	 */
	this.newVariant = async function( model, name ) {
		let newVariant = await i2VariantBuilder.createNewVariant();
		newVariant.setName(name);
		newVariant.addModelID(model.getID());
		await newVariant.save();
		this.refreshVariantTable();
	};

	/**
	 * Show the copy/move dialog
	 *
	 * @params string name - name of the file
	 */
	this.showCopyMoveDialog = function( items ) {
		self.showModal( Mustache.render( self.templates.copymove, { i18n: self.i18n } ) );
		$.ajax({
			url: self.api,
			type: "POST",
			data: ({
				api: "getFolders"
			}),
			dataType: "json",
			success: function( data ) {
				$( '#copyMoveTree' ).treeview({
					data: data,
					levels: 1,
					expandIcon: "icon icon-folder-empty",
					emptyIcon: "icon icon-folder-empty",
					collapseIcon: "icon icon-folder-open-empty",
					loadingIcon: "icon icon-spin5",
					lazyLoad: function( n, cb ) {
						$.ajax({
							url: self.api,
							type: "POST",
							data: {
								api: "getFolders",
								dir: n.dataAttr.path
							},
							dataType: "json",
							success: cb
						});
					}
				});
			},
			error: function() { self.hideModal(); self.showMessage( self.i18n.folder_tree_load_error, "e" ) }
		});
		var form = document.forms.formCopyMove;
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'copyButton' ) {
				e.preventDefault();
				self.copyMove( items, form.getElementsByClassName( 'node-selected' )[0].dataset.path, 'copy' );
				self.hideModal();
			} else if( e.target.id == 'moveButton' ) {
				e.preventDefault();
				self.copyMove( items, form.getElementsByClassName( 'node-selected' )[0].dataset.path, 'move' );
				self.hideModal();
			} else if( e.target.id == 'cancelButton' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Copy or moves a file
	 * 
	 * @params {string} sources - array of fileCache items
	 * @params {string} destination - target directory
	 * @params {string} action - action (copy|move)
	 */
	this.copyMove = function( sources, destination, action ) {
		if( ! Array.isArray( sources ) )
			sources = [sources];
		var id = self.generateGuid();
		self.task_add( { id: id, name: self.i18n[action] + " " + ( sources.length > 1 ? sources.length : sources[0].name ) + " " + self.i18n.file_copy_to + " " + destination } );
		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				dir: self.currentDir,
				api: "copyMove",
				action: action,
				filenames: sources.map( function( e ) { return e.name } ),
				destination: destination
			},
			dataType: "json",
			success: function( data ) {
				if( data.status == "OK" ) {
					self.showMessage( data.message, "s" );
				} else {
					self.showMessage( data.message, "e" );
				}
				self.refreshFileTable();
			},
			error: function() {
				self.showMessage( self.i18n.general_error, "e" );
			},
			complete: function() {
				self.task_done( id );
			}
		});
	};

	/**
	 * Shows the extract file dialog
	 *
	 * @param {string} filename - name of the file
	 */
	this.showExtractFileDialog = function( filename ) {
		var targetDirSuggestion = ( filename.lastIndexOf( '.' ) > 1 ) ? filename.substr( 0, filename.lastIndexOf( '.' ) ) : filename;
		self.showModal( Mustache.render( self.templates.extractfile, { filename: filename, destination: targetDirSuggestion, i18n: self.i18n } ) );
		var form = document.forms.formExtractFile;
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonExtract' ) {
				e.preventDefault();
				var loc = form.elements.extractTargetLocation.value;
				self.extractFile( filename, ( loc == "custom" ? form.elements.extractCustomLocation.value : loc ) ); 
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
		form.elements.extractCustomLocation.addEventListener( 'keypress', function( e ) {
			var loc = form.elements.extractTargetLocation.value;
			if( e.key == 'Enter' ) {
				e.preventDefault();
				self.extractFile( filename, ( loc == "custom" ? form.elements.extractCustomLocation.value : loc ) );
				self.hideModal();
			}
		});
		form.elements.extractCustomLocation.addEventListener( 'focus', function( e ) {
			form.elements.extractTargetLocation.value = 'custom';
		});
	};

	/**
	 * Extracts a file
	 *
	 * @param string filename - name of the file
	 * @param string destination - name of the target directory
	 */
	this.extractFile = function( filename, destination ) {
		var id = self.generateGuid();
		self.task_add( { id: id, name: "extract "+filename } );
		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				api: "extract",
				dir: self.currentDir,
				filename: filename,
				targetdir: destination
			},
			dataType: "json",
			success: function( data ) {
						if( data.status == "OK" ) {
							self.showMessage( data.message, "s" );
							self.refreshFileTable();
						} else self.showMessage( data.message, "e" );
					},
			error: function() { self.showMessage( self.i18n.general_error, "e" ); },
			complete: function() { self.task_done( id ); }
		});
	};

	/**
	 * Shows the upload file dialog
	 */
	this.showUploadFileDialog = function() {
		self.showModal( Mustache.render( self.templates.uploadfile, { i18n: self.i18n } ) );
		var form = document.forms.formUploadFile;
		form.elements.files.addEventListener( 'change', function( e ) {
			if( e.target.files.length > 1 )
				form.elements.newfilename.readOnly = true;
			else 
				form.elements.newfilename.readOnly = false;
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonUpload' ) {
				e.preventDefault();
				var files = Array.prototype.slice.call( form.elements.files.files );
				if( files.length > 1 )
					files.forEach( function( file ) {
						self.uploadFile( file );
					});
				else
					self.uploadFile( files[0], form.elements.newfilename.value );
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Uploads a file
	 */
	this.uploadFile = function( file, newfilename ) {
		var data = new FormData();
 		data.append( 'api', 'upload' );
 		data.append( 'dir', self.currentDir );
 		data.append( 'file', file );
		if( newfilename )
			data.append( 'newfilename', newfilename );
		var id = self.generateGuid();
		$.ajax({
			url: self.api,
			type: "POST",
			data: data,
			processData: false,
			contentType: false,
			dataType: "json",
			xhr: function(){
				var xhr = $.ajaxSettings.xhr() ;
				xhr.upload.onprogress = function(evt){ self.task_update(evt.loaded/evt.total*100,id); } ;
				xhr.upload.onload = function(){ self.log('Uploading '+file.name+' done.') } ;
				return xhr ;
			},
			success: function(data) {
				if(data.status == "OK") {
					self.showMessage( self.i18n.file_upload_success, "s");
					if(data.cd == self.currentDir) self.refreshFileTable();
				} else self.showMessage( data.message, "e");
			},
			error: function() { self.showMessage( self.i18n.general_error, "e"); },
			complete: function() { self.task_done(id); }
		});
		self.task_add( { id: id, name: "Upload " + file.name } );
	};

	/**
	 * Change the permissions of a file
	 *
	 * @params object e - event object
	 * @params string name - name of the file
	 */
	this.changePermissions = function( filename, newperms ) {
		$.ajax({
			url: self.api,
			type: "POST",
			data: ({
				api: "changePermissions",
				dir: self.currentDir,
				filename: filename,
				chmod: newperms
			}),
			dataType: "json",
			success: function( data ){
				if( data.status == "OK" ) {
					self.showMessage( self.i18n.permission_change_success, "s" );
					self.refreshFileTable();
				}
				else {
					self.showMessage( data.message, "e");
				}
			},
			error: function() { self.showMessage( self.i18n.general_error, "e"); }
		});
	};

	/**
	 * Show the remote upload dialog
	 */
	this.showRemoteUploadDialog = function() {
		self.showModal( Mustache.render( self.templates.remoteupload, { i18n: self.i18n } ) );
		var form = document.forms.formRemoteUpload;
		var urlChangeHandler = function( e ) {
			form.elements.filename.value = e.target.value.substr( e.target.value.lastIndexOf( '/' ) + 1 );
		};
		form.elements.url.addEventListener( 'keypress', self.preventEnter );
		form.elements.url.addEventListener( 'change', urlChangeHandler );
		form.elements.url.addEventListener( 'keyup', urlChangeHandler );
		form.elements.filename.addEventListener( 'keypress', self.preventEnter );
		form.elements.filename.addEventListener( 'keyup', function( e ) {
			form.elements.url.removeEventListener( 'change', urlChangeHandler );
			form.elements.url.removeEventListener( 'keyup', urlChangeHandler );
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonUpload' ) {
				e.preventDefault();
				self.remoteUpload( form.elements.url.value, form.elements.filename.value, form.elements.method.value );
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Remote uploads a file
	 */
	this.remoteUpload = function( url, filename, method ) {
		var id = ifm.generateGuid();
		$.ajax({
			url: ifm.api,
			type: "POST",
			data: ({
				api: "remoteUpload",
				dir: ifm.currentDir,
				filename: filename,
				method: method,
				url: url
			}),
			dataType: "json",
			success: function(data) {
				if(data.status == "OK") {
					self.showMessage( self.i18n.file_upload_success, "s" );
					self.refreshFileTable();
				} else
					self.showMessage( self.i18n.file_upload_error + data.message, "e" );
			},
			error: function() { self.showMessage( self.i18n.general_error, "e"); },
			complete: function() { self.task_done(id); }
		});
		self.task_add( { id: id, name: self.i18n.upload_remote+" "+filename } );
	};

	/**
	 * Shows the ajax request dialog
	 */
	this.showAjaxRequestDialog = function() {
		self.showModal( Mustache.render( self.templates.ajaxrequest, { i18n: self.i18n } ) );
		var form = document.forms.formAjaxRequest;
		form.elements.ajaxurl.addEventListener( 'keypress', self.preventEnter );
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonRequest' ) {
				e.preventDefault();
				self.ajaxRequest( form.elements.ajaxurl.value, form.elements.ajaxdata.value.replace( /\n/g, '&' ), form.elements.arMethod.value );
			} else if( e.target.id == 'buttonClose' ) {
				e.preventDefault();
				self.hideModal();
			}
		});
	};

	/**
	 * Performs an ajax request
	 */
	this.ajaxRequest = function( url, data, method ) {
		$.ajax({
			url	: url,
			cache	: false,
			data	: data,
			type    : method,
			success	: function( response ) { document.getElementById( 'ajaxresponse' ).innerText = response; },
			error	: function(e) { self.showMessage("Error: "+e, "e"); self.log(e); }
		});
	};

	/**
	 * Shows the search dialog
	 */
	this.showSearchDialog = function() {
		self.showModal( Mustache.render( self.templates.search, { lastSearch: self.search.lastSearch, i18n: self.i18n } ) );

		var updateResults = function( data ) {
			self.log( 'updated search results' );
			var searchresults = document.getElementById( 'searchResults' );
			if( searchresults.tBodies[0] ) searchresults.tBodies[0].remove();
			searchresults.appendChild( document.createElement( 'tbody' ) );
			searchresults.tBodies[0].innerHTML = Mustache.render( self.templates.searchresults, { items: self.search.data } );
			searchresults.tBodies[0].addEventListener( 'click', function( e ) {
				if( e.target.classList.contains( 'searchitem' ) || e.target.parentElement.classList.contains( 'searchitem' ) ) {
					e.preventDefault();
					self.changeDirectory( self.pathCombine( self.search.data.currentDir, e.target.dataset.folder || e.target.parentElement.dataset.foldera ), { absolute: true } );
					self.hideModal();
				}
			});
			searchresults.tBodies[0].addEventListener( 'keypress', function( e ) {
				if( e.target.classList.contains( 'searchitem' ) || e.target.parentElement.classList.contains( 'searchitem' ) ) {
					e.preventDefault();
					e.target.click();
				}
			});
		};

		updateResults( self.search.data );

		document.getElementById( 'searchPattern' ).addEventListener( 'keypress', function( e ) {
			if( e.key == 'Enter' ) {
				e.preventDefault();
				if( e.target.value.trim() === '' ) return;
				document.getElementById( 'searchResults' ).tBodies[0].innerHTML = '<tr><td style="text-align:center;"><span class="icon icon-spin5 animate-spin"></span></td></tr>';
				self.search.lastSearch = e.target.value;
				$.ajax({
					url: self.api,
					type: "POST",
					data: {
						api: "searchItems",
						dir: self.currentDir,
						pattern: e.target.value
					},
					dataType: "json",
					success: function( data ) {
						if( data.status == 'ERROR' ) {
							self.hideModal();
							self.showMessage( data.message, "e" );
						} else {
							data.forEach( function(e) {
								e.folder = e.name.substr( 0, e.name.lastIndexOf( '/' ) );
								e.linkname = e.name.substr( e.name.lastIndexOf( '/' ) + 1 );
							});
							self.search.data = data;
							if( self.search.data ) self.search.data.currentDir = self.currentDir;
							updateResults( data );
						}
					}
				});
			}
		});
	};

	/**
	 * Shows the create archive dialog
	 */
	this.showCreateArchiveDialog = function( items ) {
		self.showModal( Mustache.render( self.templates.createarchive, { i18n: self.i18n } ) );

		var form = document.forms.formCreateArchive;
		form.elements.archivename.addEventListener( 'keypress', function( e ) {
			if( e.key == 'Enter' ) {
				e.preventDefault();
				self.createArchive( items, e.target.value );
				self.hideModal();
			}
		});
		form.addEventListener( 'click', function( e ) {
			if( e.target.id == 'buttonSave' ) {
				e.preventDefault();
				self.createArchive( items, form.elements.archivename.value );
				self.hideModal();
			} else if( e.target.id == 'buttonCancel' ) {
				e.preventDefault();
				self.hideModal();
			}
		}, false );
	};

	this.createArchive = function( items, archivename ) {
		var type = "";
		if( archivename.substr( -3 ).toLowerCase() == "zip" )
			type = "zip";
		else if( archivename.substr( -3 ).toLowerCase() == "tar" )
			type = "tar";
		else if( archivename.substr( -6 ).toLowerCase() == "tar.gz" )
			type = "tar.gz";
		else if( archivename.substr( -7 ).toLowerCase() == "tar.bz2" )
			type = "tar.bz2";
		else {
			self.showMessage( self.i18n.invalid_archive_format, "e" );
			return;
		}
		var id = self.generateGuid();
		self.task_add( { id: id, name: self.i18n.create_archive+" "+archivename } );

		if( ! Array.isArray( items ) )
			items = [items];

		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				api: "createArchive",
				dir: self.currentDir,
				archivename: archivename,
				filenames: items.map( function( e ) { return e.name; } ),
				format: type
			},
			dataType: "json",
			success: function( data ) {
				console.log( data );
				if( data.status == "OK" ) {
					self.showMessage( data.message, "s" );
					self.refreshFileTable();
				} else
					self.showMessage( data.message, "e" );
			},
			error: function() { self.showMessage( self.i18n.general_error, "e" ); },
			complete: function() { self.task_done( id ); }
		});
	};

	// --------------------
	// helper functions
	// --------------------

	/**
	 * Shows a notification
	 *
	 * @param string m - message text
	 * @param string t - message type (e: error, s: success)
	 */
	this.showMessage = function(m, t) {
		var msgType = ( t == "e" ) ? "danger" : ( t == "s" ) ? "success" : "info";
		var element = ( self.config.inline ) ? self.rootElement : "body";
		$.notify(
			{ message: m },
			{ type: msgType, delay: 3000, mouse_over: 'pause', offset: { x: 15, y: 65 }, element: element }
		);
	};

	/**
	 * Combines two path components
	 *
	 * @param {string} a - component 1
	 * @param {string} b - component 2
	 * @returns {string} - combined path
	 */
	this.pathCombine = function(a, b) {
		if ( a == "" && b == "" )
			return "";
		if( b[0] == "/" )
			b = b.substring(1);
		if( a == "" )
			return b;
		if( a[a.length-1] == "/" )
			a = a.substring(0, a.length-1);
		if( b == "" )
			return a;
		return a+"/"+b;
	};

	/**
	 * Prevents a user to submit a form via clicking enter
	 *
	 * @param object e - click event
	 */
	this.preventEnter = function(e) {
		if( e.key == 'Enter' )
			e.preventDefault();
	};

	/**
	 * Checks if an element is part of an array
	 *
	 * @param {object} needle - search item
	 * @param {array} haystack - array to search
	 * @returns {boolean}
	 */
	this.inArray = function(needle, haystack) {
		for( var i = 0; i < haystack.length; i++ )
			if( haystack[i] == needle )
				return true;
		return false;
	};

	this.getNodeFromString = function( s ) {
		var template = document.createElement( 'template');
		template.innerHTML = s;
		return template.content.childNodes[0];
	};

	this.getNodesFromString = function( s ) {
		var template = document.createElement( 'template');
		template.innerHTML = s;
		return template.content.childNodes;
	};

	/**
	 * Adds a task to the taskbar.
	 *
	 * @param object task - description of the task: { id: "guid", name: "Task Name", type: "(info|warning|danger|success)" }
	 */
	this.task_add = function( task ) {
		if( ! task.id ) {
			self.log( "Error: No task id given.");
			return false;
		}
		if( ! document.querySelector( "footer" ) ) {
			var newFooter = self.getNodeFromString( Mustache.render( self.templates.footer, { i18n: self.i18n } ) );
			newFooter.addEventListener( 'click', function( e ) {
				if( e.target.name == 'showAll' ) {
					if( newFooter.style.maxHeight == '80%' ) {
						newFooter.style.maxHeight = '6em';
						newFooter.style.overflow = 'hidden';
					} else {
						newFooter.style.maxHeight = '80%';
						newFooter.style.overflow = 'scroll';
					}
				}
			});
			document.body.appendChild( newFooter );
			document.body.style.paddingBottom = '6em';
		}
		task.id = "wq-"+task.id;
		task.type = task.type || "info";
		var wq = document.getElementById( 'waitqueue' );
		wq.prepend( self.getNodeFromString( Mustache.render( self.templates.task, task ) ) );
		document.getElementsByName( 'taskCount' )[0].innerText = wq.children.length;
	};

	/**
	 * Removes a task from the taskbar
	 *
	 * @param string id - task identifier
	 */
	this.task_done = function( id ) {
		document.getElementById( 'wq-' + id ).remove();
		var wq = document.getElementById( 'waitqueue' );
		if( wq.children.length == 0 ) {
			document.getElementsByTagName( 'footer' )[0].remove();
			document.body.style.paddingBottom = 0;
		} else {
			document.getElementsByName( 'taskCount' )[0].innerText = wq.children.length;
		}
	};

	/**
	 * Updates a task
	 *
	 * @param integer progress - percentage of status
	 * @param string id - task identifier
	 */
	this.task_update = function( progress, id ) {
		var progbar = document.getElementById( 'wq-'+id ).getElementsByClassName( 'progress-bar' )[0];
		progbar.style.width = progress+'%';
		progbar.setAttribute( 'aria-valuenow', progress );
	};

	/**
	 * Highlights an item in the file table
	 *
	 * @param object param - either an element id or a jQuery object
	 */
	this.highlightItem = function( direction ) {
		var highlight = function( el ) {
			[].slice.call( el.parentElement.children ).forEach( function( e ) {
				e.classList.remove( 'highlightedItem' );
			});
			el.classList.add( 'highlightedItem' );
			el.firstElementChild.firstElementChild.focus();
			if( ! self.isElementInViewport( el ) ) {
				var scrollOffset =  0;
				if( direction=="prev" )
					scrollOffset = el.offset().top - ( window.innerHeight || document.documentElement.clientHeight ) + el.height() + 15;
				else
					scrollOffset = el.offset().top - 55;
				$('html, body').animate( { scrollTop: scrollOffset }, 200 );
			}
		};


		var highlightedItem = document.getElementsByClassName( 'highlightedItem' )[0];
		if( ! highlightedItem ) {
			if( document.activeElement.classList.contains( 'ifmitem' ) )
				highlight( document.activeElement.parentElement.parentElement );
			else 
				highlight( document.getElementById( 'filetable' ).tBodies[0].firstElementChild );
		} else  {
			var newItem = ( direction=="next" ? highlightedItem.nextElementSibling : highlightedItem.previousElementSibling );
			if( newItem != null )
				highlight( newItem );
		}
	};

	/**
	 * Checks if an element is within the viewport
	 *
	 * @param object el - element object
	 */
	this.isElementInViewport = function( el ) {
		var rect = el.getBoundingClientRect();
		return (
				rect.top >= 80 &&
				rect.left >= 0 &&
				rect.bottom <= ( ( window.innerHeight || document.documentElement.clientHeight ) ) &&
				rect.right <= ( window.innerWidth || document.documentElement.clientWidth )
			   );
	};

	/**
	 * Generates a GUID
	 */
	this.generateGuid = function() {
		var result, i, j;
		result = '';
		for( j = 0; j < 20; j++ ) {
			i = Math.floor( Math.random() * 16 ).toString( 16 ).toUpperCase();
			result = result + i;
		}
		return result;
	};

	/**
	 * Logs a message if debug mode is active
	 *
	 * @param string m - message text
	 */
	this.log = function( m ) {
		if( self.config.debug ) {
			console.log( "IFM (debug): " + m );
		}
	};

	/**
	 * Encodes a string for use in the href attribute of an anchor.
	 *
	 * @param string s - decoded string
	 */
	this.hrefEncode = function( s ) {
		return s
			.replace( '%', '%25' )
			.replace( ';', '%3B' )
			.replace( '?', '%3F' )
			.replace( ':', '%3A' )
			.replace( '@', '%40' )
			.replace( '&', '%26' )
			.replace( '=', '%3D' )
			.replace( '+', '%2B' )
			.replace( '$', '%24' )
			.replace( ',', '%2C' )
			.replace( '<', '%3C' )
			.replace( '>', '%3E' )
			.replace( '#', '%23' )
			.replace( '"', '%22' )
			.replace( '{', '%7B' )
			.replace( '}', '%7D' )
			.replace( '|', '%7C' )
			.replace( '^', '%5E' )
			.replace( '[', '%5B' )
			.replace( ']', '%5D' )
			.replace( '`', '%60' )
			.replace( '\\', '%5C' )
		;
	};

	/**
	 * Handles the javascript onbeforeunload event
	 *
	 * @param object event - event object
	 */
	this.onbeforeunloadHandler = function( e ) {
		if( document.getElementById( 'waitqueue' ) ) {
			return self.i18n.remaining_tasks;
		}
	};

	/**
	 * Handles the javascript pop states
	 *
	 * @param object event - event object
	 */
	this.historyPopstateHandler = function( e ) {
		var dir = "";
		if( e.state && e.state.dir )
			dir = e.state.dir;
		self.changeDirectory( dir, { pushState: false, absolute: true } );
	};

	/**
	 * Handles keystrokes
	 *
	 * @param object e - event object
	 */
	this.handleKeystrokes = function( e ) {
		var isFormElement = function( el ) {
			do {
				if( self.inArray( el.tagName, ['INPUT', 'TEXTAREA'] ) ) {
					return true;
				}
			} while( ( el == el.parentElement ) !== false );
			return false;
		}

		if( isFormElement( e.target ) ) return;

		// global key events
		switch( e.key ) {
			case '/':
				e.preventDefault();
				self.showSearchDialog();
				break;
			case 'g':
				e.preventDefault();
				$('#currentDir').focus();
				return;
				break;
			case 'r':
				e.preventDefault();
				self.refreshFileTable();
				return;
				break;
			case 'u':
				if( self.config.upload ) {
					e.preventDefault();
					self.showUploadFileDialog();
				}
				return;
				break;
			case 'o':
				if( self.config.remoteupload ) {
					e.preventDefault();
					self.showRemoteUploadDialog();
				}
				return;
				break;
			case 'a':
				if( self.config.ajaxrequest ) {
					e.preventDefault();
					self.showAjaxRequestDialog();
				}
				return;
				break;
			case 'F':
				if( self.config.createfile ) {
					e.preventDefault();
					self.showFileDialog();
				}
				return;
				break;
			/*case 'D':
				if( self.config.createdir ) {
					e.preventDefault();
					self.showCreateDirDialog();
				}
				return;
				break;*/
			case 'h':
			case 'ArrowLeft':
			case 'Backspace':
				e.preventDefault();
				self.changeDirectory( '..' );
				return;
				break;
			case 'j':
			case 'ArrowDown':
				e.preventDefault();
				self.highlightItem('next');
				return;
				break;
			case 'k':
			case 'ArrowUp':
				e.preventDefault();
				self.highlightItem('prev');
				return;
				break;
		}

		// key events which need a highlighted item
		var element = document.getElementsByClassName( 'highlightedItem' )[0];
		if( element )
			item = self.fileCache.find( x => x.guid == element.children[0].children[0].id );
		else
			item = false;

		// Some operations do not work if the highlighted item is the parent
		// directory. In these cases the keybindings are ignored.
		var selectedItems = Array.prototype.slice.call( document.getElementsByClassName( 'selectedItem' ) )
			.map( function( e ) { return self.fileCache.find( x => x.guid === e.children[0].children[0].id ) } );

		switch( e.key ) {
			case 'Delete':
				if( self.config.delete )
					if( selectedItems.length > 0 ) {
						e.preventDefault();
						self.showDeleteDialog( selectedItems );
					} else if( item && item.name !== '..' )
						self.showDeleteDialog( item );
				return;
				break;
			case 'c':
			case 'm':
				if( self.config.copymove ) {
					if( selectedItems.length > 0 ) {
						e.preventDefault();
						self.showCopyMoveDialog( selectedItems );
					} else if( item && item.name !== '..' )
						self.showCopyMoveDialog( item );
				}
				return;
				break;
		}

		if( item )
			switch( e.key ) {
				case 'l':
				case 'ArrowRight':
					e.preventDefault();
					if( item.type == "dir" )
						self.changeDirectory( item.name );
					return;
					break;
				case 'Escape':
					if( element.children[0].children[0] == document.activeElement ) {
						e.preventDefault();
						element.classList.toggle( 'highlightedItem' );
					}
					return;
					break;
				case ' ':
				case 'Enter':
					if( element.children[0].children[0] == document.activeElement ) { 
						if( e.key == 'Enter' && element.classList.contains( 'isDir' ) ) {
							e.preventDefault();
							e.stopPropagation();
							self.changeDirectory( item.name );
						} else if( e.key == ' ' && item.name != ".." ) {
							e.preventDefault();
							e.stopPropagation();
							element.classList.toggle( 'selectedItem' );
						}
					}
					return;
					break;
				case 'e':
					if( self.config.edit && item.eaction == "edit" ) {
						e.preventDefault();
						self.editFile( item.name );
					} else if( self.config.extract && item.eaction == "extract" ) {
						e.preventDefault();
						self.showExtractFileDialog( item.name );
					}
					return;
					break;
				case 'n':
					e.preventDefault();
					if( self.config.rename ) {
						self.showRenameFileDialog( item.name );
					}
					return;
					break;
			}
	};

	/**
	 * Initializes the application
	 */
	this.initLoadConfig = function() {
		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				api: "getConfig"
			},
			dataType: "json",
			success: function(d) {
				self.config = d;
				if( self.config.ace_includes ) {
					self.ace = {};
					self.ace.files = self.config.ace_includes.split( '|' ).filter( x => x != "" );
					self.ace.modes = self.ace.files
						.filter( function(f){ if( f.substr(0,5)=="mode-" ) return f; } )
						.map( function(f){ return f.substr(5); } )
					self.ace.modes.unshift( "text" );
				}
				self.log( "configuration loaded" );
				self.initLoadTemplates();
			},
			error: function() {
				throw new Error( self.i18n.load_config_error );
			}
		});
	};
	
	this.initLoadTemplates = function() {
		// load the templates from the backend
		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				api: "getTemplates"
			},
			dataType: "json",
			success: function(d) {
				self.templates = d;
				self.log( "templates loaded" );
				self.initLoadI18N();
			},
			error: function() {
				throw new Error( self.i18n.load_template_error );
			}
		});
	};

	this.initLoadI18N = function() {
		// load I18N from the backend
		$.ajax({
			url: self.api,
			type: "POST",
			data: {
				api: "getI18N"
			},
			dataType: "json",
			success: function(d) {
				self.i18n = d;
				self.log( "I18N loaded" );
				self.initApplication();
			},
			error: function() {
				throw new Error( self.i18n.load_text_error );
			}
		});
	};
	
	this.initApplication = function() {
		self.rootElement.innerHTML = Mustache.render(
				self.templates.app,
				{
					showpath: "/",
					config: self.config,
					i18n: self.i18n,
					ftbuttons: function(){
						return ( self.config.edit || self.config.rename || self.config.delete || self.config.zipnload || self.config.extract );
					}
				});

		// bind static buttons
		document.getElementById( 'buttonNewVariant' ).onclick = function() {
			self.showNewVariantDialog();
		};
		document.getElementById( 'buttonNewModel' ).onclick = function() {
			self.showCreateDirDialog();
		};
		document.getElementById( 'refresh' ).onclick = function() { self.refreshFileTable(); };
		document.getElementById( 'search' ).onclick = function() { self.showSearchDialog(); };
		if( self.config.createfile )
			document.getElementById( 'createFile' ).onclick = function() { self.showFileDialog(); };
		//if( self.config.createdir )
		//	document.getElementById( 'createDir' ).onclick = function() { self.showCreateDirDialog(); };
		if( self.config.upload )
			document.getElementById( 'upload' ).onclick = function() { self.showUploadFileDialog(); };
		document.getElementById( 'currentDir' ).onkeypress = function( e ) {
			if( e.keyCode == 13 ) {
				e.preventDefault();
				self.changeDirectory( e.target.value, { absolute: true } );
				e.target.blur();
			}
		};
		if( self.config.remoteupload )
			document.getElementById( 'buttonRemoteUpload' ).onclick = function() { self.showRemoteUploadDialog(); };
		if( self.config.ajaxrequest )
			document.getElementById( 'buttonAjaxRequest' ).onclick = function() { self.showAjaxRequestDialog(); };
		if( self.config.upload )
			document.addEventListener( 'dragover', function( e ) {
				if( self.currentDir != "" && Array.prototype.indexOf.call(e.dataTransfer.types, "Files") != -1 ) {
					e.preventDefault();
					e.stopPropagation();
					var div = document.getElementById( 'filedropoverlay' );
					div.style.display = 'block';
					div.ondrop = function( e ) {
						e.preventDefault();
						e.stopPropagation();
						var files = e.dataTransfer.files;
						for( var i = 0; i < files.length; i++ ) {
							var ext = files[i].name.split('.').pop();
							if(self.allowedfiles.indexOf(ext) != -1) {
								self.uploadFile( files[i] );
							} else {
								self.showMessage( self.i18n.filetype_not_allowed + ": " + files[i].name, "e");
							}
						}
						if( e.target.id == 'filedropoverlay' )
							e.target.style.display = 'none';
						else if( e.target.parentElement.id == 'filedropoverlay' ) {
							e.target.parentElement.style.display = 'none';
						}
					};
					div.ondragleave = function( e ) {
						e.preventDefault();
						e.stopPropagation();
						if( e.target.id == 'filedropoverlay' )
							e.target.style.display = 'none';
					};
				} else {
					var div = document.getElementById( 'filedropoverlay' );
					if( div.style.display == 'block' )
						div.stye.display = 'none';
				}
			});

		// drag and drop of filetable items
		if( self.config.copymove ) {
			var isFile = function(e) { return Array.prototype.indexOf.call(e.dataTransfer.types, "Files") != -1 };
			document.addEventListener( 'dragstart', function( e ) {
				var selectedItems = document.getElementsByClassName( 'selectedItem' );
				var data;
				if( selectedItems.length > 0 ) 
					data = self.fileCache.filter(
							x => self.inArray(
								x.guid,
								[].slice.call( selectedItems ).map( function( e ) { return e.dataset.id; } )
								)
							);
				else 
					data = self.fileCache.find( x => x.guid === e.target.dataset.id );
				e.dataTransfer.setData( 'text/plain', JSON.stringify( data ) );
				var dragImage = document.createElement( 'div' );
				dragImage.style.display = 'inline';
				dragImage.style.padding = '10px';
				dragImage.innerHTML = '<span class="icon icon-folder-open-empty"></span> '+self.i18n.move+' '+( data.length || data.name );
				document.body.appendChild( dragImage );
				setTimeout(function() {
					dragImage.remove();
				});
				e.dataTransfer.setDragImage( dragImage, 0, 0 );
			});
			document.addEventListener( 'dragover', function( e ) { if( ! isFile( e ) && e.target.parentElement.classList.contains( 'isDir' ) ) e.preventDefault(); } );
			document.addEventListener( 'dragenter', function( e ) {
				if( ! isFile( e ) && e.target.tagName == "TD" && e.target.parentElement.classList.contains( 'isDir' ) )
					e.target.parentElement.classList.add( 'highlightedItem' );
			});
			document.addEventListener( 'dragleave', function( e ) {
				if( ! isFile( e ) && e.target.tagName == "TD" && e.target.parentElement.classList.contains( 'isDir' ) )
					e.target.parentElement.classList.remove( 'highlightedItem' );
			});
			document.addEventListener( 'drop', function( e ) {
				if( ! isFile( e ) && e.target.tagName == "TD" && e.target.parentElement.classList.contains( 'isDir' ) ) {
					e.preventDefault();
					e.stopPropagation();
					try {
						var source = JSON.parse( e.dataTransfer.getData( 'text' ) );
						self.log( "source:" );
						self.log( source );
						var destination = self.fileCache.find( x => x.guid === e.target.firstElementChild.id );
						if( ! Array.isArray( source ) )
							source = [source];
						if( source.find( x => x.name === destination.name ) )
							self.showMessage( "Source and destination are equal." );
						else
							self.copyMove( source, destination.name, "move" );
					} catch( e ) {
						console.log( e );
					} finally {
						[].slice.call( document.getElementsByClassName( 'highlightedItem' ) ).forEach( function( e ) {
							e.classList.remove( 'highlightedItem' );
						});
					}
				}
			});
		}
		
		// handle keystrokes
		document.onkeydown = self.handleKeystrokes;

		// handle history manipulation
		window.onpopstate = self.historyPopstateHandler;

		// handle window.onbeforeunload
		window.onbeforeunload = self.onbeforeunloadHandler;

		// load initial file table
		if( window.location.hash ) {
			self.changeDirectory( decodeURIComponent( window.location.hash.substring( 1 ) ) );
		} else {
			this.refreshFileTable();
			this.refreshVariantTable();
		}
	};

	this.init = function( id ) {
		self.rootElement = document.getElementById( id );
		this.initLoadConfig();
	};
}

</script>
f00bar;
	}

	public function getHTMLHeader() {
		print '<!DOCTYPE HTML>
		<html>
			<head>
				<title>I2 Configurator - Explorer</title>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<script src="i2ConfiguratorCore/build/i2ConfiguratorCore.min.js"></script>';
		$this->getCSS();
		print '</head><body>';
	}

	public function getHTMLFooter() {
		print '</body></html>';
	}

	/*
	   main functions
	 */

	private function handleRequest() {
		if( $_REQUEST["api"] == "getRealpath" ) {
			if( isset( $_REQUEST["dir"] ) && $_REQUEST["dir"] != "" )
				$this->jsonResponse( array( "realpath" => $this->getValidDir( $_REQUEST["dir"] ) ) );
			else
				$this->jsonResponse( array( "realpath" => "" ) );
		}
		elseif( $_REQUEST["api"] == "getFiles" ) {
			if( isset( $_REQUEST["dir"] ) && $this->isPathValid( $_REQUEST["dir"] ) )
				$this->getFiles( $_REQUEST["dir"] );
			else
				$this->getFiles( "" );
		}
		elseif( $_REQUEST["api"] == "getConfig" ) {
			$this->getConfig();
		}
		elseif( $_REQUEST["api"] == "getFolders" ) {
			$this->getFolders( $_REQUEST );
		} elseif( $_REQUEST["api"] == "getTemplates" ) {
			$this->jsonResponse( $this->templates );
		} elseif( $_REQUEST["api"] == "getI18N" ) {
			$this->jsonResponse( $this->l );
		} elseif( $_REQUEST["api"] == "logout" ) {
			unset( $_SESSION['ifmauth'] );
			session_destroy();
			header( "Location: " . strtok( $_SERVER["REQUEST_URI"], '?' ) );
			exit( 0 );
		} else {
			if( isset( $_REQUEST["dir"] ) && $this->isPathValid( $_REQUEST["dir"] ) ) {
				$this->chDirIfNecessary( $_REQUEST['dir'] );
				switch( $_REQUEST["api"] ) {
					case "createDir": $this->createDir( $_REQUEST["dir"], $_REQUEST["dirname"] ); break;
					case "saveFile": $this->saveFile( $_REQUEST ); break;
					case "getContent": $this->getContent( $_REQUEST ); break;
					case "delete": $this->deleteFiles( $_REQUEST ); break;
					case "rename": $this->renameFile( $_REQUEST ); break;
					case "download": $this->downloadFile( $_REQUEST ); break;
					case "extract": $this->extractFile( $_REQUEST ); break;
					case "upload": $this->uploadFile( $_REQUEST ); break;
					case "copyMove": $this->copyMove( $_REQUEST ); break;
					case "changePermissions": $this->changePermissions( $_REQUEST ); break;
					case "zipnload": $this->zipnload( $_REQUEST); break;
					case "remoteUpload": $this->remoteUpload( $_REQUEST ); break;
					case "searchItems": $this->searchItems( $_REQUEST ); break;
					case "getFolderTree": $this->getFolderTree( $_REQUEST ); break;
					case "createArchive": $this->createArchive( $_REQUEST ); break;
					case "proxy": $this->downloadFile( $_REQUEST, false ); break;
					default:
						$this->jsonResponse( array( "status" => "ERROR", "message" => "Invalid api action given" ) );
						break;
				}
			} else {
				print $this->jsonResponse( array( "status" => "ERROR", "message" => "Invalid working directory" ) );
			}
		}
		exit( 0 );
	}

	public function run( $mode="standalone" ) {
		if ( $this->checkAuth() ) {
			// go to our root_dir
			if( ! is_dir( realpath( $this->config['root_dir'] ) ) || ! is_readable( realpath( $this->config['root_dir'] ) ) )
				die( "Cannot access root_dir.");
			else
				chdir( realpath( $this->config['root_dir'] ) );
			$this->mode = $mode;
			if( isset( $_REQUEST['api'] ) || $mode == "api" ) {
				$this->handleRequest();
			} elseif( $mode == "standalone" ) {
				$this->getApplication();
			} else {
				$this->getInlineApplication();
			}
		}
	}

	/*
	   api functions
	 */


	private function getFiles( $dir ) {
		$this->chDirIfNecessary( $dir );

		unset( $files ); unset( $dirs ); $files = array(); $dirs = array();

		if( $handle = opendir( "." ) ) {
			while( false !== ( $result = readdir( $handle ) ) ) {
				if( $result == basename( $_SERVER['SCRIPT_NAME'] ) && $this->getScriptRoot() == getcwd() ) { }
				elseif( ( $result == ".htaccess" || $result==".htpasswd" ) && $this->config['showhtdocs'] != 1 ) {}
				elseif( $result == "." ) {}
				elseif( $result != ".." && substr( $result, 0, 1 ) == "." && $this->config['showhiddenfiles'] != 1 ) {}
				else {
					$item = $this->getItemInformation( $result );
					if( $item['type'] == "dir" ) $dirs[] = $item;
					else $files[] = $item;
				}
			}
			closedir( $handle );
		}
		usort( $dirs, array( $this, "sortByName" ) );
		usort( $files, array( $this, "sortByName" ) );

		$this->jsonResponse( array_merge( $dirs, $files ) );
	}

	private function getItemInformation( $name ) {
		$item = array();
		$item["name"] = $name;
		if( is_dir( $name ) ) {
			$item["type"] = "dir";
			if( $name == ".." )
				$item["icon"] = "icon icon-up-open";
			else 
				$item["icon"] = "icon icon-folder-empty";
		} else {
			$item["type"] = "file";
			if( in_array( substr( $name, -7 ), array( ".tar.gz", ".tar.xz" ) ) )
				$type = substr( $name, -6 );
			elseif( substr( $name, -8 ) == ".tar.bz2" )
				$type = "tar.bz2";
			else
				$type = substr( strrchr( $name, "." ), 1 );
			$item["icon"] = $this->getTypeIcon( $type );
			$item["ext"] = strtolower($type);
			if( !$this->config['disable_mime_detection'] )
				$item["mime_type"] = mime_content_type( $name );
		}
		if( $this->config['showlastmodified'] == 1 ) { $item["lastmodified"] = date( "d.m.Y, G:i e", filemtime( $name ) ); }
		if( $this->config['showfilesize'] == 1 ) {
			if( $item['type'] == "dir" ) {
				$item['size_raw'] = 0;
				$item['size'] = "";
			} else {
				$item["size_raw"] = filesize( $name );
				if( $item["size_raw"] > 1073741824 ) $item["size"] = round( ( $item["size_raw"]/1073741824 ), 2 ) . " GB";
				elseif($item["size_raw"]>1048576)$item["size"] = round( ( $item["size_raw"]/1048576 ), 2 ) . " MB";
				elseif($item["size_raw"]>1024)$item["size"] = round( ( $item["size_raw"]/1024 ), 2 ) . " KB";
				else $item["size"] = $item["size_raw"] . " Byte";
			}
		}
		if( $this->config['showpermissions'] > 0 ) {
			if( $this->config['showpermissions'] == 1 ) $item["fileperms"] = substr( decoct( fileperms( $name ) ), -3 );
			elseif( $this->config['showpermissions'] == 2 ) $item["fileperms"] = $this->filePermsDecode( fileperms( $name ) );
			if( $item["fileperms"] == "" ) $item["fileperms"] = " ";
			$item["filepermmode"] = ( $this->config['showpermissions'] == 1 ) ? "short" : "long";
		}
		if( $this->config['showowner'] == 1  ) {
			if ( function_exists( "posix_getpwuid" ) && fileowner($name) !== false ) {
				$ownerarr = posix_getpwuid( fileowner( $name ) );
				$item["owner"] = $ownerarr['name'];
			} else $item["owner"] = false;
		}
		if( $this->config['showgroup'] == 1 ) {
			if( function_exists( "posix_getgrgid" ) && filegroup( $name ) !== false ) {
				$grouparr = posix_getgrgid( filegroup( $name ) );
				$item["group"] = $grouparr['name'];
			} else $item["group"] = false;
		}
		return $item;
	}

	private function getConfig() {
		$ret = $this->config;
		$ret['inline'] = ( $this->mode == "inline" ) ? true : false;
		$ret['isDocroot'] = ( $this->getRootDir() == $this->getScriptRoot() ) ? true : false;
		$this->jsonResponse( $ret );
	}

	private function getFolders( $d ) {
		if( ! isset( $d['dir'] ) )
			$d['dir'] = $this->getRootDir();
		if( ! $this->isPathValid( $d['dir'] ) )
			echo "[]";
		else {
			$ret = array();
			foreach( glob( $this->pathCombine( $d['dir'], "*" ), GLOB_ONLYDIR ) as $dir ) {
				array_push( $ret, array(
					"text" => htmlspecialchars( basename( $dir ) ),
					"lazyLoad" => true,
					"dataAttr" => array( "path" => $dir )
				));
			}
			sort( $ret );
			if( $this->getScriptRoot() == realpath( $d['dir'] ) )
				$ret = array_merge(
					array(
						0 => array(
							"text" => "/ [root]",
							"dataAttr" => array( "path" => $this->getRootDir() )
						)
					),
					$ret
				);
			$this->jsonResponse( $ret );
		}
	}

	private function searchItems( $d ) {
		if( strpos( $d['pattern'], '/' ) !== false ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['pattern_error_slashes'] ) );
			exit( 1 );
		}
		try {
			$results = $this->searchItemsRecursive( $d['pattern'] );
			$this->jsonResponse( $results );
		} catch( Exception $e ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['error'] . " " . $e->getMessage() ) );
		}
	}

	private function searchItemsRecursive( $pattern, $dir="" ) {
		$items = array();
		$dir = $dir ? $dir : '.';
		foreach( glob( $this->pathCombine( $dir, $pattern ) ) as $result ) {
			array_push( $items, $this->getItemInformation( $result ) );
		}
		foreach( glob( $this->pathCombine( $dir, '*') , GLOB_ONLYDIR ) as $subdir ) {
			$items = array_merge( $items, $this->searchItemsRecursive( $pattern, $subdir ) );
		}
		return $items;
	}

	private function getFolderTree( $d ) {
		$this->jsonResponse(
			array_merge(
				array(
					0 => array(
						"text" => "/ [root]",
						"nodes" => array(),
						"dataAttributes" => array( "path" => $this->getRootDir() )
					)
				),
				$this->getFolderTreeRecursive( $d['dir']  )
			)
		);
	}

	private function getFolderTreeRecursive( $start_dir ) {
		$ret = array();
		$start_dir = realpath( $start_dir );
		if( $handle = opendir( $start_dir ) ) {
			while (false !== ( $result = readdir( $handle ) ) ) {
				if( is_dir( $this->pathCombine( $start_dir, $result ) ) && $result != "." && $result != ".." ) {
					array_push(
						$ret,
						array(
							"text" => htmlspecialchars( $result ),
							"dataAttributes" => array(
								"path" => $this->pathCombine( $start_dir, $result )
							),
							"nodes" => $this->getFolderTreeRecursive( $this->pathCombine( $start_dir, $result ) )
						)
					);
				}
			}
		}
		sort( $ret );
		return $ret;
	}

	private function copyMove( $d ) {
		if( $this->config['copymove'] != 1 ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
			exit( 1 );
		}
		if( ! isset( $d['destination'] ) || ! $this->isPathValid( realpath( $d['destination'] ) ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_dir'] ) );
			exit( 1 );
		}
		if( ! is_array( $d['filenames'] ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_params'] ) );
			exit( 1 );
		}
		if( ! in_array( $d['action'], array( 'copy', 'move' ) ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_action'] ) );
			exit( 1 );
		}
		$err = array(); $errFlag = -1; // -1 -> all errors; 0 -> at least some errors; 1 -> no errors
		foreach( $d['filenames'] as $file ) {
			if( ! file_exists( $file ) || $file == ".." || ! $this->isFilenameValid( $file ) ) {
				array_push( $err, $file );
			}
			if( $d['action'] == "copy" ) {
				if( $this->xcopy( $file, $d['destination'] ) )
					$errFlag = 0;
				else
					array_push( $err, $file );
			} elseif( $d['action'] == "move" ) {
				if( rename( $file, $this->pathCombine( $d['destination'], basename( $file ) ) ) )
					$errFlag = 0;
				else
					array_push( $err, $file );
			}
		}
		$action = ( $d['action'] == "copy" ? "copied" : "moved" );
		if( empty( $err ) ) {
			$this->jsonResponse( array( "status" => "OK", "message" => ( $d['action'] == "copy" ? $this->l['copy_success'] : $this->l['move_success'] ), "errflag" => "1" ) );
		}
		else {
			$errmsg = ( $d['action'] == "copy" ? $this->l['copy_error'] : $this->l['move_error'] ) . "<ul>";
			foreach( $err as $item )
				$errmsg .= "<li>".$item."</li>";
			$errmsg .= "</ul>";
			$this->jsonResponse( array( "status" => "OK", "message" => $errmsg, "flag" => $errFlag ) );
		}
	}

	// creates a directory
	private function createDir($w, $dn) {
		if( $this->config['createdir'] != 1 ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
			exit( 1 );
		}
		if( $dn == "" )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_dir'] ) );
		elseif( ! $this->isFilenameValid( $dn ) )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_dir'] ) );
		else {
			if( @mkdir( $dn ) )
				$this->jsonResponse( array( "status" => "OK", "message" => $this->l['folder_create_success'] ) );
			else
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['folder_create_error'] ) );
		}
	}

	// save a file
	private function saveFile( $d ) {
		if( ( file_exists( $this->pathCombine( $d['dir'], $d['filename'] ) ) && $this->config['edit'] != 1 ) || ( ! file_exists( $this->pathCombine( $d['dir'], $d['filename'] ) ) && $this->config['createfile'] != 1 ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
			exit( 1 );
		}
		if( isset( $d['filename'] ) && $this->isFilenameValid( $d['filename'] ) ) {
			if( isset( $d['content'] ) ) {
				// work around magic quotes
				$content = get_magic_quotes_gpc() == 1 ? stripslashes( $d['content'] ) : $d['content'];
				if( @file_put_contents( $d['filename'], $content ) !== false ) {
					$this->jsonResponse( array( "status" => "OK", "message" => $this->l['file_save_success'] ) );
				} else
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_save_error'] ) );
			} else
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_save_error'] ) );
		} else
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
	}

	// gets the content of a file
	// notice: if the content is not JSON encodable it returns an error
	private function getContent( array $d ) {
		if( $this->config['edit'] != 1 )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['npermissions'] ) );
		else {
			if( isset( $d['filename'] ) && $this->isFilenameAllowed( $d['filename'] ) && file_exists( $d['filename'] ) && is_readable( $d['filename'] ) ) {
				$content = @file_get_contents( $d['filename'] );
				if( function_exists( "mb_check_encoding" ) && ! mb_check_encoding( $content, "UTF-8" ) )
					$content = utf8_encode( $content );
				$this->jsonResponse( array( "status" => "OK", "data" => array( "filename" => $d['filename'], "content" => $content ) ) );
			} else $this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_not_found'] ) );
		}
	}

	// deletes a bunch of files or directories
	private function deleteFiles( array $d ) {
		if( $this->config['delete'] != 1 ) $this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		else {
			$err = array(); $errFLAG = -1; // -1 -> no files deleted; 0 -> at least some files deleted; 1 -> all files deleted
			foreach( $d['filenames'] as $file ) {
				if( $this->isFilenameAllowed( $file ) ) {
					if( is_dir( $file ) ) {
						$res = $this->rec_rmdir( $file );
						if( $res != 0 )
							array_push( $err, $file );
						else
							$errFLAG = 0;
					} else {
						if( @unlink($file) )
							$errFLAG = 0;
						else
							array_push($err, $file);
					}
				} else {
					array_push( $err, $file );
				}
			}
			if( empty( $err ) ) {
				$this->jsonResponse( array( "status" => "OK", "message" => $this->l['file_delete_success'], "errflag" => "1" ) );
			}
			else {
				$errmsg = $this->l['file_delete_error'] . "<ul>";
				foreach($err as $item)
					$errmsg .= "<li>".$item."</li>";
				$errmsg .= "</ul>";
				$this->jsonResponse( array( "status" => "ERROR", "message" => $errmsg, "flag" => $errFLAG ) );
			}
		}
	}

	// renames a file
	private function renameFile( array $d ) {
		if( $this->config['rename'] != 1 ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		} elseif( ! $this->isFilenameValid( $d['filename'] ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
		} elseif( ! $this->isFilenameValid( $d['newname'] ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
		} else {
			if( strpos( $d['newname'], '/' ) !== false )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['filename_slashes'] ) );
			elseif( $this->config['showhtdocs'] != 1 && ( substr( $d['newname'], 0, 3) == ".ht" || substr( $d['filename'], 0, 3 ) == ".ht" ) )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
			elseif( $this->config['showhiddenfiles'] != 1 && ( substr( $d['newname'], 0, 1) == "." || substr( $d['filename'], 0, 1 ) == "." ) )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
			else {
				if( @rename( $d['filename'], $d['newname'] ) )
					$this->jsonResponse( array( "status" => "OK", "message" => $this->l['file_rename_success'] ) );
				else
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_rename_error'] ) );
			}
		}
	}

	// provides a file for downloading
	private function downloadFile( array $d, $forceDL=true ) {
		if( $this->config['download'] != 1 )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		elseif( $this->config['showhtdocs'] != 1 && ( substr( $d['filename'], 0, 3 ) == ".ht" || substr( $d['filename'],0,3 ) == ".ht" ) )
			$this->jsonResponse( array( "status" => "ERROR", "message"=> $this->l['nopermissions'] ) );
		elseif( $this->config['showhiddenfiles'] != 1 && ( substr( $d['filename'], 0, 1 ) == "." || substr( $d['filename'],0,1 ) == "." ) )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		else {
			if( ! is_file( $d['filename' ] ) )
				http_response_code( 404 );
			else 
				$this->fileDownload( array( "file" => $d['filename'], "forceDL" => $forceDL ) );
		}
	}

	// extracts a zip-archive
	private function extractFile( array $d ) {
		$restoreIFM = false;
		$tmpSelfContent = null;
		$tmpSelfChecksum = null;
		if( $this->config['extract'] != 1 )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		else {
			if( ! file_exists( $d['filename'] ) ) {
				$this->jsonResponse( array( "status" => "ERROR","message" => $this->l['invalid_filename'] ) );
				exit( 1 );
			}
			if( ! isset( $d['targetdir'] ) || trim( $d['targetdir'] ) == "" )
				$d['targetdir'] = "./";
			if( ! $this->isPathValid( $d['targetdir'] ) ) {
				$this->jsonResponse( array( "status" => "ERROR","message" => $this->l['invalid_dir'] ) );
				exit( 1 );
			}
			if( ! is_dir( $d['targetdir'] ) && ! mkdir( $d['targetdir'], 0777, true ) ) {
				$this->jsonResponse( array( "status" => "ERROR","message" => $this->l['folder_create_error'] ) );
				exit( 1 );
			}
			if( realpath( $d['targetdir'] ) == substr( $this->getScriptRoot(), 0, strlen( realpath( $d['targetdir'] ) ) ) ) {
				$tmpSelfContent = tmpfile();
				fwrite( $tmpSelfContent, file_get_contents( __FILE__ ) );
				$tmpSelfChecksum = hash_file( "sha256", __FILE__ );
				$restoreIFM = true;
			}
			if( substr( strtolower( $d['filename'] ), -4 ) == ".zip" ) {
				if( ! IFMArchive::extractZip( $d['filename'], $d['targetdir'] ) ) {
					$this->jsonResponse( array( "status" => "ERROR","message" => $this->l['extract_error'] ) );
				} else {
					$this->jsonResponse( array( "status" => "OK","message" => $this->l['extract_success'] ) );
				}
			} else {
				if( ! IFMArchive::extractTar( $d['filename'], $d['targetdir'] ) ) {
					$this->jsonResponse( array( "status" => "ERROR","message" => $this->l['extract_error'] ) );
				} else {
					$this->jsonResponse( array( "status" => "OK","message" => $this->l['extract_success'] ) );
				}
			} 
			if( $restoreIFM ) {
				if( $tmpSelfChecksum != hash_file( "sha256", __FILE__ ) ) {
					rewind( $tmpSelfContent );
					$fh = fopen( __FILE__, "w" );
					while( ! feof( $tmpSelfContent ) ) {
						fwrite( $fh, fread( $tmpSelfContent, 8196 ) );
					}
					fclose( $fh );
				}
				fclose( $tmpSelfContent );
			}
		}
	}

	// uploads a file
	private function uploadFile( array $d ) {
		if( $this->config['upload'] != 1 )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		elseif( !isset( $_FILES['file'] ) )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_upload_error'] ) );
		else {
			$newfilename = ( isset( $d["newfilename"] ) && $d["newfilename"]!="" ) ? $d["newfilename"] : $_FILES['file']['name'];
			if( ! $this->isFilenameValid( $newfilename ) )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
			else {
				if( $_FILES['file']['tmp_name'] ) {
					if( is_writable( getcwd( ) ) ) {
						if( move_uploaded_file( $_FILES['file']['tmp_name'], $newfilename ) )
							$this->jsonResponse( array( "status" => "OK", "message" => $this->l['file_upload_success'], "cd" => $d['dir'] ) );
						else
							$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_upload_error'] ) );
					}
					else
						$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_upload_error'] ) );
				} else
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_not_found'] ) );
			}
		}
	}

	// change permissions of a file
	private function changePermissions( array $d ) {
		if( $this->config['chmod'] != 1 ) $this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		elseif( ! isset( $d["chmod"] )||$d['chmod']=="" ) $this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['permission_parse_error'] ) );
		elseif( ! $this->isPathValid( $this->pathCombine( $d['dir'],$d['filename'] ) ) ) { $this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) ); }
		else {
			$chmod = $d["chmod"]; $cmi = true;
			if( ! is_numeric( $chmod ) ) {
				$cmi = false;
				$chmod = str_replace( " ","",$chmod );
				if( strlen( $chmod )==9 ) {
					$cmi = true;
					$arr = array( substr( $chmod,0,3 ),substr( $chmod,3,3 ),substr( $chmod,6,3 ) );
					$chtmp = "0";
					foreach( $arr as $right ) {
						$rtmp = 0;
						if( substr( $right,0,1 )=="r" ) $rtmp = $rtmp + 4; elseif( substr( $right,0,1 )<>"-" ) $cmi = false;
						if( substr( $right,1,1 )=="w" ) $rtmp = $rtmp + 2; elseif( substr( $right,1,1 )<>"-" ) $cmi = false;
						if( substr( $right,2,1 )=="x" ) $rtmp = $rtmp + 1; elseif( substr( $right,2,1 )<>"-" ) $cmi = false;
						$chtmp = $chtmp . $rtmp;
					}
					$chmod = intval( $chtmp );
				}
			}
			else $chmod = "0" . $chmod;

			if( $cmi ) {
				try {
					chmod( $d["filename"], (int)octdec( $chmod ) );
					$this->jsonResponse( array( "status" => "OK", "message" => $this->l['permission_change_success'] ) );
				} catch ( Exception $e ) {
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['permission_change_error'] ) );
				}
			}
			else $this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['permission_parse_error'] ) );
		}
	}

	// zips a directory and provides it for downloading
	// it creates a temporary zip file in the current directory, so it has to be as much space free as the file size is
	private function zipnload( array $d ) {
		if( $this->config['zipnload'] != 1 )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermission'] ) );
		else {
			if( ! file_exists( $d['filename'] ) )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['folder_not_found'] ) );
			elseif ( ! $this->isFilenameValid( $d['filename'] ) )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
			else {
				unset( $zip );
				$dfile = $this->pathCombine( $this->config['tmp_dir'], uniqid( "ifm-tmp-" ) . ".zip" ); // temporary filename
				try {
					IFMArchive::createZip( realpath( $d['filename'] ), $dfile );
					if( $d['filename'] == "." ) {
						if( getcwd() == $this->getScriptRoot() )
							$d['filename'] = "root";
						else
							$d['filename'] = basename( getcwd() );
					}
					$this->fileDownload( array( "file" => $dfile, "name" => $d['filename'] . ".zip" ) );
				} catch ( Exception $e ) {
					echo $this->l['error'] . " " . $e->getMessage();
				} finally {
					if( file_exists( $dfile ) ) @unlink( $dfile );
				}
			}
		}
	}

	// uploads a file from an other server using the curl extention
	private function remoteUpload( array $d ) {
		if( $this->config['remoteupload'] != 1 )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
		elseif( !isset( $d['method'] ) || !in_array( $d['method'], array( "curl", "file" ) ) )
			$this->jsonResponse( array( "status" => "error", "message" => $this->l['invalid_params'] ) );
		elseif( $d['method']=="curl" && $this->checkCurl( ) == false )
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['error']." cURL extention not installed." ) );
		elseif( $d['method']=="curl" && $this->checkCurl( ) == true ) {
			$filename = ( isset( $d['filename'] )&&$d['filename']!="" )?$d['filename']:"curl_".uniqid( );
			$ch = curl_init( );
			if( $ch ) {
				if( $this->isFilenameValid( $filename ) == false )
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
				elseif( filter_var( $d['url'], FILTER_VALIDATE_URL ) === false )
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_url'] ) );
				else {
					$fp = fopen( $filename, "w" );
					if( $fp ) {
						if( !curl_setopt( $ch, CURLOPT_URL, urldecode( $d['url'] ) ) || !curl_setopt( $ch, CURLOPT_FILE, $fp ) || !curl_setopt( $ch, CURLOPT_HEADER, 0 ) || !curl_exec( $ch ) )
							$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['error']." ".curl_error( $ch ) ) );
						else {
							$this->jsonResponse( array( "status" => "OK", "message" => $this->l['file_upload_success'] ) );
						}
						curl_close( $ch );
						fclose( $fp );
					} else {
						$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['file_open_error'] ) );
					}
				}
			} else {
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['error']." curl init" ) );
			}
		}
		elseif( $d['method']=='file' ) {
			$filename = ( isset( $d['filename'] ) && $d['filename']!="" ) ? $d['filename'] : "curl_".uniqid( );
			if( $this->isFilenameValid( $filename ) == false )
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
			else {
				try {
					file_put_contents( $filename, file_get_contents( $d['url'] ) );
					$this->jsonResponse( array( "status" => "OK", "message" => $this->l['file_upload_success'] ) );
				} catch( Exception $e ) {
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['error'] . " " . $e->getMessage() ) );
				}
			}
		}
		else
			$this->jsonResponse( array( "status" => "error", "message" => $this->l['invalid_params'] ) );
	}

	private function createArchive( $d ) {
		if( $this->config['createarchive'] != 1 ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['nopermissions'] ) );
			return false;
		}
		if( ! $this->isFilenameValid( $d['archivename'] ) ) {
			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
			return false;
		}
		$filenames = array();
		foreach( $d['filenames'] as $file )
			if( ! $this->isFilenameValid( $file ) ) {
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['invalid_filename'] ) );
				exit( 1 );
			} else 
				array_push( $filenames, realpath( $file ) );
		switch( $d['format'] ) {
			case "zip":
				if( IFMArchive::createZip( $filenames, $d['archivename'] ) )
					$this->jsonResponse( array( "status" => "OK", "message" => $this->l['archive_create_success'] ) );
				else
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['archive_create_error'] ) );
				break;
			case "tar":
			case "tar.gz":
			case "tar.bz2":
				if( IFMArchive::createTar( $filenames, $d['archivename'], $d['format'] ) )
					$this->jsonResponse( array( "status" => "OK", "message" => $this->l['archive_create_success'] ) );
				else
					$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['archive_create_error'] ) );
				break;
			default:
				$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['archive_invalid_format'] ) );
				break;
		}
	}

	/*
	   help functions
	 */

	private function log( $d ) {
		file_put_contents( $this->pathCombine( $this->getRootDir(), "debug.ifm.log" ), ( is_array( $d ) ? print_r( $d, true ) . "\n" : $d . "\n" ), FILE_APPEND );
	}

	private function jsonResponse( $array ) {
		$this->convertToUTF8( $array );
		$json = json_encode( $array );
		if( $json === false ) {
			switch(json_last_error()) {
			case JSON_ERROR_NONE:
				echo ' - No errors';
				break;
			case JSON_ERROR_DEPTH:
				echo ' - Maximum stack depth exceeded';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				echo ' - Underflow or the modes mismatch';
				break;
			case JSON_ERROR_CTRL_CHAR:
				echo ' - Unexpected control character found';
				break;
			case JSON_ERROR_SYNTAX:
				echo ' - Syntax error, malformed JSON';
				break;
			case JSON_ERROR_UTF8:
				echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
			default:
				echo ' - Unknown error';
				break;
			}

			$this->jsonResponse( array( "status" => "ERROR", "message" => $this->l['json_encode_error'] . " " . $err ) );
		} else {
			echo $json;
		}
	}

	private function convertToUTF8( &$item ) {
		if( is_array( $item ) )
			array_walk(
				$item,
				array( $this, 'convertToUTF8' )
			);
		else
			if( function_exists( "mb_check_encoding" ) && ! mb_check_encoding( $item, "UTF-8" ) )
				$item = utf8_encode( $item );
	}

	public function checkAuth() {
		if( $this->config['auth'] == 0 )
			return true;

		if( isset( $_SERVER['HTTP_X_IFM_AUTH'] ) && ! empty( $_SERVER['HTTP_X_IFM_AUTH'] ) ) {
			$cred = split( ":", base64_decode( $_SERVER['HTTP_X_IFM_AUTH'] ) );
			if( count( $cred ) == 2 && $this->checkCredentials( $cred[0], $cred[1] ) )
				return true;
		}

		if( session_status() !== PHP_SESSION_ACTIVE ) {
			if( isset( $this->config['session_lifetime'] ) )
				ini_set( 'session.gc_maxlifetime', $this->config['session_lifetime'] );
			if( isset( $this->config['force_session_lifetime'] ) && $this->config['force_session_lifetime'] ) {
				ini_set( 'session.gc_divisor', 1 );
				ini_set( 'session.gc_probability', 1 );
			}
			session_start();
		}

		if( ! isset( $_SESSION['ifmauth'] ) || $_SESSION['ifmauth'] !== true ) {
			$login_failed = false;
			if( isset( $_POST["user"] ) && isset( $_POST["pass"] ) ) {
				if( $this->checkCredentials( $_POST["user"], $_POST["pass"] ) ) {
					$_SESSION['ifmauth'] = true;
				}
				else {
					$_SESSION['ifmauth'] = false;
					$login_failed = true;
				}
			}

			if( isset( $_SESSION['ifmauth'] ) && $_SESSION['ifmauth'] === true ) {
				return true;
			} else {
				if( isset( $_POST["api"] ) ) {
					if( $login_failed === true )
						$this->jsonResponse( array( "status"=>"ERROR", "message"=>"authentication failed" ) );
					else
						$this->jsonResponse( array( "status"=>"ERROR", "message"=>"not authenticated" ) );
				} else {
					$this->loginForm($login_failed);
				}
				return false;
			}
		} else {
			return true;
		}
	}

	private function checkCredentials( $user, $pass ) {
		list( $src, $srcopt ) = explode( ";", $this->config['auth_source'], 2 );
		switch( $src ) {
			case "inline":
				list( $uname, $hash ) = explode( ":", $srcopt );
				$htpasswd = new Htpasswd();
				return $htpasswd->verifyPassword( $pass, $hash ) ? ( $uname == $user ) : false;
				break;
			case "file":
				if( @file_exists( $srcopt ) && @is_readable( $srcopt ) ) {
					$htpasswd = new Htpasswd( $srcopt );
					return $htpasswd->verify( $user, $pass );
				} else {
					trigger_error( "IFM: Fatal: Credential file does not exist or is not readable" );
					return false;
				}
				break;
			case "ldap":
				$authenticated = false;
				list( $ldap_server, $rootdn ) = explode( ";", $srcopt );
				$u = "uid=" . $user . "," . $rootdn;
				if( ! $ds = ldap_connect( $ldap_server ) ) {
					trigger_error( "Could not reach the ldap server.", E_USER_ERROR );
					return false;
				}
				ldap_set_option( $ds, LDAP_OPT_PROTOCOL_VERSION, 3 );
				if( $ds ) {
					$ldbind = @ldap_bind( $ds, $u, $pass );
					if( $ldbind ) {
						$authenticated = true;
					} else {
						trigger_error( ldap_error( $ds ), E_USER_ERROR );
						$authenticated = false;
					}
					ldap_unbind( $ds );
				} else
					$authenticated = false;
				return $authenticated;
				break;
		}
		return false;
	}

	private function loginForm($loginFailed=false) {
		$err = "";
		if( $loginFailed ) 
			$err = '<div class="alert alert-danger">'.$this->l['login_failed'].'</div>';
		$this->getHTMLHeader();
		$html = str_replace( "{{error}}", $err, $this->templates['login'] );
		$html = str_replace( "{{i18n.username}}", $this->l['username'], $html );
		$html = str_replace( "{{i18n.password}}", $this->l['password'], $html );
		$html = str_replace( "{{i18n.login}}", $this->l['login'], $html );
		print $html;
		$this->getHTMLFooter();
	}

	private function filePermsDecode( $perms ) {
		$oct = str_split( strrev( decoct( $perms ) ), 1 );
		$masks = array( '---', '--x', '-w-', '-wx', 'r--', 'r-x', 'rw-', 'rwx' );
		return(
			sprintf(
				'%s %s %s',
				array_key_exists( $oct[ 2 ], $masks ) ? $masks[ $oct[ 2 ] ] : '###',
				array_key_exists( $oct[ 1 ], $masks ) ? $masks[ $oct[ 1 ] ] : '###',
				array_key_exists( $oct[ 0 ], $masks ) ? $masks[ $oct[ 0 ] ] : '###')
		);
	}

	private function isAbsolutePath( $path ) {
		if( $path === null || $path === '' )
			return false;
		return $path[0] === DIRECTORY_SEPARATOR || preg_match('~\A[A-Z]:(?![^/\\\\])~i',$path) > 0;
	}

	private function getRootDir() {
		if( $this->config['root_dir'] == "" )
			return realpath( $this->getScriptRoot() );
		elseif( $this->isAbsolutePath( $this->config['root_dir'] ) )
			return realpath( $this->config['root_dir'] );
		else
			return realpath( $this->pathCombine( realpath($this->getScriptRoot() . "/../"), realpath($this->config['root_dir'] )));
	}

	private function getScriptRoot() {
		return ( defined( 'IFM_FILENAME' ) ? dirname( realpath( IFM_FILENAME ) ) : dirname( __FILE__ ) );
	}

	private function getValidDir( $dir ) {
		if( ! $this->isPathValid( $dir ) || ! is_dir( $dir ) )
			return "";
		else {
			$rpDir = realpath( $dir );
			$rpConfig = $this->getRootDir();
			if( $rpConfig == "/" )
				return $rpDir;
			elseif( $rpDir == $rpConfig )
				return "";
			else {
				$part = substr( $rpDir, strlen( $rpConfig ) );
				$part = ( in_array( substr( $part, 0, 1 ), ["/", "\\"] ) ) ? substr( $part, 1 ) : $part;
				return $part;
			}
		}
	}


	private function isPathValid( $dir ) {
		/**
		 * This function is also used to check non-existent paths, but the PHP realpath function returns false for
		 * nonexistent paths. Hence we need to check the path manually in the following lines.
		 */
		$tmp_d = $dir;
		$tmp_missing_parts = array();
		while( realpath( $tmp_d ) === false ) {
			$tmp_i = pathinfo( $tmp_d );
			array_push( $tmp_missing_parts, $tmp_i['filename'] );
			$tmp_d = dirname( $tmp_d );
			if( $tmp_d == dirname( $tmp_d ) ) break;
		}
		$rpDir = $this->pathCombine( realpath( $tmp_d ), implode( "/", array_reverse( $tmp_missing_parts ) ) );
		$rpConfig = $this->getRootDir();
		if( ! is_string( $rpDir ) || ! is_string( $rpConfig ) ) // can happen if open_basedir is in effect
			return false;
		elseif( $rpDir == $rpConfig )
			return true;
		elseif( 0 === strpos( $rpDir, $rpConfig ) )
			return true;
		else
			return false;
	}

	private function chDirIfNecessary($d) {
		if( substr( getcwd(), strlen( $this->getScriptRoot() ) ) != $this->getValidDir($d) && !empty( $d ) ) {
			chdir( $d );
		}
	}

	private function getTypeIcon( $type ) {
		$type = strtolower($type);
		switch( $type ) {
			case "aac":	case "aiff": case "mid": case "mp3": case "wav": return 'icon icon-file-audio'; break;
			case "ai": case "bmp": case "eps": case "tiff": case "gif": case "jpg": case "jpeg": case "png": case "psd": return 'icon icon-file-image'; break;
			case "avi": case "flv": case "mp4": case "mpg": case "mkv": case "mpeg": case "webm": case "wmv": case "mov": return 'icon icon-file-video'; break;
			case "c": case "cpp": case "css": case "dat": case "h": case "html": case "java": case "js": case "php": case "py": case "sql": case "xml": case "yml": return 'icon icon-file-code'; break;
			case "doc": case "docx": case "odf": case "odt": case "rtf": return 'icon icon-file-word'; break;
			case "ods": case "xls": case "xlsx": return 'icon icon-file-excel'; break;
			case "odp": case "ppt": case "pptx": return 'icon icon-file-powerpoint'; break;
			case "pdf": return 'icon icon-file-pdf'; break;
			case "tgz":	case "zip": case "tar": case "tgz": case "tar.gz": case "tar.xz": case "tar.bz2": case "7z": case "rar": return 'icon icon-file-archive';
			default: return 'icon icon-doc';
		}
	}

	private function rec_rmdir( $path ) {
		if( !is_dir( $path ) ) {
			return -1;
		}
		$dir = @opendir( $path );
		if( !$dir ) {
			return -2;
		}
		while( ( $entry = @readdir( $dir ) ) !== false ) {
			if( $entry == '.' || $entry == '..' ) continue;
			if( is_dir( $path . '/' . $entry ) ) {
				$res = $this->rec_rmdir( $path . '/' . $entry );
				if( $res == -1 ) { @closedir( $dir ); return -2; }
				else if( $res == -2 ) { @closedir(  $dir ); return -2; }
				else if( $res == -3 ) { @closedir( $dir ); return -3; }
				else if( $res != 0 ) { @closedir( $dir ); return -2; }
			} else if( is_file( $path . '/' . $entry ) || is_link( $path . '/' . $entry ) ) {
				$res = @unlink( $path . '/' . $entry );
				if( !$res ) { @closedir( $dir ); return -2; }
			} else { @closedir( $dir ); return -3; }
		}
		@closedir( $dir );
		$res = @rmdir( $path );
		if( !$res ) { return -2; }
		return 0;
	}

	private function xcopy( $source, $dest ) {
		$isDir = is_dir( $source );
		if( $isDir )
			$dest = $this->pathCombine( $dest, basename( $source ) );
		if( ! is_dir( $dest ) )
			mkdir($dest, 0777, true);
		if( is_file( $source ) )
			return copy( $source, $this->pathCombine( $dest, basename( $source ) ) );

		chdir( $source );
		foreach( glob( '*' ) as $item )
			$this->xcopy( $item, $dest );
		chdir( '..' );
		return true;
	}

	// combines two parts to a valid path
	private function pathCombine( $a, $b ) {
		if( trim( $a ) == "" && trim( $b ) == "" )
			return "";
		elseif( trim( $a ) == "" )
			return ltrim( $b, '/' );
		else
			return rtrim( $a, '/' ) . '/' . trim( $b, '/' );
	}

	// check if filename is allowed
	private function isFilenameValid( $f ) {
		if( ! $this->isFilenameAllowed( $f ) )
			return false;
		if( strtoupper( substr( PHP_OS, 0, 3 ) ) == "WIN" ) {
			// windows-specific limitations
			foreach( array( '\\', '/', ':', '*', '?', '"', '<', '>', '|' ) as $char )
				if( strpos( $f, $char ) !== false )
					return false;
		} else {
			// *nix-specific limitations
			foreach( array( '/', '\0' ) as $char )
				if( strpos( $f, $char ) !== false )
					return false;
		}
		// custom limitations
		foreach( $this->config['forbiddenChars'] as $char )
			if( strpos( $f, $char ) !== false )
				return false;
		return true;
	}

	private function isFilenameAllowed( $f ) {
		if( $this->config['showhtdocs'] != 1 && substr( $f, 0, 3 ) == ".ht" )
			return false;
		elseif( $this->config['showhiddenfiles'] != 1 && substr( $f, 0, 1 ) == "." )
			return false;
		elseif( $this->config['selfoverwrite'] != 1 && getcwd() == $this->getScriptRoot() && $f == basename( __FILE__ ) )
			return false;
		else
			return true;
	}

	// sorting function for file and dir arrays
	private function sortByName( $a, $b ) {
		if( strtolower( $a['name'] ) == strtolower( $b['name'] ) ) return 0;
		return ( strtolower( $a['name'] ) < strtolower( $b['name'] ) ) ? -1 : 1;
	}

	// is cURL extention avaliable?
	private function checkCurl() {
		if( !function_exists( "curl_init" ) ||
				!function_exists( "curl_setopt" ) ||
				!function_exists( "curl_exec" ) ||
				!function_exists( "curl_close" ) ) return false;
		else return true;
	}

	private function fileDownload( array $options ) {
		if( isset( $options['forceDL'] ) && $options['forceDL'] )
			$content_type = "application/octet-stream";
		else
			$content_type = mime_content_type( $options['file'] );

		if( ! isset( $options['name'] ) || trim( $options['name'] ) == "" )
			$options['name'] = basename( $options['file'] );

		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: ' . $content_type );
		header( 'Content-Disposition: attachment; filename="' . $options['name'] . '"' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . filesize( $options['file'] ) );

		$file_stream = fopen( $options['file'], 'rb' );
		$stdout_stream = fopen('php://output', 'wb');

		stream_copy_to_stream($file_stream, $stdout_stream);

		fclose($file_stream);
		fclose($stdout_stream);
	}

}

/**
 * =======================================================================
 * Improved File Manager
 * ---------------------
 * License: This project is provided under the terms of the MIT LICENSE
 * http://github.com/misterunknown/ifm/blob/master/LICENSE
 * =======================================================================
 * 
 * archive class
 *
 * This class provides support for various archive types for the IFM. It can
 * create and extract the following formats:
 * 	* zip
 * 	* tar
 * 	* tar.gz
 * 	* tar.bz2
*/

class IFMArchive {

	/**
	 * Add a folder to an archive
	 */
	private static function addFolder( &$archive, $folder, $offset=0 ) {
		if( $offset == 0 )
			$offset = strlen( dirname( $folder ) ) + 1;
		$archive->addEmptyDir( substr( $folder, $offset ) );
		$handle = opendir( $folder );
		while( false !== $f = readdir( $handle ) ) {
			if( $f != '.' && $f != '..'  ) {
				$filePath = $folder . '/' . $f;
				if( file_exists( $filePath ) && is_readable( $filePath ) )
					if( is_file( $filePath ) )
						$archive->addFile( $filePath, substr( $filePath, $offset ) );
					elseif( is_dir( $filePath ) )
						self::addFolder( $archive, $filePath, $offset );
			}
		}
		closedir( $handle );
	}

	/**
	 * Create a zip file
	 */
	public static function createZip( $src, $out )
	{
		$a = new ZipArchive();
		$a->open( $out, ZIPARCHIVE::CREATE);

		if( ! is_array( $src ) )
			$src = array( $src );

		foreach( $src as $s )
			if( is_dir( $s ) )
				self::addFolder( $a, $s );
			elseif( is_file( $s ) )
				$a->addFile( $s, substr( $s, strlen( dirname( $s ) ) + 1 ) );

		try {
			return $a->close();
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Unzip a zip file
	 */
	public static function extractZip( $file, $destination="./" ) {
		if( ! file_exists( $file ) )
			return false;
		$zip = new ZipArchive;
		$res = $zip->open( $file );
		if( $res === true ) {
			$zip->extractTo( $destination );
			$zip->close();
			return true;
		} else
			return false;
	}

	/**
	 * Creates a tar archive
	 */
	public static function createTar( $src, $out, $t ) {
		$tmpf = substr( $out, 0, strlen( $out ) - strlen( $t ) ) . "tar";
		$a = new PharData( $tmpf );

		try { 
			if( ! is_array( $src ) )
				$src = array( $src );

			foreach( $src as $s )
				if( is_dir( $s ) )
					self::addFolder( $a, $s );
				elseif( is_file( $s ) )
					$a->addFile( $s, substr( $s, strlen( dirname( $s ) ) +1 ) ); 
			switch( $t ) {
			case "tar.gz":
				$a->compress( Phar::GZ );
				@unlink( $tmpf );
				break;
			case "tar.bz2":
				$a->compress( Phar::BZ2 );
				@unlink( $tmpf );
				break;
			}
			return true;
		} catch( Exception $e ) {
			@unlink( $tmpf );
			return false;
		}
	}

	/**
	 * Extracts a tar archive
	 */
	public static function extractTar( $file, $destination="./" ) {
		if( ! file_exists( $file ) )
			return false;
		$tar = new PharData( $file );
		try {
			$tar->extractTo( $destination, null, true );
			return true;
		} catch( Exception $e ) {
			return false;
		}
	}
}
/**
 * htpasswd parser
 */

class Htpasswd {
	public $users = [];

	public function __construct( $filename="" ) {
		if( $filename )
			$this->load( $filename );
	}

	/**
	 * Load a new htpasswd file
	 */
	public function load( $filename ) {
		unset( $this->users );
		if( file_exists( $filename ) && is_readable( $filename ) ) {
			$lines = file( $filename );
			foreach( $lines as $line ) {
				list( $user, $pass ) = explode( ":", $line );
				$this->users[$user] = trim( $pass );
			}
			return true;
		} else
			return false;
	}

	public function getUsers() {
		return array_keys( $this->users );
	}

	public function userExist( $user ) {
		return isset( $this->users[ $user ] );
	}

	public function verify( $user, $pass ) {
		if( isset( $this->users[$user] ) ) {
			return $this->verifyPassword( $pass, $this->users[$user] );
		} else {
			return false;
		}
	}

	public function verifyPassword( $pass, $hash ) {
		if( substr( $hash, 0, 4 ) == '$2y$' ) {
			return password_verify( $pass, $hash );
		} elseif( substr( $hash, 0, 6 ) == '$apr1$' ) {
			$apr1 = new APR1_MD5();
			return $apr1->check( $pass, $hash );
		} elseif( substr( $hash, 0, 5 ) == '{SHA}' ) {
			return base64_encode( sha1( $pass, TRUE ) ) == substr( $hash, 5 );
		} else { // assume CRYPT
			return crypt( $pass, $hash ) == $hash;
		}
	}
}

/**
 * APR1_MD5 class
 *
 * Source: https://github.com/whitehat101/apr1-md5/blob/master/src/APR1_MD5.php
 */
class APR1_MD5 {

    const BASE64_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    const APRMD5_ALPHABET = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Source/References for core algorithm:
    // http://www.cryptologie.net/article/126/bruteforce-apr1-hashes/
    // http://svn.apache.org/viewvc/apr/apr-util/branches/1.3.x/crypto/apr_md5.c?view=co
    // http://www.php.net/manual/en/function.crypt.php#73619
    // http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
    // Wikipedia

    public static function hash($mdp, $salt = null) {
        if (is_null($salt))
            $salt = self::salt();
        $salt = substr($salt, 0, 8);
        $max = strlen($mdp);
        $context = $mdp.'$apr1$'.$salt;
        $binary = pack('H32', md5($mdp.$salt.$mdp));
        for($i=$max; $i>0; $i-=16)
            $context .= substr($binary, 0, min(16, $i));
        for($i=$max; $i>0; $i>>=1)
            $context .= ($i & 1) ? chr(0) : $mdp[0];
        $binary = pack('H32', md5($context));
        for($i=0; $i<1000; $i++) {
            $new = ($i & 1) ? $mdp : $binary;
            if($i % 3) $new .= $salt;
            if($i % 7) $new .= $mdp;
            $new .= ($i & 1) ? $binary : $mdp;
            $binary = pack('H32', md5($new));
        }
        $hash = '';
        for ($i = 0; $i < 5; $i++) {
            $k = $i+6;
            $j = $i+12;
            if($j == 16) $j = 5;
            $hash = $binary[$i].$binary[$k].$binary[$j].$hash;
        }
        $hash = chr(0).chr(0).$binary[11].$hash;
        $hash = strtr(
            strrev(substr(base64_encode($hash), 2)),
            self::BASE64_ALPHABET,
            self::APRMD5_ALPHABET
        );
        return '$apr1$'.$salt.'$'.$hash;
    }

    // 8 character salts are the best. Don't encourage anything but the best.
    public static function salt() {
        $alphabet = self::APRMD5_ALPHABET;
        $salt = '';
        for($i=0; $i<8; $i++) {
            $offset = hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % 64;
            $salt .= $alphabet[$offset];
        }
        return $salt;
    }

    public static function check($plain, $hash) {
        $parts = explode('$', $hash);
        return self::hash($plain, $parts[2]) === $hash;
    }
}
