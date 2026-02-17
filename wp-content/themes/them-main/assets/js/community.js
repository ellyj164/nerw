/**
 * Community Feature JavaScript
 * 
 * Handles AJAX for joining/leaving communities, posting discussions, and commenting
 */

(function($) {
    'use strict';

    // Community functionality
    const Community = {
        init: function() {
            this.bindEvents();
            this.initTabs();
        },

        bindEvents: function() {
            // Join/Leave community buttons
            $(document).on('click', '.community-card-join-btn, .community-action-btn', this.handleJoinLeave);
            
            // New discussion form
            $(document).on('submit', '.new-discussion-form', this.handleNewDiscussion);
            
            // Comment form
            $(document).on('submit', '.comment-form', this.handleNewComment);
            
            // Discussion item click (expand/collapse)
            $(document).on('click', '.discussion-item', this.toggleDiscussion);
        },

        initTabs: function() {
            // Community tabs switching
            $(document).on('click', '.community-tab', function(e) {
                e.preventDefault();
                
                const targetTab = $(this).data('tab');
                
                // Update active tab
                $('.community-tab').removeClass('active');
                $(this).addClass('active');
                
                // Show target content
                $('.community-tab-content').removeClass('active');
                $('#' + targetTab).addClass('active');
            });
        },

        handleJoinLeave: function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent triggering parent click events
            
            const $btn = $(this);
            const communitySlug = $btn.data('community');
            const isJoined = $btn.hasClass('joined');
            const action = isJoined ? 'fph_leave_community' : 'fph_join_community';
            
            // Check if user is logged in
            if (!fphCommunity.isLoggedIn && !isJoined) {
                Community.showLoginRequired();
                return;
            }
            
            // Disable button during request
            $btn.prop('disabled', true);
            
            $.ajax({
                url: fphCommunity.ajaxurl,
                type: 'POST',
                data: {
                    action: action,
                    community_slug: communitySlug,
                    nonce: fphCommunity.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update button state
                        if (isJoined) {
                            $btn.removeClass('joined').text('Join Community');
                        } else {
                            $btn.addClass('joined').text('Joined ✓');
                        }
                        
                        // Update member count
                        if (response.data.member_count !== undefined) {
                            const $memberCount = $btn.closest('.community-card, .community-info-panel')
                                .find('.community-card-members, .community-member-count');
                            
                            const memberText = response.data.member_count === 1 
                                ? '1 member' 
                                : response.data.member_count + ' members';
                            $memberCount.text(memberText);
                        }
                        
                        // Show success message
                        Community.showMessage(response.data.message, 'success');
                        
                        // Reload page if on community hub to update discussion permissions
                        if ($('.community-hub-header').length) {
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    } else {
                        Community.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    Community.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        },

        handleNewDiscussion: function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const communitySlug = $form.data('community');
            const title = $form.find('input[name="discussion_title"]').val().trim();
            const content = $form.find('textarea[name="discussion_content"]').val().trim();
            
            // Validate
            if (!title || !content) {
                Community.showMessage('Please fill in all fields.', 'error');
                return;
            }
            
            // Disable submit button
            $submitBtn.prop('disabled', true).text('Posting...');
            
            $.ajax({
                url: fphCommunity.ajaxurl,
                type: 'POST',
                data: {
                    action: 'fph_post_discussion',
                    community_slug: communitySlug,
                    title: title,
                    content: content,
                    nonce: fphCommunity.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Clear form
                        $form[0].reset();
                        
                        // Show success message
                        Community.showMessage(response.data.message, 'success');
                        
                        // Add new discussion to list
                        const discussion = response.data.discussion;
                        const $discussionHtml = Community.createDiscussionHTML(discussion);
                        
                        const $discussionsList = $form.closest('.community-tab-content').find('.discussions-list');
                        
                        // Remove empty state if exists
                        $discussionsList.find('.empty-state').remove();
                        
                        // Prepend new discussion
                        $discussionsList.prepend($discussionHtml);
                        
                        // Animate in
                        $discussionHtml.hide().slideDown(300);
                    } else {
                        Community.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    Community.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text('Post Discussion');
                }
            });
        },

        handleNewComment: function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent triggering parent click events
            
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const discussionId = $form.data('discussion-id');
            const content = $form.find('textarea[name="comment_content"]').val().trim();
            
            // Validate
            if (!content) {
                Community.showMessage('Please enter a comment.', 'error');
                return;
            }
            
            // Disable submit button
            $submitBtn.prop('disabled', true).text('Posting...');
            
            $.ajax({
                url: fphCommunity.ajaxurl,
                type: 'POST',
                data: {
                    action: 'fph_post_comment',
                    discussion_id: discussionId,
                    content: content,
                    nonce: fphCommunity.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Clear form
                        $form[0].reset();
                        
                        // Show success message (briefly)
                        Community.showMessage(response.data.message, 'success');
                        setTimeout(function() {
                            $('.community-message').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 2000);
                        
                        // Add new comment to comments section
                        const comment = response.data.comment;
                        const $commentHtml = Community.createCommentHTML(comment);
                        
                        const $commentsSection = $form.closest('.comments-section');
                        $commentsSection.find('.comment-list').append($commentHtml);
                        
                        // Animate in
                        $commentHtml.hide().slideDown(300);
                        
                        // Update comment count
                        const $discussionItem = $form.closest('.discussion-item');
                        const $commentCount = $discussionItem.find('.discussion-comments-count');
                        const currentCountText = $commentCount.text();
                        const currentCount = parseInt(currentCountText.match(/\d+/)) || 0;
                        $commentCount.html('💬 ' + (currentCount + 1) + ' comments');
                    } else {
                        Community.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    Community.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text('Post Comment');
                }
            });
        },

        toggleDiscussion: function(e) {
            // Don't toggle if clicking on buttons, forms, or links
            if ($(e.target).is('button, input, textarea, a, .comment-form')) {
                return;
            }
            if ($(e.target).closest('button, .comment-form, a').length) {
                return;
            }
            
            const $discussion = $(this);
            const $commentsSection = $discussion.find('.comments-section');
            
            // Toggle visibility
            $commentsSection.slideToggle(300);
        },

        createDiscussionHTML: function(discussion) {
            return $(`
                <div class="discussion-item" data-discussion-id="${discussion.id}">
                    <div class="discussion-header">
                        <img src="${discussion.author_avatar}" alt="${discussion.author_name}" class="discussion-avatar">
                        <div class="discussion-meta">
                            <h4>${discussion.title}</h4>
                            <div class="discussion-author-info">
                                <span>${discussion.author_name}</span> • 
                                <span>${discussion.date}</span>
                            </div>
                        </div>
                    </div>
                    <div class="discussion-content">
                        ${discussion.content}
                    </div>
                    <div class="discussion-footer">
                        <span class="discussion-comments-count">
                            💬 ${discussion.comment_count || 0} comments
                        </span>
                    </div>
                    <div class="comments-section" style="display: none;">
                        <div class="comment-list"></div>
                        <form class="comment-form" data-discussion-id="${discussion.id}">
                            <textarea name="comment_content" placeholder="Write a comment..." required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                    </div>
                </div>
            `);
        },

        createCommentHTML: function(comment) {
            return $(`
                <div class="comment-item">
                    <img src="${comment.author_avatar}" alt="${comment.author_name}" class="comment-avatar">
                    <div class="comment-content-wrapper">
                        <div class="comment-author">
                            ${comment.author_name}
                            <span class="comment-date">${comment.date}</span>
                        </div>
                        <p class="comment-text">${comment.content}</p>
                    </div>
                </div>
            `);
        },

        showLoginRequired: function() {
            const message = 'Please log in first to join this community.';
            const $messageHtml = $(`
                <div class="community-message info">
                    ${message}
                    <a href="${fphCommunity.loginUrl}" style="margin-left: 8px; text-decoration: underline;">Log In</a>
                </div>
            `);
            
            // Show at top of page
            if ($('.community-hub-header').length) {
                $('.community-hub-header').after($messageHtml);
            } else {
                $('body').prepend($messageHtml);
            }
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $messageHtml.offset().top - 100
            }, 500);
            
            // Auto-remove after 5 seconds
            setTimeout(function() {
                $messageHtml.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        },

        showMessage: function(message, type) {
            type = type || 'info';
            
            const $message = $(`<div class="community-message ${type}">${message}</div>`);
            
            // Remove any existing messages
            $('.community-message').remove();
            
            // Insert message
            if ($('.community-hub-header').length) {
                $('.community-hub-header').after($message);
            } else if ($('.cta-buttons-section').length) {
                $('.cta-buttons-section h3').after($message);
            } else {
                $('body').prepend($message);
            }
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $message.offset().top - 100
            }, 500);
            
            // Auto-remove after 5 seconds
            setTimeout(function() {
                $message.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        Community.init();
    });

})(jQuery);
