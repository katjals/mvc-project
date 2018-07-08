<p>Here is a list of all bikes:</p>

<?php foreach($bikes as $bike){ ?>
    <p>
        <?php echo $bike->title; ?>
        <a href="?controller=bikes&action=show&id=<?php echo $bike->id; ?>">See content</a>
    </p>
<?php } ?>