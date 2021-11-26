/* SUMMARY
* Action Type
  * Action Parameter
  * Action Option
* Info Type
  * Info Type Arg
  * Info Parameter
*/

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

$('#table_cmd').on('change','.cmdAttr[data-l1key=type]',function(){
  if($(this).value() == 'action'){
    $(this).closest('.cmd').find('.actionType').show();
    $(this).closest('.cmd').find('.infoType').hide();
  }else{
    $(this).closest('.cmd').find('.actionType').hide();
    $(this).closest('.cmd').find('.infoType').show();
  }
});

$('#table_cmd').on('change','.cmdAttr[data-l1key=configuration][data-l2key=actionCmd]',function(){
  $(this).closest('.cmd').find('.actionArgument').hide();
  $(this).closest('.cmd').find('.actionArgument.'+$(this).value()).show();
});

$('#table_cmd').on('change','.cmdAttr[data-l1key=configuration][data-l2key=actionArgument]',function(){
  $(this).closest('.cmd').find('.actionParameter').hide();
  $(this).closest('.cmd').find('.actionParameter.'+$(this).value()).show();

  $(this).closest('.cmd').find('.actionOption').hide();
  $(this).closest('.cmd').find('.actionOption.'+$(this).value()).show();
});

$('#table_cmd').on('change','.cmdAttr[data-l1key=configuration][data-l2key=infoType]',function(){
  $(this).closest('.cmd').find('.infoParameter').hide();
  $(this).closest('.cmd').find('.infoParameter.'+$(this).value()).show();

  $(this).closest('.cmd').find('.infoOption').hide();
  $(this).closest('.cmd').find('.infoOption.'+$(this).value()).show();
});

$('.cmdAction[data-action=importFromTemplate]').on('click',function(){
  $('#md_modal').dialog({title: "{{Template commande IPX800}}"});
  $("#md_modal").load('index.php?v=d&plugin=GCE_IPX800V5&modal=cmd.template&eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_downloadIpxBackup').on('click',function(){
  window.open('core/php/downloadFile.php?pathfile=plugins/GCE_IPX800V5/data/'+$('.eqLogicAttr[data-l2key=ip]').value()+'.gce', "_blank", null);
});

function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
  }
  var disabled = '';
  if(init(_cmd.logicalId) == 'refresh'){
    var disabled = 'disabled';
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
  tr += '<div class="row">';
  tr += '<div class="col-sm-6">';
  tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icone</a>';
  tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
  tr += '</div>';
  tr += '<div class="col-sm-6">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
  tr += '</div>';
  tr += '</div>';
  if(init(_cmd.logicalId) != 'refresh'){
    tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par défaut la commande}}">';
    tr += '<option value="">Aucune</option>';
    tr += '</select>';
  }
  tr += '</td>';
  tr += '<td>';

  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
  tr += '</td>';
  tr += '<td>';
  if(init(_cmd.logicalId) != 'refresh'){
    /* Action Type */
    tr += '<span class="actionType">';
      tr += '<div class="col-xs-6">';
        tr += '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="actionArgument">';
          tr += '<option value="IO" class="actionArgument">{{IO}}</option>';
          tr += '<option value="Ana" class="actionArgument">{{Analog}}</option>';
        tr += '</select>';
      tr += '</div>';
      /***/

      tr += '<div class="row" style="margin-top:5px;">';

        /* Action Parameter */
        tr += '<div class="col-xs-6">';
          tr += '<input class="cmdAttr form-control actionParameter IO input-sm" data-l1key="configuration" data-l2key="actionParameterIO" placeholder="{{IO id}}" style="display:none;" />';
          tr += '<input class="cmdAttr form-control actionParameter Ana input-sm" data-l1key="configuration" data-l2key="actionParameterAna" placeholder="{{Analog id}}" style="display:none;" />';
        tr += '</div>';
        /***/

        /* Action Option */
        tr += '<div class="col-xs-6">';
          tr += '<input class="cmdAttr form-control actionOption Ana input-sm" data-l1key="configuration" data-l2key="actionOptionAna" placeholder="{{Valeur}}" style="display:none;" />';
        tr += '</div>';
        /***/

      tr += '</div>';
    tr += '</span>';
    /***/

    /* Info Type */
    tr += '<span class="infoType">';
      tr += '<div class="row">';
        /* Info Type Arg */
        tr += '<div class="col-xs-6">';
        tr += '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="infoType">';
          tr += '<option value="IO" class="actionArgument">{{IO}}</option>';
          tr += '<option value="Ana" class="actionArgument">{{Analog}}</option>';
        tr += '</select>';
        tr += '</div>';
        /***/

        /* Info Parameter */
        tr += '<div class="col-xs-6">';
          tr += '<input class="cmdAttr form-control infoParameter IO input-sm" data-l1key="configuration" data-l2key="infoParameterIO" placeholder="{{IO id}}" style="display:none;" />';
          tr += '<input class="cmdAttr form-control infoParameter Ana input-sm" data-l1key="configuration" data-l2key="infoParameterAna" placeholder="{{Analog ID}}" style="display:none;" />';
        tr += '</div>';
        /***/
      tr += '</div>';
    tr += '</span>';
    /***/
  }
  tr += '</td>';

  tr += '<td>';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;display:inline-block;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;display:inline-block;">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;display:inline-block;margin-left:2px;">';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
  tr += '</td>';
  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
  tr += '</td>';
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);

  var tr = $('#table_cmd tbody tr').last();
  jeedom.eqLogic.builSelectCmd({
    id:  $('.eqLogicAttr[data-l1key=id]').value(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
      tr.find('.cmdAttr[data-l1key=configuration][data-l2key=infoType]').trigger('change')
    }
  });
}
