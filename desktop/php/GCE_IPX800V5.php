<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('GCE_IPX800V5');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes ipx800}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br/>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Nom de l'équipement IPX800}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement IPX800}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" >{{Objet parent}}</label>
							<div class="col-sm-3">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									$options = '';
									foreach ((jeeObject::buildTree(null, false)) as $object) {
										$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
									}
									echo $options;
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-9">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Catégorie}}</label>
							<div class="col-sm-9">
								<?php
								foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>';
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{IP}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="ip"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Clef API}}</label>
							<div class="col-sm-3">
								<input type="password" autocomplete="new-password"  class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="apikey"/>
							</div>
						</div>

						<div class="form-group">
						<label class="col-sm-2 control-label">{{Commandes IPX par défaut}}</label>
						<div class="col-sm-9">
							<?php
								foreach (GCE_IPX800V5::PRESET_IPX as $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="' . $value . '" />' . GCE_IPX800V5::TYPE_DATA_IPX[$value][0];
									echo '</label>';
								}
							?>
						</div>
						</div>

						<div class="form-group">
						<label class="col-sm-2 control-label">{{Commandes IPX800 V4 par défaut}}</label>
						<div class="col-sm-9">
							<?php
							for ($i=0; $i < 4; $i++) {
								echo '<div class="col-sm-12">';
								echo '<label class="checkbox-inline">';
								echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="V4'. $i . '" />IPX800 V4 '. $i;
								echo '</label>';
								echo '</div>';
								echo '<div class="col-sm-12">';
								foreach (GCE_IPX800V5::PRESET_IPXV4 as $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="V4'. $i . $value . '" />' . GCE_IPX800V5::TYPE_DATA_IPXV4[$value][0];
									echo '</label>';
								}
								echo '</div>';
							}
							?>
						</div>
						</div>

						<div class="form-group">
						<label class="col-sm-2 control-label">{{Commandes Objets par défaut}}</label>
						<div class="col-sm-10">:</div>
						<div>
							<?php
							foreach (GCE_IPX800V5::PRESET_OBJ as $key => $value) {
								echo '<div class="col-sm-2"></div>';
								echo '<div class="col-sm-9">';
								echo '<label class="checkbox-inline">';
								echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="' . $key . '" />' . $value[0];
								echo '</label>';
								echo '</div>';
								if ($value[2] == 1) {
									echo '<div class="col-sm-2"></div>';
									echo '<div class="col-sm-9">';
								}
								for ($i=0; $i < $value[1]; $i++) {
										if ($value[2] > 1) {
											echo '<div class="col-sm-2"></div>';
											echo '<div class="col-sm-9">';
										}
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="' . $key .  $i. '" />' . ($i);
										echo '</label>';
										if ($value[2] > 1) {
											for ($j=0; $j < $value[2]; $j++) {
												echo '<label class="checkbox-inline">';
												echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="Canal' . $key . $i . '_' . $j . '" />'. $value[4] . ($j);
												echo '</label>';
											}
										}
										if ($value[3] == 1) {
											echo '<label class="checkbox-inline">';
											echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="Canal' . $key . $i . '_All" />Canal All';
											echo '</label>';
										}
									if ($value[2] > 1) { echo '</div>'; }
								}
								if ($value[2] == 1) { echo '</div>'; }
								echo '<div class="col-sm-12">_</div>';
							}
							?>
						</div>
						</div>

						<div class="form-group">
						<label class="col-sm-2 control-label">{{Commandes Extensions par défaut}}</label>
						<div class="col-sm-10">:</div>
						<div>
							<?php
							foreach (GCE_IPX800V5::PRESET_EXT as $key => $value) {
								echo '<div class="col-sm-2"></div>';
								echo '<div class="col-sm-9">';
								echo '<label class="checkbox-inline">';
								echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="' . $key . '" />' . $value[0];
								echo '</label>';
								echo '</div>';
								if ($value[2] == 1) {
									echo '<div class="col-sm-2"></div>';
									echo '<div class="col-sm-9">';
								}
								for ($i=0; $i < $value[1]; $i++) {
										if ($value[2] > 1) {
											echo '<div class="col-sm-2"></div>';
											echo '<div class="col-sm-9">';
										}
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="' . $key .  $i. '" />' . ($i);
										echo '</label>';
										if ($value[2] > 1) {
											for ($j=0; $j < $value[2]; $j++) {
												echo '<label class="checkbox-inline">';
												echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="Canal' . $key . $i . '_' . $j . '" />'. $value[4] . ($j);
												echo '</label>';
											}
										}
										if ($value[3] == 1) {
											echo '<label class="checkbox-inline">';
											echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="Canal' . $key . $i . '_All" />Canal All';
											echo '</label>';
										}
									if ($value[2] > 1) { echo '</div>'; }
								}
								if ($value[2] == 1) { echo '</div>'; }
								echo '<div class="col-sm-12">_</div>';
							}
							?>
						</div>
					</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<div class="input-group pull-right" style="display:inline-flex">
					<span class="input-group-btn">
						<a class="btn btn-default btn-sm cmdAction roundedLeft" data-action="importFromTemplate"><i class="fas fa-file"></i> {{Templates}}</a><a class="btn btn-success btn-sm cmdAction roundedRight" data-action="add"><i class="fas fa-plus-circle"></i> {{Commandes}}</a>
					</span>
				</div>
				<br/><br/>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th style="width:50px;">{{Type}}</th>
							<th>{{Configuration}}</th>
							<th style="width:300px;">{{Paramètres}}</th>
							<th style="width:125px;">{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'GCE_IPX800V5', 'js', 'GCE_IPX800V5');?>
<?php include_file('core', 'plugin.template', 'js');?>
