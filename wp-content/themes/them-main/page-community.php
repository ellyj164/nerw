<?php
/**
 * Template Name: Community Hub
 * Description: Community hub page with tabs for each level
 *
 * @package French_Practice_Hub
 */

get_header();
?>

<main class="community-hub-page">
    <div class="container">
        <!-- Header Section -->
        <div class="community-hub-header">
            <h1><?php esc_html_e( 'Community Hub', 'french-practice-hub' ); ?></h1>
            <p><?php esc_html_e( 'Connect with fellow French learners, share experiences, and grow together', 'french-practice-hub' ); ?></p>
        </div>

        <?php
        // Get all communities
        $communities = fph_get_communities();
        
        if ( ! empty( $communities ) ) :
        ?>
            <!-- Community Tabs -->
            <div class="community-tabs">
                <?php foreach ( $communities as $index => $community ) : ?>
                    <button class="community-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                            data-tab="community-<?php echo esc_attr( $community['slug'] ); ?>">
                        <?php echo esc_html( $community['icon'] . ' ' . $community['title'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Community Tab Contents -->
            <?php foreach ( $communities as $index => $community ) : 
                $is_member = is_user_logged_in() && fph_is_member( get_current_user_id(), $community['slug'] );
            ?>
                <div id="community-<?php echo esc_attr( $community['slug'] ); ?>" 
                     class="community-tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
                    
                    <!-- Community Info Panel -->
                    <div class="community-info-panel">
                        <div class="community-info-header">
                            <span class="community-info-icon"><?php echo esc_html( $community['icon'] ); ?></span>
                            <div class="community-info-details">
                                <h2><?php echo esc_html( $community['title'] ); ?> (<?php echo esc_html( $community['level_range'] ); ?>)</h2>
                                <p><?php echo esc_html( $community['description'] ); ?></p>
                                <p class="community-member-count">
                                    <?php 
                                    echo esc_html( 
                                        sprintf( 
                                            _n( '%s member', '%s members', $community['member_count'], 'french-practice-hub' ), 
                                            number_format_i18n( $community['member_count'] ) 
                                        ) 
                                    ); 
                                    ?>
                                </p>
                            </div>
                        </div>
                        <?php if ( is_user_logged_in() ) : ?>
                            <button class="community-action-btn <?php echo $is_member ? 'joined' : ''; ?>" 
                                    data-community="<?php echo esc_attr( $community['slug'] ); ?>">
                                <?php echo $is_member ? esc_html__( 'Leave Community', 'french-practice-hub' ) : esc_html__( 'Join Community', 'french-practice-hub' ); ?>
                            </button>
                        <?php else : ?>
                            <p style="margin-top: 16px; color: #64748b;">
                                <?php esc_html_e( 'Please', 'french-practice-hub' ); ?>
                                <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" style="text-decoration: underline;">
                                    <?php esc_html_e( 'log in', 'french-practice-hub' ); ?>
                                </a>
                                <?php esc_html_e( 'to join this community.', 'french-practice-hub' ); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php if ( $is_member ) : ?>
                        <!-- New Discussion Form -->
                        <div class="new-discussion-form" data-community="<?php echo esc_attr( $community['slug'] ); ?>">
                            <h3><?php esc_html_e( 'Start a New Discussion', 'french-practice-hub' ); ?></h3>
                            <form>
                                <div class="form-group">
                                    <label for="discussion-title-<?php echo esc_attr( $community['slug'] ); ?>">
                                        <?php esc_html_e( 'Title', 'french-practice-hub' ); ?> *
                                    </label>
                                    <input type="text" 
                                           id="discussion-title-<?php echo esc_attr( $community['slug'] ); ?>" 
                                           name="discussion_title" 
                                           placeholder="<?php esc_attr_e( 'What would you like to discuss?', 'french-practice-hub' ); ?>" 
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="discussion-content-<?php echo esc_attr( $community['slug'] ); ?>">
                                        <?php esc_html_e( 'Content', 'french-practice-hub' ); ?> *
                                    </label>
                                    <textarea id="discussion-content-<?php echo esc_attr( $community['slug'] ); ?>" 
                                              name="discussion_content" 
                                              placeholder="<?php esc_attr_e( 'Share your thoughts, questions, or experiences...', 'french-practice-hub' ); ?>" 
                                              required></textarea>
                                </div>
                                <button type="submit"><?php esc_html_e( 'Post Discussion', 'french-practice-hub' ); ?></button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Discussions List -->
                    <div class="discussions-list">
                        <?php
                        // Get discussions for this community
                        $discussions = get_posts( array(
                            'post_type'      => 'fph_discussion',
                            'posts_per_page' => 20,
                            'meta_key'       => 'community_level',
                            'meta_value'     => $community['slug'],
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ) );

                        if ( ! empty( $discussions ) ) :
                            foreach ( $discussions as $discussion ) :
                                $author = get_userdata( $discussion->post_author );
                                $comment_count = wp_count_comments( $discussion->ID );
                                ?>
                                <div class="discussion-item" data-discussion-id="<?php echo esc_attr( $discussion->ID ); ?>">
                                    <div class="discussion-header">
                                        <img src="<?php echo esc_url( get_avatar_url( $discussion->post_author ) ); ?>" 
                                             alt="<?php echo esc_attr( $author->display_name ); ?>" 
                                             class="discussion-avatar">
                                        <div class="discussion-meta">
                                            <h4><?php echo esc_html( $discussion->post_title ); ?></h4>
                                            <div class="discussion-author-info">
                                                <span><?php echo esc_html( $author->display_name ); ?></span> • 
                                                <span><?php echo esc_html( get_the_date( '', $discussion ) ); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="discussion-content">
                                        <?php echo wp_kses_post( $discussion->post_content ); ?>
                                    </div>
                                    <div class="discussion-footer">
                                        <span class="discussion-comments-count">
                                            💬 <?php echo esc_html( $comment_count->approved ); ?> <?php esc_html_e( 'comments', 'french-practice-hub' ); ?>
                                        </span>
                                    </div>

                                    <!-- Comments Section -->
                                    <div class="comments-section" style="display: none;">
                                        <div class="comment-list">
                                            <?php
                                            // Get comments for this discussion
                                            $comments = get_comments( array(
                                                'post_id' => $discussion->ID,
                                                'status'  => 'approve',
                                                'orderby' => 'comment_date',
                                                'order'   => 'ASC',
                                            ) );

                                            if ( ! empty( $comments ) ) :
                                                foreach ( $comments as $comment ) :
                                                    ?>
                                                    <div class="comment-item">
                                                        <img src="<?php echo esc_url( get_avatar_url( $comment->user_id ) ); ?>" 
                                                             alt="<?php echo esc_attr( $comment->comment_author ); ?>" 
                                                             class="comment-avatar">
                                                        <div class="comment-content-wrapper">
                                                            <div class="comment-author">
                                                                <?php echo esc_html( $comment->comment_author ); ?>
                                                                <span class="comment-date"><?php echo esc_html( get_comment_date( '', $comment ) ); ?></span>
                                                            </div>
                                                            <p class="comment-text"><?php echo esc_html( $comment->comment_content ); ?></p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </div>

                                        <?php if ( $is_member ) : ?>
                                            <form class="comment-form" data-discussion-id="<?php echo esc_attr( $discussion->ID ); ?>">
                                                <textarea name="comment_content" placeholder="<?php esc_attr_e( 'Write a comment...', 'french-practice-hub' ); ?>" required></textarea>
                                                <button type="submit"><?php esc_html_e( 'Post Comment', 'french-practice-hub' ); ?></button>
                                            </form>
                                        <?php else : ?>
                                            <p style="margin-top: 16px; color: #64748b; font-size: 0.9rem;">
                                                <?php esc_html_e( 'Join this community to participate in discussions.', 'french-practice-hub' ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else :
                            ?>
                            <div class="empty-state">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <h3><?php esc_html_e( 'No discussions yet', 'french-practice-hub' ); ?></h3>
                                <p><?php esc_html_e( 'Be the first to start a discussion in this community!', 'french-practice-hub' ); ?></p>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else : ?>
            <div class="empty-state">
                <h3><?php esc_html_e( 'No communities available', 'french-practice-hub' ); ?></h3>
                <p><?php esc_html_e( 'Please check back later.', 'french-practice-hub' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
