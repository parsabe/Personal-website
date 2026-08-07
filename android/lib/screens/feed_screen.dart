import 'package:flutter/material.dart';
import '../config/server_config.dart';
import '../models/chat_message.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import '../widgets/gradient_button.dart';

class FeedScreen extends StatefulWidget {
  final bool isLoggedIn;
  final VoidCallback onRequireLogin;

  const FeedScreen({
    super.key,
    required this.isLoggedIn,
    required this.onRequireLogin,
  });

  @override
  State<FeedScreen> createState() => _FeedScreenState();
}

class _FeedScreenState extends State<FeedScreen> {
  final List<ChatMessage> _feedPosts = ChatMessage.sampleMessages;
  final List<UserStory> _stories = [];

  final _postContentController = TextEditingController();
  final _mediaUrlController = TextEditingController();
  final _storyCaptionController = TextEditingController();
  final _replyController = TextEditingController();

  void _openCreatePostModal() {
    if (!widget.isLoggedIn) {
      widget.onRequireLogin();
      return;
    }

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return Padding(
          padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
          child: GlassCard(
            margin: const EdgeInsets.all(16),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Create Post', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white54),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: _postContentController,
                  style: const TextStyle(color: Colors.white),
                  maxLines: 4,
                  decoration: const InputDecoration(
                    hintText: "What's happening? Share thoughts, code, photos, or videos...",
                    hintStyle: TextStyle(color: Colors.white38, fontSize: 13),
                    border: InputBorder.none,
                  ),
                ),
                const SizedBox(height: 10),
                TextField(
                  controller: _mediaUrlController,
                  style: const TextStyle(color: Colors.white, fontSize: 13),
                  decoration: const InputDecoration(
                    prefixIcon: Icon(Icons.link, color: AppTheme.telegramBlue),
                    hintText: 'Media URL (Photo or Video link, optional)...',
                    hintStyle: TextStyle(color: Colors.white38, fontSize: 12),
                  ),
                ),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: GradientButton(
                    text: 'Publish Post',
                    gradient: AppTheme.telegramGradient,
                    onPressed: () {
                      if (_postContentController.text.trim().isNotEmpty) {
                        setState(() {
                          _feedPosts.insert(
                            0,
                            ChatMessage(
                              id: DateTime.now().toString(),
                              sender: 'You',
                              avatarUrl: '',
                              message: _postContentController.text.trim(),
                              timestamp: 'Just now',
                              isUser: true,
                              likes: 0,
                              reposts: 0,
                            ),
                          );
                        });
                        _postContentController.clear();
                        _mediaUrlController.clear();
                        Navigator.pop(context);
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Post Published! Endpoint: ${ServerConfig.userPostsCreate}')),
                        );
                      }
                    },
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  // Instagram-Style Story Creator Modal (Matching Images 2 & 3 tools)
  void _openCreateStoryModal() {
    if (!widget.isLoggedIn) {
      widget.onRequireLogin();
      return;
    }

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: SizedBox(
            height: 500,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Create Story', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white54),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 10),

                // Caption Input Field
                TextField(
                  controller: _storyCaptionController,
                  style: const TextStyle(color: Colors.white, fontSize: 14),
                  decoration: const InputDecoration(
                    hintText: 'Add story caption or highlight text...',
                    hintStyle: TextStyle(color: Colors.white38, fontSize: 13),
                  ),
                ),
                const SizedBox(height: 16),

                const Text('Story Interactive Tools (English)', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.white60)),
                const SizedBox(height: 10),

                // Instagram Story Tool Badges Grid (Matching Images 2 & 3)
                Expanded(
                  child: SingleChildScrollView(
                    child: Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: [
                        _buildStoryToolChip(Icons.text_fields, 'Text (Aa)'),
                        _buildStoryToolChip(Icons.sticky_note_2, 'Stickers'),
                        _buildStoryToolChip(Icons.music_note, 'Audio / Music'),
                        _buildStoryToolChip(Icons.auto_awesome, 'Effects'),
                        _buildStoryToolChip(Icons.location_on, 'Location'),
                        _buildStoryToolChip(Icons.alternate_email, 'Mention (@)'),
                        _buildStoryToolChip(Icons.gesture, 'Draw'),
                        _buildStoryToolChip(Icons.download, 'Download'),
                        _buildStoryToolChip(Icons.poll, 'Poll'),
                        _buildStoryToolChip(Icons.link, 'Link'),
                        _buildStoryToolChip(Icons.tag, 'Hashtag'),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 16),

                // Share Buttons: Your Story / Close Friends (Matching Image 3)
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        icon: const Icon(Icons.person, size: 16),
                        label: const Text('Your Story'),
                        style: OutlinedButton.styleFrom(
                          foregroundColor: Colors.white,
                          side: BorderSide(color: Colors.white.withValues(alpha: 0.3)),
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                        onPressed: () {
                          _submitStory('Your Story');
                        },
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: ElevatedButton.icon(
                        icon: const Icon(Icons.star, size: 16, color: Colors.white),
                        label: const Text('Close Friends'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.emeraldAccent,
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                        onPressed: () {
                          _submitStory('Close Friends');
                        },
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _submitStory(String audience) {
    if (_storyCaptionController.text.trim().isNotEmpty) {
      setState(() {
        _stories.insert(
          0,
          UserStory(
            id: DateTime.now().toString(),
            username: 'Your Story ($audience)',
            caption: _storyCaptionController.text.trim(),
            hasUnseen: true,
          ),
        );
      });
      _storyCaptionController.clear();
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Story Posted to $audience! Endpoint: ${ServerConfig.chatStoriesCreate}')),
      );
    }
  }

  Widget _buildStoryToolChip(IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: AppTheme.telegramBlue, size: 16),
          const SizedBox(width: 6),
          Text(label, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  void _openReplyModal(ChatMessage post) {
    if (!widget.isLoggedIn) {
      widget.onRequireLogin();
      return;
    }

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Padding(
              padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
              child: GlassCard(
                margin: const EdgeInsets.all(16),
                child: SizedBox(
                  height: 380,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('Replies (${post.replies.length})', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                          IconButton(
                            icon: const Icon(Icons.close, color: Colors.white54),
                            onPressed: () => Navigator.pop(context),
                          ),
                        ],
                      ),
                      const Divider(color: Colors.white12),
                      const SizedBox(height: 6),

                      Expanded(
                        child: post.replies.isEmpty
                            ? const Center(child: Text('No replies yet. Be the first to reply!', style: TextStyle(color: Colors.white54, fontSize: 13)))
                            : ListView.builder(
                                itemCount: post.replies.length,
                                itemBuilder: (context, index) {
                                  return Padding(
                                    padding: const EdgeInsets.only(bottom: 8.0),
                                    child: Container(
                                      padding: const EdgeInsets.all(10),
                                      decoration: BoxDecoration(
                                        color: Colors.white.withValues(alpha: 0.05),
                                        borderRadius: BorderRadius.circular(12),
                                      ),
                                      child: Text(post.replies[index], style: const TextStyle(color: Colors.white70, fontSize: 13)),
                                    ),
                                  );
                                },
                              ),
                      ),
                      const SizedBox(height: 10),

                      Row(
                        children: [
                          Expanded(
                            child: TextField(
                              controller: _replyController,
                              style: const TextStyle(color: Colors.white, fontSize: 13),
                              decoration: InputDecoration(
                                hintText: 'Write a reply...',
                                hintStyle: const TextStyle(color: Colors.white38, fontSize: 13),
                                filled: true,
                                fillColor: Colors.white.withValues(alpha: 0.08),
                                contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
                              ),
                            ),
                          ),
                          const SizedBox(width: 8),
                          IconButton(
                            icon: const Icon(Icons.send, color: AppTheme.telegramBlue),
                            onPressed: () {
                              if (_replyController.text.trim().isNotEmpty) {
                                setState(() {
                                  post.replies.add('You: ${_replyController.text.trim()}');
                                });
                                setModalState(() {});
                                _replyController.clear();
                              }
                            },
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            );
          },
        );
      },
    );
  }

  void _viewStory(UserStory story) {
    showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          backgroundColor: Colors.transparent,
          child: GlassCard(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                CircleAvatar(
                  radius: 36,
                  backgroundColor: AppTheme.telegramBlue,
                  child: Text(story.username[0], style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: Colors.white)),
                ),
                const SizedBox(height: 12),
                Text(story.username, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                const SizedBox(height: 8),
                Text(story.caption, textAlign: TextAlign.center, style: const TextStyle(color: Colors.white70, fontSize: 15)),
                const SizedBox(height: 20),
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Close Story', style: TextStyle(color: AppTheme.telegramBlue)),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.transparent,
      body: RefreshIndicator(
        onRefresh: () async {
          await Future.delayed(const Duration(seconds: 1));
          if (!context.mounted) return;
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Feeds Refreshed! Endpoint: ${ServerConfig.userPublicFeed}')),
          );
        },
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 120),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Instagram-Style Stories Bar (Only shown if active stories exist)
              if (_stories.isNotEmpty) ...[
                const Text('Active Stories', style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.white70)),
                const SizedBox(height: 8),
                SizedBox(
                  height: 95,
                  child: ListView(
                    scrollDirection: Axis.horizontal,
                    children: [
                      GestureDetector(
                        onTap: _openCreateStoryModal,
                        child: Container(
                          margin: const EdgeInsets.only(right: 12),
                          child: Column(
                            children: [
                              Container(
                                padding: const EdgeInsets.all(2),
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  border: Border.all(color: AppTheme.telegramBlue, width: 2),
                                ),
                                child: const CircleAvatar(
                                  radius: 26,
                                  backgroundColor: AppTheme.surface,
                                  child: Icon(Icons.add, color: AppTheme.telegramBlue, size: 28),
                                ),
                              ),
                              const SizedBox(height: 4),
                              const Text('Add Story', style: TextStyle(fontSize: 11, color: Colors.white70)),
                            ],
                          ),
                        ),
                      ),

                      ..._stories.map((story) => GestureDetector(
                        onTap: () => _viewStory(story),
                        child: Container(
                          margin: const EdgeInsets.only(right: 12),
                          child: Column(
                            children: [
                              Container(
                                padding: const EdgeInsets.all(2.5),
                                decoration: const BoxDecoration(
                                  shape: BoxShape.circle,
                                  gradient: AppTheme.primaryGradient,
                                ),
                                child: CircleAvatar(
                                  radius: 26,
                                  backgroundColor: AppTheme.background,
                                  child: Text(story.username[0], style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(story.username, style: const TextStyle(fontSize: 11, color: Colors.white70)),
                            ],
                          ),
                        ),
                      )),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
              ],

              // Community Feed Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: const [
                  Text('Community Feed & Updates', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  Icon(Icons.rss_feed, color: AppTheme.telegramBlue, size: 20),
                ],
              ),
              const SizedBox(height: 12),

              if (_feedPosts.isEmpty)
                GlassCard(
                  child: Column(
                    children: const [
                      Icon(Icons.inbox, color: Colors.white38, size: 40),
                      SizedBox(height: 8),
                      Text('No new feeds found.', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                      SizedBox(height: 4),
                      Text('Tap the + button to publish your first post!', style: TextStyle(color: Colors.white54, fontSize: 12)),
                    ],
                  ),
                )
              else
                ..._feedPosts.map((post) => Padding(
                  padding: const EdgeInsets.only(bottom: 14.0),
                  child: GlassCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            CircleAvatar(
                              radius: 18,
                              backgroundColor: post.isUser ? AppTheme.telegramBlue : AppTheme.orangePrimary,
                              child: Text(post.sender[0], style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                            ),
                            const SizedBox(width: 10),
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(post.sender, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                                Text(post.timestamp, style: const TextStyle(color: Colors.white38, fontSize: 11)),
                              ],
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        Text(post.message, style: const TextStyle(fontSize: 14, height: 1.4, color: Colors.white70)),
                        const SizedBox(height: 14),

                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            _buildInteractionButton(Icons.favorite_border, '${post.likes}', AppTheme.pinkPrimary, () {
                              setState(() {
                                post.likes++;
                              });
                            }),
                            _buildInteractionButton(Icons.repeat, '${post.reposts}', AppTheme.cyanAccent, () {
                              setState(() {
                                post.reposts++;
                              });
                            }),
                            _buildInteractionButton(Icons.bookmark_border, 'Save', AppTheme.orangePrimary, () {}),
                            _buildInteractionButton(Icons.chat_bubble_outline, '${post.replies.length} Reply', AppTheme.telegramBlue, () {
                              _openReplyModal(post);
                            }),
                          ],
                        ),
                      ],
                    ),
                  ),
                )),
            ],
          ),
        ),
      ),

      floatingActionButton: Padding(
        padding: const EdgeInsets.only(bottom: 80),
        child: FloatingActionButton(
          backgroundColor: AppTheme.telegramBlue,
          onPressed: _openCreatePostModal,
          child: const Icon(Icons.add, color: Colors.white, size: 28),
        ),
      ),
    );
  }

  Widget _buildInteractionButton(IconData icon, String count, Color color, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
        child: Row(
          children: [
            Icon(icon, color: color, size: 18),
            const SizedBox(width: 4),
            Text(count, style: TextStyle(color: color, fontSize: 12, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}
