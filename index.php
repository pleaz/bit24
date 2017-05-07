<html>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

<?

if(!empty($_REQUEST['route'])){

    require_once ('init.php');

    $q = $db->escape($_REQUEST['route']);
    $gg = $db->where ('phone', '%'.$q.'%', 'like');
    $results = $db->get('users');
    $arr = ['use_count' => $results['0']['use_count']+1];

    $detect = new Mobile_Detect;
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

    if($results['0']['use_count']==0) { $arr['first_place'] = date("Y-m-d H:i:s"); $arr['first_brows'] = $deviceType; }
    $db->where ('id', $results['0']['id']);
    $db->update ('users', $arr);
    if($results==null) die('404');
?>
    Здравствуйте, <?=$results['0']['name'];?><br/>
    <form id="searchForm" action="ajax.php" method="post">
        Сообщение: <textarea name="k"></textarea>
        <input type="hidden" name="id" value="<?=$results['0']['id'];?>" />
        <input type="hidden" name="name" value="<?=$results['0']['name'];?>" />
        <input type="hidden" name="phone" value="<?=$results['0']['phone'];?>" />
        <input type="submit" value="Ввод" />
    </form>
    <div id="result"></div>

    <script>
        $(function() {
            $("#searchForm").submit(function (e) {
                e.preventDefault();
                var $form = $(this),
                    term = $form.find("textarea[name='k']").val(),
                    idd = $form.find("input[name='id']").val(),
                    nam = $form.find("input[name='name']").val(),
                    pho = $form.find("input[name='phone']").val(),
                    url = $form.attr("action");
                var posting = $.post(url, {k: term, n: nam, p: pho, i: idd});
                posting.done(function (data) {
                    if($.isNumeric(data.result)===true) { $("#result").empty().html('Успешно отправлено!'); }
                });
            });
        });
    </script>
<?

    die();

}


?>

<form id="searchForm" action="ajax.php" method="post">
    Номер телефона: <input type="text" name="q" value =""/>
    <input type="submit" value="Ввод" />
</form>
<div id="result"></div>

<script>
    $(function() {
        $("#searchForm").submit(function (e) {
            e.preventDefault();
            var $form = $(this),
                term = $form.find("input[name='q']").val(),
                url = $form.attr("action");
            var posting = $.post(url, {q: term});
            posting.done(function (data) {
                if(data.st===true){ $( location ).attr("href", '/'+data.tel); }
                $("#result").empty().html(data);
            });
        });
    });
</script>
</body>





