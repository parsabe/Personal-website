import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'theme/app_theme.dart';
import 'screens/auth_screen.dart';
import 'screens/feed_screen.dart';
import 'screens/about_parsa_screen.dart';
import 'screens/portals_hub_screen.dart';
import 'screens/chats_list_screen.dart';
import 'screens/settings_screen.dart';
import 'screens/search_screen.dart';
import 'models/user_profile.dart';
import 'widgets/custom_nav_drawer.dart';
import 'widgets/floating_glass_nav_bar.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const ParsaApp());
}

class ParsaApp extends StatefulWidget {
  const ParsaApp({super.key});

  @override
  State<ParsaApp> createState() => _ParsaAppState();
}

class _ParsaAppState extends State<ParsaApp> {
  bool _isLoadingSession = true;
  bool _isLoggedIn = false;
  String _currentThemeName = 'OLED Pitch Black';
  UserProfile _currentUser = UserProfile.fromFullnameAndEmail('Guest User', 'guest@parsajournals.com');

  @override
  void initState() {
    super.initState();
    _loadSavedSession();
  }

  Future<void> _loadSavedSession() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final isLoggedIn = prefs.getBool('is_logged_in') ?? false;
      final email = prefs.getString('user_email') ?? 'guest@parsajournals.com';
      final name = prefs.getString('user_name') ?? 'Guest User';
      final firstName = prefs.getString('user_first_name') ?? '';
      final lastName = prefs.getString('user_last_name') ?? '';
      final username = prefs.getString('user_username') ?? '';
      final bio = prefs.getString('user_bio') ?? '';
      final website = prefs.getString('user_website') ?? '';
      final github = prefs.getString('user_github') ?? '';
      final social = prefs.getString('user_social') ?? '';
      final theme = prefs.getString('current_theme') ?? 'OLED Pitch Black';

      UserProfile profile = UserProfile.fromFullnameAndEmail(name, email);
      if (firstName.isNotEmpty) profile.firstName = firstName;
      if (lastName.isNotEmpty) profile.lastName = lastName;
      if (username.isNotEmpty) profile.username = username;
      if (bio.isNotEmpty) profile.bio = bio;
      profile.websiteLink = website;
      profile.githubLink = github;
      profile.socialLink = social;

      setState(() {
        _isLoggedIn = isLoggedIn;
        _currentUser = profile;
        _currentThemeName = theme;
        _isLoadingSession = false;
      });
    } catch (_) {
      setState(() {
        _isLoadingSession = false;
      });
    }
  }

  Future<void> _onLoginSuccess(String name, String email, [String? customUsername]) async {
    final profile = UserProfile.fromFullnameAndEmail(name, email);
    if (customUsername != null && customUsername.trim().isNotEmpty) {
      profile.username = customUsername.trim().replaceAll('@', '');
    }
    setState(() {
      _currentUser = profile;
      _isLoggedIn = true;
    });
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setBool('is_logged_in', true);
      await prefs.setString('user_name', name);
      await prefs.setString('user_email', email);
      await prefs.setString('user_first_name', profile.firstName);
      await prefs.setString('user_last_name', profile.lastName);
      await prefs.setString('user_username', profile.username);
      await prefs.setString('user_bio', profile.bio);
      await prefs.setString('user_website', profile.websiteLink);
      await prefs.setString('user_github', profile.githubLink);
      await prefs.setString('user_social', profile.socialLink);

      // Save user to persistent registered users directory cache
      List<String> registeredJsonList = prefs.getStringList('registered_users_db') ?? [];
      final userMap = profile.toJson();
      registeredJsonList.removeWhere((item) {
        try {
          final m = jsonDecode(item);
          return m['email'] == profile.email;
        } catch (_) {
          return false;
        }
      });
      registeredJsonList.add(jsonEncode(userMap));
      await prefs.setStringList('registered_users_db', registeredJsonList);
    } catch (_) {}
  }

  Future<void> _onRequireLogin() async {
    setState(() {
      _isLoggedIn = false;
    });
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setBool('is_logged_in', false);
    } catch (_) {}
  }

  Future<void> _onThemeChanged(String newTheme) async {
    setState(() {
      _currentThemeName = newTheme;
    });
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('current_theme', newTheme);
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Parsa Journals',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.getThemeByName(_currentThemeName),
      home: _isLoadingSession
          ? const Scaffold(
              backgroundColor: AppTheme.background,
              body: Center(
                child: CircularProgressIndicator(color: AppTheme.telegramBlue),
              ),
            )
          : (_isLoggedIn
              ? AuthenticatedAppScaffold(
                  isLoggedIn: _isLoggedIn,
                  onRequireLogin: _onRequireLogin,
                  currentTheme: _currentThemeName,
                  onThemeChanged: _onThemeChanged,
                  userProfile: _currentUser,
                )
              : GuestModeScreen(
                  onLoginSuccess: _onLoginSuccess,
                )),
    );
  }
}

