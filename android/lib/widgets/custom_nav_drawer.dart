import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../screens/portals/sandika_portal_screen.dart';
import '../screens/portals/nigma_portal_screen.dart';
import '../screens/portals/chat_portal_screen.dart';
import '../screens/portals/feedback_portal_screen.dart';
import '../screens/portals/vpn_status_screen.dart';
import '../screens/portals/playlist_books_screen.dart';
import '../screens/contact_screen.dart';
import '../models/user_profile.dart';
import '../screens/search_screen.dart';

class CustomNavDrawer extends StatelessWidget {
  final UserProfile? userProfile;

  const CustomNavDrawer({super.key, this.userProfile});

  @override
  Widget build(BuildContext context) {
    final profile = userProfile ?? UserProfile.fromFullnameAndEmail('Parsa Journals User', 'user@parsajournals.com');

    return Drawer(
      backgroundColor: AppTheme.surface,
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: const BoxDecoration(
              gradient: AppTheme.primaryGradient,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                CircleAvatar(
                  radius: 28,
                  backgroundColor: Colors.white24,
                  child: Text(
                    profile.initials,
                    style: const TextStyle(
                      color: Colors.white,
                      fontWeight: FontWeight.bold,
                      fontSize: 20,
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  profile.fullName,
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                  ),
                ),
                Text(
                  profile.email,
                  style: const TextStyle(
                    color: Colors.white70,
                    fontSize: 12,
                  ),
                ),
              ],
            ),
          ),
          ListTile(
            leading: const Icon(Icons.search, color: AppTheme.cyanAccent),
            title: const Text('Search Everything'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const SearchScreen()),
              );
            },
          ),
          const Divider(color: Colors.white12),
          const Padding(
            padding: EdgeInsets.only(left: 16, top: 8, bottom: 4),
            child: Text(
              'INTERACTIVE PORTALS',
              style: TextStyle(color: Colors.white54, fontSize: 12, fontWeight: FontWeight.bold),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.auto_awesome, color: AppTheme.orangePrimary),
            title: const Text('Sandika AI Portal'),
            subtitle: const Text('Voice analytics, Git insights & Arkham'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const SandikaPortalScreen()),
              );
            },
          ),
          ListTile(
            leading: const Icon(Icons.psychology, color: AppTheme.pinkPrimary),
            title: const Text('Nigma Riddler Portal'),
            subtitle: const Text('Cybersecurity & Logic Challenges'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const NigmaPortalScreen()),
              );
            },
          ),
          ListTile(
            leading: const Icon(Icons.forum, color: AppTheme.emeraldAccent),
            title: const Text('Social Chat & Feed'),
            subtitle: const Text('Messages, Stories & Public Feed'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const ChatPortalScreen()),
              );
            },
          ),
          const Divider(color: Colors.white12),
          const Padding(
            padding: EdgeInsets.only(left: 16, top: 8, bottom: 4),
            child: Text(
              'SERVICES & REPOSITORY',
              style: TextStyle(color: Colors.white54, fontSize: 12, fontWeight: FontWeight.bold),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.security, color: AppTheme.emeraldAccent),
            title: const Text('VPN Server Monitor'),
            subtitle: const Text('Live server nodes & latency'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const VpnStatusScreen()),
              );
            },
          ),
          ListTile(
            leading: const Icon(Icons.headphones, color: AppTheme.purpleAccent),
            title: const Text('Playlist & Books'),
            subtitle: const Text('Curated audio & reading list'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const PlaylistBooksScreen()),
              );
            },
          ),
          ListTile(
            leading: const Icon(Icons.rate_review, color: AppTheme.pinkPrimary),
            title: const Text('CS Feedback Portal'),
            subtitle: const Text('Submit reviews & ratings'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const FeedbackPortalScreen()),
              );
            },
          ),
          ListTile(
            leading: const Icon(Icons.mail, color: AppTheme.orangePrimary),
            title: const Text('Contact Parsa'),
            subtitle: const Text('Send a direct message'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const ContactScreen()),
              );
            },
          ),
        ],
      ),
    );
  }
}
