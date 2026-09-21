<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Public article reader.
require_once("include/initialize.php");
global $mydb;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;   // cast to int = safe

$article = null;
$blocks  = array();
if ($id > 0 && $mydb->tableExists('tblnews')) {
    $mydb->setQuery("SELECT * FROM tblnews WHERE NEWS_ID = " . $id . " AND STATUS = 'Published' LIMIT 1");
    $article = $mydb->loadSingleResult();
    if ($article && $mydb->tableExists('tblnews_media')) {
        $mydb->setQuery("SELECT * FROM tblnews_media WHERE NEWS_ID = " . $id . " ORDER BY SORT_ORDER, MEDIA_ID");
        $blocks = $mydb->loadResultList();
    }
}

function sjcs_img($path) { return WEB_ROOT . ltrim($path, '/'); }

$PAGE_TITLE = $article ? ($article->TITLE . ' - St. Joseph CSSI') : 'Article Not Found';
$NAV_MODE   = 'min';
require_once(__DIR__ . '/theme/public_header.php');
?>

<section class="sjcs-section">
  <div class="sjcs-article">
    <?php if (!$article): ?>
      <div class="sjcs-center">
        <h2 class="sjcs-section-title">Article not found</h2>
        <p class="sjcs-muted">This article may have been unpublished or removed.</p>
        <a href="<?php echo WEB_ROOT; ?>#news" class="sjcs-btn sjcs-btn-primary">Back to News</a>
      </div>
    <?php else: ?>
      <a class="sjcs-back" href="<?php echo WEB_ROOT; ?>#news"><i class="fas fa-arrow-left"></i> Back to News</a>

      <span class="sjcs-news-category"><?php echo htmlspecialchars($article->CATEGORY ?: 'News'); ?></span>
      <h1 class="sjcs-article-title"><?php echo htmlspecialchars($article->TITLE); ?></h1>
      <?php if (!empty($article->SUBTITLE)): ?>
        <p class="sjcs-article-sub"><?php echo htmlspecialchars($article->SUBTITLE); ?></p>
      <?php endif; ?>
      <p class="sjcs-article-meta">
        By <?php echo htmlspecialchars($article->AUTHOR ?: 'St. Joseph CSSI'); ?>
        <?php if (!empty($article->DATE_PUBLISHED)): ?> &middot; <?php echo date('F j, Y', strtotime($article->DATE_PUBLISHED)); ?><?php endif; ?>
      </p>

      <?php if (!empty($article->FEATURED_IMAGE)): ?>
        <div class="sjcs-article-hero">
          <img src="<?php echo sjcs_img($article->FEATURED_IMAGE); ?>" alt="<?php echo htmlspecialchars($article->TITLE); ?>">
        </div>
      <?php endif; ?>

      <div class="sjcs-article-body">
        <?php if (empty($blocks)): ?>
          <p class="sjcs-muted">No content has been added to this article yet.</p>
        <?php else: foreach ($blocks as $b):
            $type = strtolower($b->BLOCK_TYPE);
            if ($type === 'image' && !empty($b->FILE_PATH)): ?>
              <figure class="sjcs-article-figure">
                <img src="<?php echo sjcs_img($b->FILE_PATH); ?>" alt="">
                <?php if (!empty($b->TEXT_CONTENT)): ?><figcaption><?php echo htmlspecialchars($b->TEXT_CONTENT); ?></figcaption><?php endif; ?>
              </figure>
          <?php elseif ($type === 'video' && !empty($b->VIDEO_URL)): ?>
              <div class="sjcs-video-frame">
                <video controls playsinline preload="metadata">
                  <source src="<?php echo htmlspecialchars($b->VIDEO_URL); ?>" type="video/mp4">
                </video>
              </div>
          <?php else:
              // Text / Caption: keep the author's line breaks, split into paragraphs
              $text = trim((string)$b->TEXT_CONTENT);
              if ($text !== ''):
                  $paras = preg_split('/\n\s*\n/', $text);
                  foreach ($paras as $para):
                      $para = trim($para);
                      if ($para === '') continue; ?>
                      <p><?php echo nl2br(htmlspecialchars($para)); ?></p>
              <?php endforeach;
              endif;
            endif;
          endforeach; endif; ?>
      </div>

      <div class="sjcs-article-foot">
        <a href="<?php echo WEB_ROOT; ?>#news" class="sjcs-btn sjcs-btn-outline">More News</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once(__DIR__ . '/theme/public_footer.php'); ?>
