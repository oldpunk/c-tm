<script language="javascript" type="text/javascript"><!--
function permcheckusers(form)
{
  inner = document.getElementById('inner[]').options;
  for(i=inner.length-1; i!=-1; i--)  { inner[i].selected = true; }
  return true;
}
function permuseradd()
{
  outer = document.getElementById('outer[]').options;
  inner = document.getElementById('inner[]').options;
  for(i=outer.length-1; i!=-1; i--)
  {
    if(outer[i].selected)
    {
      opt = new Option(outer[i].text, outer[i].value, false, true);
      opt.className = outer[i].className;
      inner.add(opt);
      outer[i].selected = false;
      outer[i] = null;
    }
  }
  return false;
}
function permuserremove()
{
  inner = document.getElementById('inner[]').options;
  outer = document.getElementById('outer[]').options;
  for(i=inner.length-1; i!=-1; i--)
  {
    if(inner[i].selected)
    {
      opt = new Option(inner[i].text, inner[i].value, false, true);
      opt.className = inner[i].className;
      outer.add(opt);
      inner[i].selected = false;
      inner[i] = null;
    }
  }
  return false;
}
function permoncancel()
{
  document.location='/admin/?mod=perm';
  return false;
}
//--></script>
<h4 align="center"><?=htmlspecialchars($args['title'])?></h4>
<form name="users" id="users" method="post" action="/admin/?mod=perm&amp;act=users&amp;id=<?=htmlspecialchars($args['id'])?>" onsubmit="return (permcheckusers(this) && submitonce(this))">
<table width="450" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
<thead>
<tr>
  <th>запрещено</th>
  <th>&nbsp;</th>
  <th>разрешено</th>
</tr>
</thead>
<tr>
  <th width="48%">
    <select id="outer[]" style="width:100%;height:250px" ondblclick="permuseradd()" multiple="multiple">
<?foreach($args['users'] as $i) if(! in_array($i['id'], $args['inner'])) {?>
      <option value="<?=$i['id']?>" class="<?=($i['blocked']? 'hidden' : '')?>"><?=htmlspecialchars($i['login'])?></option>
<?}?>
    </select>
  </th>
  <th width="2%">
    <input type="button" class="button" style="width:30px" value="&gt;" onclick="return permuseradd()" />
    <br /><br />
    <input type="button" class="button" style="width:30px" value="&lt;" onclick="return permuserremove()" />
  </th>
  <th width="48%">
    <select name="inner[]" id="inner[]" style="width:100%;height:250px" ondblclick="permuserremove()" multiple="multiple">
<?foreach($args['users'] as $i) if(in_array($i['id'], $args['inner'])) {?>
      <option value="<?=$i['id']?>" class="<?=($i['blocked']? 'hidden' : '')?>"><?=htmlspecialchars($i['login'])?></option>
<?}?>
    </select>
  </th>
</tr>
<tr>
  <td class="none" align="center" colspan="3">
    <input type="submit" value="Сохранить!" class="button" />
    <input type="reset" value="Отменить" onclick="permoncancel()" class="button" />
  </td>
</tr>
</table>
</form>