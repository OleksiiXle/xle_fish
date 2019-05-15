<b>lokoko</b>
<table class="table table-bordered">
    <?php foreach($postMedia as $item): ?>
        <tr>
            <td><?=$item->name;?></td>
            <td><?=$item->file_name;?></td>
            <td><img src="<?=$item->urlToFile;?>" height="70 px" width="auto"></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
</table>