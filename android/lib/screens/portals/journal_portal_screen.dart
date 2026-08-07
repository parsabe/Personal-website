import 'package:flutter/material.dart';
import '../../config/server_config.dart';
import '../../models/blog_post.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';
import '../../widgets/gradient_button.dart';
import '../journal_detail_screen.dart';

class JournalPortalScreen extends StatefulWidget {
  const JournalPortalScreen({super.key});

  @override
  State<JournalPortalScreen> createState() => _JournalPortalScreenState();
}

class _JournalPortalScreenState extends State<JournalPortalScreen> {
  final List<BlogPost> _posts = BlogPost.samplePosts;
  final _titleController = TextEditingController();
  final _contentController = TextEditingController();
  bool _showCreateArticle = false;

  void _insertFormatting(String prefix, String suffix) {
    final text = _contentController.text;
    final selection = _contentController.selection;
    if (selection.isValid && selection.start != selection.end) {
      final selectedText = text.substring(selection.start, selection.end);
      final newText = text.replaceRange(selection.start, selection.end, '$prefix$selectedText$suffix');
      _contentController.text = newText;
    } else {
      _contentController.text += '$prefix$suffix';
    }
  }

  void _submitArticle() {
    if (_titleController.text.trim().isNotEmpty && _contentController.text.trim().isNotEmpty) {
      setState(() {
        _posts.insert(
          0,
          BlogPost(
            id: DateTime.now().toString(),
            title: _titleController.text.trim(),
            author: 'Parsa Besharat (Author)',
            date: 'Just Now',
            readTime: '3 min read',
            excerpt: _contentController.text.trim().split('\n').first,
            content: _contentController.text.trim(),
          ),
        );
        _titleController.clear();
        _contentController.clear();
        _showCreateArticle = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Journal Article Published! Endpoint: ${ServerConfig.blogStore}')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header: Wrapped with Expanded/Flexible to eliminate the 66px right overflow!
          Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: const [
                    Text('Research Journal & Articles', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold), overflow: TextOverflow.ellipsis),
                    Text('Endpoint: ${ServerConfig.blogList}', style: TextStyle(color: Colors.white38, fontSize: 11), overflow: TextOverflow.ellipsis),
                  ],
                ),
              ),
              const SizedBox(width: 8),
              GradientButton(
                text: _showCreateArticle ? 'Close' : 'New Article',
                icon: _showCreateArticle ? Icons.close : Icons.edit,
                height: 36,
                gradient: AppTheme.telegramGradient,
                onPressed: () {
                  setState(() {
                    _showCreateArticle = !_showCreateArticle;
                  });
                },
              ),
            ],
          ),
          const SizedBox(height: 20),

          // Rich Text Article Composer Form
          if (_showCreateArticle) ...[
            GlassCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Write Research Journal Article (Rich Text Editor)', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _titleController,
                    style: const TextStyle(color: Colors.white),
                    decoration: const InputDecoration(
                      hintText: 'Article Title...',
                      hintStyle: TextStyle(color: Colors.white38),
                    ),
                  ),
                  const SizedBox(height: 10),

                  // Rich Text Editor Formatting Toolbar
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        _buildFormatBtn('B', () => _insertFormatting('**', '**')),
                        _buildFormatBtn('I', () => _insertFormatting('*', '*')),
                        _buildFormatBtn('H1', () => _insertFormatting('# ', '')),
                        _buildFormatBtn('</>', () => _insertFormatting('```\n', '\n```')),
                        _buildFormatBtn('"', () => _insertFormatting('> ', '')),
                        _buildFormatBtn('🔗', () => _insertFormatting('[Link](', ')')),
                        _buildFormatBtn('•', () => _insertFormatting('- ', '')),
                      ],
                    ),
                  ),
                  const SizedBox(height: 8),

                  TextField(
                    controller: _contentController,
                    style: const TextStyle(color: Colors.white, fontSize: 13),
                    maxLines: 6,
                    decoration: const InputDecoration(
                      hintText: 'Markdown / Rich Text Article Body...',
                      hintStyle: TextStyle(color: Colors.white38),
                    ),
                  ),
                  const SizedBox(height: 14),
                  GradientButton(
                    text: 'Publish Journal Article',
                    gradient: AppTheme.telegramGradient,
                    onPressed: _submitArticle,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
          ],

          // Journal Articles Stream
          ..._posts.map((post) => Padding(
            padding: const EdgeInsets.only(bottom: 14.0),
            child: GlassCard(
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => JournalDetailScreen(article: post)),
                );
              },
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.article, color: AppTheme.telegramBlue, size: 18),
                      const SizedBox(width: 6),
                      Text(post.date, style: const TextStyle(color: Colors.white54, fontSize: 12)),
                      const Spacer(),
                      Text(post.readTime, style: const TextStyle(color: AppTheme.cyanAccent, fontSize: 12, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Text(post.title, style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(post.excerpt, style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.4)),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: const [
                      Text('Read Full Journal Article →', style: TextStyle(color: AppTheme.telegramBlue, fontSize: 12, fontWeight: FontWeight.bold)),
                    ],
                  ),
                ],
              ),
            ),
          )),
        ],
      ),
    );
  }

  Widget _buildFormatBtn(String label, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(right: 6),
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: Colors.white24),
        ),
        child: Text(label, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
      ),
    );
  }
}
