<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::addIncludePath(  JPATH_COMPONENT . '/helpers/html'  );

$params = $this->params;
?>

<div id="archive-items" class="uk-child-width-1-1" data-uk-grid>
    
<?php
foreach ( $this->items as $i => $item )
{
    $info = $item->params->get( 'info_block_position', 0 );
    ?>
    <div itemscope itemtype="https://schema.org/Article">
        <h2 itemprop="headline">
            <?php if ( $params->get( 'link_titles' ) ) { ?>
            <a href="<?php echo Route::_( ContentHelperRoute::getArticleRoute( $item->slug, $item->catid, $item->language ) ); ?>" itemprop="url"><?php echo $this->escape( $item->title ); ?></a>
            <?php } else { ?>
            <?php echo $this->escape( $item->title ); ?>
            <?php } ?>
        </h2>

        <?php
        // Content is generated by content plugin event "onContentAfterTitle"
        echo $item->event->afterDisplayTitle;

        $useDefList = ( $params->get( 'show_modify_date' ) || $params->get( 'show_publish_date' ) || $params->get( 'show_create_date' ) || $params->get( 'show_hits' ) || $params->get( 'show_category' ) || $params->get( 'show_parent_category' ) );
        
        if ( $useDefList && ( $info == 0 || $info == 2 ) )
        {
        ?>
        <div class="uk-article-meta">
            <dl class="uk-description-list">
                <dt class="article-info-term"><?php echo Text::_( 'COM_CONTENT_ARTICLE_INFO' ); ?></dt>
                
                <?php if ( $params->get( 'show_author' ) && !empty( $item->author  ) ) { ?>
                <dd class="uk-article-meta" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <?php
                    $author = $item->created_by_alias ?: $item->author;
                    $author = '<span itemprop="name">' . $author . '</span>';
                    if ( !empty( $item->contact_link ) && $params->get( 'link_author' ) == true )
                    {
                        echo Text::sprintf( 'COM_CONTENT_WRITTEN_BY', HTMLHelper::_( 'link', $this->item->contact_link, $author, array( 'itemprop' => 'url' ) ) );
                    }
                    else
                    {
                        echo Text::sprintf( 'COM_CONTENT_WRITTEN_BY', $author );
                    }
                    ?>
                </dd>
                <?php
                }

                if ( $params->get( 'show_parent_category' ) && !empty( $item->parent_slug ) )
                {
                ?>
                <dd>
                    <?php
                    $title = $this->escape( $item->parent_title );
                    if ( $params->get( 'link_parent_category' ) && !empty( $item->parent_slug ) )
                    {
                        $url = '<a href="' . Route::_( ContentHelperRoute::getCategoryRoute( $item->parent_slug ) ) . '" itemprop="genre">' . $title . '</a>';
                        echo Text::sprintf( 'COM_CONTENT_PARENT', $url );
                    }
                    else
                    {
                        echo Text::sprintf( 'COM_CONTENT_PARENT', '<span itemprop="genre">' . $title . '</span>' );
                    }
                    ?>
                </dd>
                <?php
                }
                
                if ( $params->get( 'show_category' ) )
                {
                ?>
                <dd>
                    <?php
                    $title = $this->escape( $item->category_title );
                    if ( $params->get( 'link_category' ) && $item->catslug )
                    {
                        $url = '<a href="' . Route::_( ContentHelperRoute::getCategoryRoute( $item->catslug ) ) . '" itemprop="genre">' . $title . '</a>';
                        echo Text::sprintf( 'COM_CONTENT_CATEGORY', $url );
                    }
                    else
                    {
                        echo Text::sprintf( 'COM_CONTENT_CATEGORY', '<span itemprop="genre">' . $title . '</span>' );
                    }
                    ?>
                </dd>
                <?php
                }

                if ( $params->get( 'show_publish_date' ) )
                {
                ?>
                <dd>
                    <time datetime="<?php echo HTMLHelper::_( 'date', $item->publish_up, 'c' ); ?>" itemprop="datePublished">
                        <?php echo Text::sprintf( 'COM_CONTENT_PUBLISHED_DATE_ON', HTMLHelper::_( 'date', $item->publish_up, Text::_( 'd.m.Y' ) ) ); ?>
                    </time>
                </dd>
                <?php
                }

                if ( $info == 0 )
                {
                    if ( $params->get( 'show_modify_date' ) )
                    {
                ?>
                <dd>
                    <time datetime="<?php echo HTMLHelper::_( 'date', $item->modified, 'c' ); ?>" itemprop="dateModified">
                        <?php echo Text::sprintf( 'COM_CONTENT_LAST_UPDATED', HTMLHelper::_( 'date', $item->modified, Text::_( 'd.m.Y' ) ) ); ?>
                    </time>
                </dd>
                <?php
                    }
                    
                    if ( $params->get( 'show_create_date' ) )
                    {
                ?>
                <dd>
                    <time datetime="<?php echo HTMLHelper::_( 'date', $item->created, 'c' ); ?>" itemprop="dateCreated">
                        <?php echo Text::sprintf( 'COM_CONTENT_CREATED_DATE_ON', HTMLHelper::_( 'date', $item->created, Text::_( 'd.m.Y' ) ) ); ?>
                    </time>
                </dd>
                <?php
                    }

                    if ( $params->get( 'show_hits' ) )
                    {
                ?>
                <dd>
                    <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>" />
                    <?php echo Text::sprintf( 'COM_CONTENT_ARTICLE_HITS', $item->hits ); ?>
                </dd>
                <?php
                    }
                }
                ?>
            </dl>
        </div>
        <?php
        }


        // Content is generated by content plugin event "onContentBeforeDisplay"
        echo $item->event->beforeDisplayContent;
        if ( $params->get( 'show_intro' ) )
        {
        ?>
        <div class="intro" itemprop="articleBody"> <?php echo HTMLHelper::_( 'string.truncateComplex', $item->introtext, $params->get( 'introtext_limit' ) ); ?> </div>
        <?php
        }


        if ( $useDefList && ( $info == 1 || $info == 2 ) )
        {
        ?>
        <div class="uk-article-meta">
            <dl class="uk-description-list">
                <dt class="article-info-term"><?php echo Text::_( 'COM_CONTENT_ARTICLE_INFO' ); ?></dt>
                
                <?php if ( $params->get( 'show_author' ) && !empty( $item->author  ) ) { ?>
                <dd class="uk-article-meta" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <?php
                    $author = $item->created_by_alias ?: $item->author;
                    $author = '<span itemprop="name">' . $author . '</span>';
                    if ( !empty( $item->contact_link ) && $params->get( 'link_author' ) == true )
                    {
                        echo Text::sprintf( 'COM_CONTENT_WRITTEN_BY', HTMLHelper::_( 'link', $this->item->contact_link, $author, array( 'itemprop' => 'url' ) ) );
                    }
                    else
                    {
                        echo Text::sprintf( 'COM_CONTENT_WRITTEN_BY', $author );
                    }
                    ?>
                </dd>
                <?php
                }

                if ( $params->get( 'show_parent_category' ) && !empty( $item->parent_slug ) )
                {
                ?>
                <dd>
                    <?php
                    $title = $this->escape( $item->parent_title );
                    if ( $params->get( 'link_parent_category' ) && !empty( $item->parent_slug ) )
                    {
                        $url = '<a href="' . Route::_( ContentHelperRoute::getCategoryRoute( $item->parent_slug ) ) . '" itemprop="genre">' . $title . '</a>';
                        echo Text::sprintf( 'COM_CONTENT_PARENT', $url );
                    }
                    else
                    {
                        echo Text::sprintf( 'COM_CONTENT_PARENT', '<span itemprop="genre">' . $title . '</span>' );
                    }
                    ?>
                </dd>
                <?php
                }
                
                if ( $params->get( 'show_category' ) )
                {
                ?>
                <dd>
                    <?php
                    $title = $this->escape( $item->category_title );
                    if ( $params->get( 'link_category' ) && $item->catslug )
                    {
                        $url = '<a href="' . Route::_( ContentHelperRoute::getCategoryRoute( $item->catslug ) ) . '" itemprop="genre">' . $title . '</a>';
                        echo Text::sprintf( 'COM_CONTENT_CATEGORY', $url );
                    }
                    else
                    {
                        echo Text::sprintf( 'COM_CONTENT_CATEGORY', '<span itemprop="genre">' . $title . '</span>' );
                    }
                    ?>
                </dd>
                <?php
                }

                if ( $params->get( 'show_publish_date' ) )
                {
                ?>
                <dd>
                    <time datetime="<?php echo HTMLHelper::_( 'date', $item->publish_up, 'c' ); ?>" itemprop="datePublished">
                        <?php echo Text::sprintf( 'COM_CONTENT_PUBLISHED_DATE_ON', HTMLHelper::_( 'date', $item->publish_up, Text::_( 'd.m.Y' ) ) ); ?>
                    </time>
                </dd>
                <?php
                }

                if ( $params->get( 'show_modify_date' ) )
                {
                ?>
                <dd>
                    <time datetime="<?php echo HTMLHelper::_( 'date', $item->modified, 'c' ); ?>" itemprop="dateModified">
                        <?php echo Text::sprintf( 'COM_CONTENT_LAST_UPDATED', HTMLHelper::_( 'date', $item->modified, Text::_( 'd.m.Y' ) ) ); ?>
                    </time>
                </dd>
                <?php
                }
                    
                if ( $params->get( 'show_create_date' ) )
                {
                ?>
                <dd>
                    <time datetime="<?php echo HTMLHelper::_( 'date', $item->created, 'c' ); ?>" itemprop="dateCreated">
                        <?php echo Text::sprintf( 'COM_CONTENT_CREATED_DATE_ON', HTMLHelper::_( 'date', $item->created, Text::_( 'd.m.Y' ) ) ); ?>
                    </time>
                </dd>
                <?php
                }

                if ( $params->get( 'show_hits' ) )
                {
                ?>
                <dd>
                    <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>" />
                    <?php echo Text::sprintf( 'COM_CONTENT_ARTICLE_HITS', $item->hits ); ?>
                </dd>
                <?php } ?>
            </dl>
        </div>
        <?php
        }

        
        // Content is generated by content plugin event "onContentAfterDisplay"
        echo $item->event->afterDisplayContent;
        ?>
    </div>
<?php } ?>
</div>

<?php
// Add pagination links
if ( !empty( $this->items ) )
{
    $show_pagination = $this->params->def( 'show_pagination', 2 ) == 1 || ( $this->params->get( 'show_pagination' ) == 2 );
    $show_pagination_results = $this->params->def( 'show_pagination_results', 1 );

    if ( $show_pagination && ( $this->pagination->pagesTotal > 1 ) )
    {
?>
<div class="uk-margin-top uk-flex uk-flex-center<?php if ( $show_pagination_results ) { echo ' uk-flex-between@s'; } ?>">

    <div><?php echo $this->pagination->getPagesLinks(); ?></div>

    <?php if ( $show_pagination_results ) { ?>
    <div><?php echo $this->pagination->getPagesCounter(); ?></div>
    <?php } ?>

</div>
<?php
    }
}
