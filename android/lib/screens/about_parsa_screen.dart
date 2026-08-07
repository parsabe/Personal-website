import 'package:flutter/material.dart';
import '../models/project.dart';
import '../models/publication.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import '../widgets/gradient_button.dart';
import 'project_detail_screen.dart';
import 'publication_detail_screen.dart';

class AboutParsaScreen extends StatefulWidget {
  const AboutParsaScreen({super.key});

  @override
  State<AboutParsaScreen> createState() => _AboutParsaScreenState();
}

class _AboutParsaScreenState extends State<AboutParsaScreen> with SingleTickerProviderStateMixin {
  late TabController _subTabController;
  final _contactFormKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _messageController = TextEditingController();
  bool _contactSent = false;

  @override
  void initState() {
    super.initState();
    _subTabController = TabController(length: 4, vsync: this);
  }

  void _submitContact() {
    if (_contactFormKey.currentState!.validate()) {
      setState(() {
        _contactSent = true;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final projects = Project.sampleProjects;
    final publications = Publication.samplePublications;

    return Column(
      children: [
        // Sub-navigation bar for Tab 1 (Removed Technical Blog as requested)
        Container(
          color: Colors.transparent,
          child: TabBar(
            controller: _subTabController,
            isScrollable: true,
            indicatorColor: AppTheme.telegramBlue,
            labelColor: AppTheme.telegramBlue,
            unselectedLabelColor: Colors.white54,
            tabs: const [
              Tab(text: 'Profile & Bio'),
              Tab(text: '10 Projects'),
              Tab(text: 'Publications'),
              Tab(text: 'Contact Me'),
            ],
          ),
        ),

        Expanded(
          child: TabBarView(
            controller: _subTabController,
            children: [
              // 1. Profile & Bio Subtab
              SingleChildScrollView(
                physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    GlassCard(
                      child: Row(
                        children: [
                          const CircleAvatar(
                            radius: 36,
                            backgroundColor: AppTheme.telegramBlue,
                            child: Text('PB', style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.white)),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: const [
                                Text('Parsa Besharat', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
                                SizedBox(height: 4),
                                Text('AI Researcher & Data Scientist', style: TextStyle(color: AppTheme.cyanAccent, fontSize: 13, fontWeight: FontWeight.w600)),
                                Text('TU Bergakademie Freiberg, Germany', style: TextStyle(color: Colors.white54, fontSize: 12)),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    const Text('Biography & Vision', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    const Text(
                      'Parsa Besharat is a Persian AI Researcher and Data Scientist pursuing his Master of Science degree at TU Bergakademie Freiberg in Saxony, Germany. He specializes in high-dimensional vector search optimization, autonomous cyber threat neural models, post-quantum cryptographic key exchanges, and multi-agent LLM orchestration engines.',
                      style: TextStyle(color: Colors.white70, fontSize: 14, height: 1.5),
                    ),
                    const SizedBox(height: 24),

                    const Text('Skills & Tech Matrix', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 12),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: const [
                        _Chip('Vector AI & Search', AppTheme.telegramBlue),
                        _Chip('Cyber Security & Zero-Day ID', AppTheme.emeraldAccent),
                        _Chip('Deep Learning (PyTorch/LibTorch)', AppTheme.pinkPrimary),
                        _Chip('Post-Quantum Cryptography', AppTheme.purpleAccent),
                        _Chip('Tensor Calculations (C++)', AppTheme.orangePrimary),
                        _Chip('Flutter & Dart Engine', AppTheme.cyanAccent),
                        _Chip('Laravel & REST APIs', AppTheme.pinkPrimary),
                      ],
                    ),
                  ],
                ),
              ),

              // 2. All 10 Flagship Projects Subtab
              ListView.separated(
                padding: const EdgeInsets.all(20),
                itemCount: projects.length,
                separatorBuilder: (_, index) => const SizedBox(height: 12),
                itemBuilder: (context, index) {
                  final p = projects[index];
                  return GlassCard(
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (_) => ProjectDetailScreen(project: p)),
                      );
                    },
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Expanded(
                              child: Text(p.title, style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold)),
                            ),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                              decoration: BoxDecoration(
                                color: AppTheme.telegramBlue.withValues(alpha: 0.15),
                                borderRadius: BorderRadius.circular(10),
                              ),
                              child: Text(p.status, style: const TextStyle(color: AppTheme.telegramBlue, fontSize: 11, fontWeight: FontWeight.bold)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),
                        Text(p.shortDescription, style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.4)),
                        const SizedBox(height: 10),
                        Wrap(
                          spacing: 6,
                          children: p.tags.map((t) => Text('#$t ', style: const TextStyle(color: Colors.white38, fontSize: 11))).toList(),
                        ),
                      ],
                    ),
                  );
                },
              ),

              // 3. Publications Subtab (Clickable to view full paper reader)
              ListView.separated(
                padding: const EdgeInsets.all(20),
                itemCount: publications.length,
                separatorBuilder: (_, index) => const SizedBox(height: 14),
                itemBuilder: (context, index) {
                  final pub = publications[index];
                  return GlassCard(
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (_) => PublicationDetailScreen(publication: pub)),
                      );
                    },
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Expanded(
                              child: Text(pub.journal, style: const TextStyle(color: AppTheme.cyanAccent, fontSize: 12, fontWeight: FontWeight.bold)),
                            ),
                            const Icon(Icons.open_in_new, color: AppTheme.telegramBlue, size: 16),
                          ],
                        ),
                        const SizedBox(height: 6),
                        Text(pub.title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        Text(pub.abstractText, style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.4)),
                        const SizedBox(height: 10),
                        const Text('Tap to read full paper & methodology →', style: TextStyle(color: AppTheme.telegramBlue, fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  );
                },
              ),

              // 4. Contact Form Subtab
              SingleChildScrollView(
                physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Contact Parsa Directly', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 6),
                    const Text('Inquiries regarding research, projects, or collaboration.', style: TextStyle(color: Colors.white60, fontSize: 14)),
                    const SizedBox(height: 20),

                    if (_contactSent)
                      GlassCard(
                        border: Border.all(color: AppTheme.emeraldAccent),
                        child: Column(
                          children: [
                            const Icon(Icons.check_circle, color: AppTheme.emeraldAccent, size: 50),
                            const SizedBox(height: 12),
                            const Text('Message Transmitted!', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                            const SizedBox(height: 8),
                            const Text('Thank you. Parsa will review your message promptly.', style: TextStyle(color: Colors.white70)),
                          ],
                        ),
                      )
                    else
                      GlassCard(
                        child: Form(
                          key: _contactFormKey,
                          child: Column(
                            children: [
                              TextFormField(
                                controller: _nameController,
                                style: const TextStyle(color: Colors.white),
                                decoration: const InputDecoration(hintText: 'Your Name', hintStyle: TextStyle(color: Colors.white38)),
                                validator: (v) => v == null || v.isEmpty ? 'Required' : null,
                              ),
                              const SizedBox(height: 12),
                              TextFormField(
                                controller: _emailController,
                                style: const TextStyle(color: Colors.white),
                                decoration: const InputDecoration(hintText: 'Your Email', hintStyle: TextStyle(color: Colors.white38)),
                                validator: (v) => v == null || !v.contains('@') ? 'Valid email required' : null,
                              ),
                              const SizedBox(height: 12),
                              TextFormField(
                                controller: _messageController,
                                style: const TextStyle(color: Colors.white),
                                maxLines: 4,
                                decoration: const InputDecoration(hintText: 'Message...', hintStyle: TextStyle(color: Colors.white38)),
                                validator: (v) => v == null || v.isEmpty ? 'Required' : null,
                              ),
                              const SizedBox(height: 16),
                              SizedBox(
                                width: double.infinity,
                                child: GradientButton(
                                  text: 'Send Message',
                                  gradient: AppTheme.telegramGradient,
                                  onPressed: _submitContact,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _Chip extends StatelessWidget {
  final String label;
  final Color color;

  const _Chip(this.label, this.color);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: color.withValues(alpha: 0.3)),
      ),
      child: Text(label, style: TextStyle(color: color, fontWeight: FontWeight.bold, fontSize: 12)),
    );
  }
}
