import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';

class AboutScreen extends StatelessWidget {
  const AboutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'About & Bio',
            style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 6),
          const Text(
            'Academic background, research domain & expertise',
            style: TextStyle(color: Colors.white60, fontSize: 14),
          ),
          const SizedBox(height: 20),

          // Profile Overview Glass Card
          GlassCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: const [
                    CircleAvatar(
                      radius: 30,
                      backgroundColor: AppTheme.orangePrimary,
                      child: Text(
                        'PB',
                        style: TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                    ),
                    SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Parsa Besharat',
                            style: TextStyle(
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          SizedBox(height: 2),
                          Text(
                            'Researcher - AI Engineer',
                            style: TextStyle(
                              color: AppTheme.cyanAccent,
                              fontSize: 13,
                            ),
                          ),
                          Text(
                            'TU Bergakademie Freiberg',
                            style: TextStyle(
                              color: Colors.white54,
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                const Text(
                  'Pursuing MS.c in Data Science in Sachsen, Germany. Parsa’s research focuses on high-dimensional vector search optimization, autonomous cyber threat intelligence neural models, distributed tensor calculations, and post-quantum cryptographic protocols.',
                  style: TextStyle(
                    fontSize: 14,
                    height: 1.5,
                    color: Colors.white70,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),

          // Technical Skills Matrix
          const Text(
            'Skills & Technologies Matrix',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 12),

          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: const [
              _SkillChip(
                label: 'Artificial Intelligence',
                color: AppTheme.orangePrimary,
              ),
              _SkillChip(
                label: 'Deep Learning (PyTorch/LibTorch)',
                color: AppTheme.pinkPrimary,
              ),
              _SkillChip(
                label: 'High-Dimensional Vector Search',
                color: AppTheme.cyanAccent,
              ),
              _SkillChip(
                label: 'Cyber Security & Zero-Day ID',
                color: AppTheme.emeraldAccent,
              ),
              _SkillChip(
                label: 'Blockchain & ZK-Proofs',
                color: AppTheme.purpleAccent,
              ),
              _SkillChip(
                label: 'Tensor Matrix Calculus (C++)',
                color: AppTheme.orangePrimary,
              ),
              _SkillChip(
                label: 'Flutter & Dart Engine',
                color: AppTheme.cyanAccent,
              ),
              _SkillChip(
                label: 'Laravel & PHP Backend',
                color: AppTheme.pinkPrimary,
              ),
              _SkillChip(
                label: 'Rust Systems Programming',
                color: AppTheme.emeraldAccent,
              ),
            ],
          ),
          const SizedBox(height: 24),

          // Academic & Social Links
          const Text(
            'Academic & Social Links',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 12),

          GlassCard(
            padding: EdgeInsets.zero,
            child: Column(
              children: const [
                ListTile(
                  leading: Icon(Icons.school, color: AppTheme.orangePrimary),
                  title: Text('ResearchGate Profile'),
                  subtitle: Text('researchgate.net/profile/Parsa-Besharat'),
                  trailing: Icon(
                    Icons.arrow_forward_ios,
                    size: 14,
                    color: Colors.white38,
                  ),
                ),
                Divider(height: 1, color: Colors.white12),
                ListTile(
                  leading: Icon(Icons.code, color: AppTheme.cyanAccent),
                  title: Text('GitHub Organization'),
                  subtitle: Text('github.com/parsabe'),
                  trailing: Icon(
                    Icons.arrow_forward_ios,
                    size: 14,
                    color: Colors.white38,
                  ),
                ),
                Divider(height: 1, color: Colors.white12),
                ListTile(
                  leading: Icon(Icons.business, color: AppTheme.emeraldAccent),
                  title: Text('LinkedIn'),
                  subtitle: Text('linkedin.com/in/parsabe'),
                  trailing: Icon(
                    Icons.arrow_forward_ios,
                    size: 14,
                    color: Colors.white38,
                  ),
                ),
                Divider(height: 1, color: Colors.white12),
                ListTile(
                  leading: Icon(Icons.email, color: AppTheme.pinkPrimary),
                  title: Text('University Email'),
                  subtitle: Text('parsa.besharat@student.tu-freiberg.de'),
                  trailing: Icon(
                    Icons.arrow_forward_ios,
                    size: 14,
                    color: Colors.white38,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _SkillChip extends StatelessWidget {
  final String label;
  final Color color;

  const _SkillChip({required this.label, required this.color});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withValues(alpha: 0.4)),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontWeight: FontWeight.w600,
          fontSize: 12,
        ),
      ),
    );
  }
}
