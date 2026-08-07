import 'package:flutter/material.dart';
import '../models/publication.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import '../widgets/gradient_button.dart';

class PublicationDetailScreen extends StatelessWidget {
  final Publication publication;

  const PublicationDetailScreen({super.key, required this.publication});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Research Paper Reader'),
      ),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
        padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Journal Pill Badge
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
                  const Icon(Icons.article, color: AppTheme.telegramBlue, size: 16),
                  const SizedBox(width: 6),
                  Text(
                    '${publication.journal} (${publication.date})',
                    style: const TextStyle(color: AppTheme.telegramBlue, fontWeight: FontWeight.bold, fontSize: 12),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),

            // Paper Title
            Text(
              publication.title,
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, height: 1.3),
            ),
            const SizedBox(height: 14),

            // Authors & Affiliation Glass Card
            GlassCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Author(s): ${publication.authors.join(", ")}',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.white),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    publication.department,
                    style: const TextStyle(color: Colors.white60, fontSize: 12),
                  ),
                  const SizedBox(height: 4),
                  SelectableText(
                    publication.contactEmail,
                    style: const TextStyle(color: AppTheme.cyanAccent, fontSize: 12),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Abstract Section
            const Text('Abstract', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            GlassCard(
              child: Text(
                publication.abstractText,
                style: const TextStyle(color: Colors.white70, fontSize: 14, height: 1.5),
              ),
            ),
            const SizedBox(height: 20),

            // Full Paper Content Section
            const Text('Full Text & Technical Architecture', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            GlassCard(
              child: Text(
                publication.fullPaperBody,
                style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.6, fontFamily: 'monospace'),
              ),
            ),
            const SizedBox(height: 20),

            // Methodology
            const Text('Methodology & Algorithmic Indexing', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            GlassCard(
              child: Row(
                children: [
                  const Icon(Icons.settings_suggest, color: AppTheme.pinkPrimary),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      publication.methodology,
                      style: const TextStyle(color: Colors.white70, fontSize: 13),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Keywords
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: publication.keywords.map((kw) => Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.white.withValues(alpha: 0.08),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text('#$kw', style: const TextStyle(color: Colors.white54, fontSize: 12)),
              )).toList(),
            ),
            const SizedBox(height: 28),

            // Download PDF Action
            SizedBox(
              width: double.infinity,
              child: GradientButton(
                text: 'Download Full PDF Paper',
                icon: Icons.picture_as_pdf,
                gradient: AppTheme.telegramGradient,
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Downloading ${publication.title} PDF...')),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