/// GUEST MODE SCREEN: For unauthenticated guests ONLY About Parsa and Login/Sign Up button exist!
class GuestModeScreen extends StatelessWidget {
  final Function(String name, String email, String? username) onLoginSuccess;

  const GuestModeScreen({super.key, required this.onLoginSuccess});

  void _openAuthModal(BuildContext context) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => AuthScreen(
          onAuthSuccess: (name, email, username) {
            if (Navigator.canPop(context)) {
              Navigator.pop(context);
            }
            onLoginSuccess(name, email, username);
          },
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('About Parsa'),
        centerTitle: true,
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 12.0),
            child: ElevatedButton.icon(
              icon: const Icon(Icons.login, size: 16, color: Colors.white),
              label: const Text('Login / Sign Up', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.white)),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.telegramBlue,
                elevation: 4,
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              ),
              onPressed: () => _openAuthModal(context),
            ),
          ),
        ],
      ),
      body: const AboutParsaScreen(),
    );
  }
}

/// AUTHENTICATED SCAFFOLD: When session is active, ALL 5 tabs & services are fully available with Floating Navigation Bar!
class AuthenticatedAppScaffold extends StatefulWidget {
  final bool isLoggedIn;
  final VoidCallback onRequireLogin;
  final String currentTheme;
  final Function(String) onThemeChanged;
  final UserProfile userProfile;

  const AuthenticatedAppScaffold({
    super.key,
    required this.isLoggedIn,
    required this.onRequireLogin,
    required this.currentTheme,
    required this.onThemeChanged,
    required this.userProfile,
  });

  @override
  State<AuthenticatedAppScaffold> createState() => _AuthenticatedAppScaffoldState();
}

class _AuthenticatedAppScaffoldState extends State<AuthenticatedAppScaffold> {
  int _currentIndex = 0; // Active session opens directly to Community Feed (Index 0)

  final List<String> _titles = const [
    'Community Feed',
    'About Parsa',
    'Portals & Journal',
    'Telegram Chats',
    'Settings & Profile',
  ];

  @override
  Widget build(BuildContext context) {
    final pages = [
      FeedScreen(
        isLoggedIn: widget.isLoggedIn,
        onRequireLogin: widget.onRequireLogin,
      ),
      const AboutParsaScreen(),
      const PortalsHubScreen(),
      ChatsListScreen(
        isLoggedIn: widget.isLoggedIn,
        onRequireLogin: widget.onRequireLogin,
        userProfile: widget.userProfile,
      ),
      SettingsScreen(
        isLoggedIn: widget.isLoggedIn,
        onRequireLogin: widget.onRequireLogin,
        currentTheme: widget.currentTheme,
        onThemeChanged: widget.onThemeChanged,
        userProfile: widget.userProfile,
      ),
    ];

    return Scaffold(
      extendBody: true,
      drawer: CustomNavDrawer(userProfile: widget.userProfile),
      appBar: AppBar(
        title: Text(_titles[_currentIndex]),
        centerTitle: true,
        actions: [
          IconButton(
            icon: const Icon(Icons.search, color: AppTheme.telegramBlue),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const SearchScreen()),
              );
            },
          ),
        ],
      ),
      body: Container(
        color: AppTheme.themeBackgrounds[widget.currentTheme] ?? AppTheme.background,
        child: IndexedStack(
          index: _currentIndex,
          children: pages,
        ),
      ),
      bottomNavigationBar: FloatingGlassNavBar(
        currentIndex: _currentIndex,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
      ),
    );
  }
}
