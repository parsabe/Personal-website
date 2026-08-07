import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import '../widgets/gradient_button.dart';
import 'contact_screen.dart';
import 'portals/chat_portal_screen.dart';
import '../models/chat_message.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final recentFeed = ChatMessage.sampleMessages;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Greeting Pill
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.08),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: Colors.white24),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: const [
                Text('👋 HELLO!', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              ],
            ),
          ),
          const SizedBox(height: 14),

          // Hero Title
          RichText(
            text: const TextSpan(
              style: TextStyle(fontSize: 32, fontWeight: FontWeight.w800, color: Colors.white),
              children: [
                TextSpan(text: "I'm "),
                TextSpan(
                  text: 'Parsa Besharat.',
                  style: TextStyle(
                    color: AppTheme.orangePrimary,
                    shadows: [
                      Shadow(color: AppTheme.pinkPrimary, blurRadius: 15),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),

          // Subtitle / Bio summary
          const Text(
            'Persian AI Researcher & Data Scientist pursuing MS.c in Data Science at TU Bergakademie Freiberg, Germany. Specializing in Vector AI, Cyber Security, Tensor Computing & Multi-Agent Frameworks.',
            style: TextStyle(fontSize: 15, color: Colors.white70, height: 1.4),
          ),
          const SizedBox(height: 20),

          // Action Buttons
          Wrap(
            spacing: 12,
            runSpacing: 12,
            children: [
              GradientButton(
                text: 'Contact Me',
                icon: Icons.mail_outline,
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const ContactScreen()),
                  );
                },
              ),
              GradientButton(
                text: 'Social Chat & Portal',
                icon: Icons.chat_bubble_outline,
                gradient: AppTheme.cyanPurpleGradient,
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const ChatPortalScreen()),
                  );
                },
              ),
            ],
          ),
          const SizedBox(height: 30),

          // Stats Glass Row
          Row(
            children: [
              Expanded(
                child: GlassCard(
                  child: Column(
                    children: const [
                      Text('10+', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppTheme.orangePrimary)),
                      SizedBox(height: 4),
                      Text('Projects', style: TextStyle(fontSize: 12, color: Colors.white60)),
                    ],
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: GlassCard(
                  child: Column(
                    children: const [
                      Text('10', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppTheme.pinkPrimary)),
                      SizedBox(height: 4),
                      Text('Publications', style: TextStyle(fontSize: 12, color: Colors.white60)),
                    ],
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: GlassCard(
                  child: Column(
                    children: const [
                      Text('7', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppTheme.cyanAccent)),
                      SizedBox(height: 4),
                      Text('Portals', style: TextStyle(fontSize: 12, color: Colors.white60)),
                    ],
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 30),

          // Community Feed Section Header
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: const [
                  Text('🐦 Community Feed', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  SizedBox(width: 8),
                  Icon(Icons.circle, color: AppTheme.emeraldAccent, size: 10),
                ],
              ),
              TextButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const ChatPortalScreen()),
                  );
                },
                child: const Text('View All', style: TextStyle(color: AppTheme.orangePrimary)),
              ),
            ],
          ),
          const SizedBox(height: 10),

          // Feed Stream Preview
          ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: recentFeed.length,
            separatorBuilder: (_, index) => const SizedBox(height: 10),
            itemBuilder: (context, index) {
              final msg = recentFeed[index];
              return GlassCard(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        CircleAvatar(
                          radius: 16,
                          backgroundColor: AppTheme.orangePrimary.withValues(alpha: 0.3),
                          child: Text(
                            msg.sender[0],
                            style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(msg.sender, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                              Text(msg.timestamp, style: const TextStyle(color: Colors.white38, fontSize: 11)),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text(msg.message, style: const TextStyle(fontSize: 13, color: Colors.white70)),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        const Icon(Icons.favorite_border, color: AppTheme.pinkPrimary, size: 16),
                        const SizedBox(width: 4),
                        Text('${msg.likes}', style: const TextStyle(fontSize: 12, color: Colors.white54)),
                        const SizedBox(width: 16),
                        const Icon(Icons.repeat, color: AppTheme.cyanAccent, size: 16),
                        const SizedBox(width: 4),
                        Text('${msg.reposts}', style: const TextStyle(fontSize: 12, color: Colors.white54)),
                      ],
                    ),
                  ],
                ),
              );
            },
          ),
        ],
      ),
    );
  }
}
