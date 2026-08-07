import 'package:flutter/material.dart';
import '../models/blog_post.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';

class JournalDetailScreen extends StatelessWidget {
  final BlogPost article;

  const JournalDetailScreen({super.key, required this.article});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Journal Article Reader'),
      ),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
        padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Article Header Pill
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
              decoration: BoxDecoration(
                color: AppTheme.telegramBlue.withValues(alpha: 0.15),
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppTheme.telegramBlue.withValues(alpha: 0.4)),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Icon(Icons.menu_book, color: AppTheme.telegramBlue, size: 16),
                  const SizedBox(width: 6),
                  Text(
                    'Published Journal • ${article.readTime}',
                    style: const TextStyle(color: AppTheme.telegramBlue, fontWeight: FontWeight.bold, fontSize: 12),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),

            // Article Title
            Text(
              article.title,
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, height: 1.3),
            ),
            const SizedBox(height: 12),

            // Author & Date
            Row(
              children: [
                const CircleAvatar(
                  radius: 16,
                  backgroundColor: AppTheme.telegramBlue,
                  child: Text('PB', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
                ),
                const SizedBox(width: 10),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(article.author, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                    Text(article.date, style: const TextStyle(color: Colors.white54, fontSize: 11)),
                  ],
                ),
              ],
            ),
            const SizedBox(height: 20),

            // Excerpt
            GlassCard(
              border: Border.all(color: AppTheme.cyanAccent.withValues(alpha: 0.4)),
              child: Text(
                article.excerpt,
                style: const TextStyle(color: AppTheme.cyanAccent, fontSize: 14, fontWeight: FontWeight.w600, height: 1.5),
              ),
            ),
            const SizedBox(height: 20),

            // Article Body Content
            GlassCard(
              child: Text(
                article.content,
                style: const TextStyle(color: Colors.white70, fontSize: 14, height: 1.6),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
