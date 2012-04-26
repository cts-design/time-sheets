<h1><?php echo $programName ?></h1>
<ol>
    <?php foreach ($answers as $k => $v) : ?>
        <li><?php echo Inflector::humanize($k) . ': ' . Inflector::humanize($v) ?></li>
    <?php endforeach ?>
</ol>

