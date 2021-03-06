window.onload = function()
{
  if(parseInt(getcookie('sidebarhide'))) { onsidebar(); }
  if(parseInt(getcookie('sectionmoduleshide'))) { onmodsection('sectionmodules'); }
  if(parseInt(getcookie('sectiontoolshide'))) { onmodsection('sectiontools'); }
  if(parseInt(getcookie('sectionstructhide'))) { onmodsection('sectionstruct'); }
  if(parseInt(getcookie('sectionaccesshide'))) { onmodsection('sectionaccess'); }
  if(parseInt(getcookie('sectionloghide'))) { onmodsection('sectionlog'); }
}
$(document).ready(function(){
    $('.table tr').mouseover(function(){
        $(this).addClass('hover');
    }).mouseout(function(){
        $(this).removeClass('hover');
    });
    
    $( "#datepicker, .datepicker" ).datepicker({
        showOn: "button",
        buttonImage: "/admin/images/calendar.png",
        buttonImageOnly: true,
        buttonText:''
    });
    $("a.lightbox").fancybox();
    $('.del_fr_foto').live('click',function(){
        var img = $(this).attr('rel'),
        cat = $(this).attr('cat'),
        tbl = $(this).attr('tbl'),
        link = $(this).attr('link'),
        obj = $(this);
        $.post('/ajax/delimg.php', {img:img,cat:cat,tbl:tbl,link:link}, function(data){
            if(data == 1)
            {
                $(obj).parent().remove();
            }
            else
                alert('Ошибка доступа');
        })
        return false;
    });
    
    $('.del_fr_foto1').live('click',function(){
        var img = $(this).attr('rel'),
        cat = $(this).attr('cat'),
        link = $(this).attr('link'),
        obj = $(this),
        tbl = $(this).attr('tbl');
        $.post('/ajax/delimg_1.php', {img:img,cat:cat,tbl:tbl,link:link}, function(data){
            if(data == 1)
            {
                $(obj).parent().remove();
            }
            else
                alert('Ошибка доступа');
        })
        return false;
    });
    
    $('.closeflush').click(function(){
       $(this).parent('.alert').fadeOut(500); 
    });
    
    $('.col_3_top a').click(function(){
        var bg = $(this).attr('class');
        if(bg)
            $('body').css('background','url(/admin/images/'+bg+'.jpg)');
        else
            $('body').css('background','');
        $.post('/admin/ajax.php',{act:'bg', bg:bg},function(){})
        return false;
    });
    
    // scroll body to 0px on click
    $('#back-top a').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 700);
        return false;
    });
});


function onsidebar()
{
  var sidebar = null;
  var spliter = null;
  var workspace = null;
  sidebar = document.getElementById('sidebar');
  spliter = document.getElementById('spliter');
  workspace = document.getElementById('workspace');
  if(sidebar && spliter && workspace)
  {
    if(String(sidebar.style.display).toLowerCase()=='none')
    {
      setcookie('sidebarhide', '0');
      sidebar.style.display = 'block';
      sidebar.width = '29%';
      spliter.width = '1%';
      workspace.width = '70%'
    }
    else
    {
      setcookie('sidebarhide', '1');
      sidebar.style.display = 'none';
      sidebar.width = '1%';
      spliter.width = '1%';
      workspace.width = '98%'
    }
  }
}

function onmodsection(id)
{
  var section = null;
  section = document.getElementById(id);
  if(section)
  {
    if(String(section.style.display).toLowerCase()=='none')
    {
      section.style.display = 'block';
      setcookie(id+'hide', '0');
    }
    else
    {
      section.style.display = 'none';
      setcookie(id+'hide', '1');
    }

  }
}

function getcookie(name)
{
  var reg = new RegExp('(\;|^)[^;]*('+name+')\=([^;]*)(;|$)');
  var res = reg.exec(document.cookie);
  return (res!=null? unescape(res[3]) : null);
}

function onpanel()
{
  var o, i, n, a = onpanel.arguments;
  for(i = 0, n = a.length; i < n; i++)
  {
    o = document.getElementById(a[i]);
    if(!o) continue;
    if(String(o.style.display).toLowerCase()=='none')
    {
      o.style.display = 'block'; 
    }
    else
    {
      o.style.display = 'none';
    }
  }
}

function setcookie(name, value, time)
{
  if(!time) { time = 3600*24*30; }
  var expire = new Date();
  expire.setTime(expire.getTime() + time);
  document.cookie = escape(name) + '=' + escape(value) + ';expires=' + expire.toGMTString() + '; path=/';
}