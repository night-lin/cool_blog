<?php

    if (isset($_GET["action"]) && $_GET["action"] == "ajax_comments") {
        $this->need('comments.php');
    } else {
        if(strpos($_SERVER["PHP_SELF"],"themes")) header('Location:/');
        $this->need('header.php');

?>

<div class="main">
    <article class="block post">
        <span class="round-date <?php $this->options->labelColor() ?>">
            <span class="month"><?php $this->date('m月'); ?></span>
            <span class="day"><?php $this->date('d'); ?></span>
        </span>
        <p class="title"><?php $this->title() ?></p>
        <p class="article-meta">本文由 <a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?> </a><?php $this->date('发表于 Y 年 m 月 d 日'); ?></p>
        <div class="article-content">
            <?php $this->content(); ?>
        </div>
        <?php if($this->allow('ping')): ?>
            <div class="copyright">
                <div role="alert" class="alert">本文由 <a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a> 创作，采用 <a rel="external nofollow" href="http://creativecommons.org/licenses/by/3.0/cn" target="_blank">知识共享署名 3.0</a> 中国大陆许可协议 进行许可。 <br>可自由转载、引用，但需署名作者且注明文章出处。</div>
            </div>
        <?php endif; ?>
        <p class="tags"><?php _e('标签：'); ?><?php $this->tags(', ', true, 'none'); ?></p>
    </article>
    

    <?php $this->need('comments.php'); ?>

    <div class="block">
    <ul class="post-near">
        <li>上一篇: <?php $this->thePrev('%s','没有了'); ?></li>
        <li>下一篇: <?php $this->theNext('%s','没有了'); ?></li>
    </ul>
</div>
</div>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
<?php } ?>
