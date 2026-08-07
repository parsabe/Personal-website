import 'dart:math';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:url_launcher/url_launcher.dart';
import '../models/user_profile.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import '../widgets/gradient_button.dart';


class SettingsScreen extends StatefulWidget {
  final bool isLoggedIn;
  final VoidCallback onRequireLogin;
  final String currentTheme;
  final Function(String) onThemeChanged;
  final UserProfile? userProfile;

  const SettingsScreen({
    super.key,
    required this.isLoggedIn,
    required this.onRequireLogin,
    required this.currentTheme,
    required this.onThemeChanged,
    this.userProfile,
  });

  @override
  State<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> with TickerProviderStateMixin {
  late TabController _profileSubTabController;
  late TabController _mainSettingsTabController;

  // Profile Form Controllers
  late TextEditingController _firstNameController;
  late TextEditingController _lastNameController;
  late TextEditingController _usernameController;
  late TextEditingController _bioController;
  late TextEditingController _websiteLinkController;
  late TextEditingController _githubLinkController;
  late TextEditingController _socialLinkController;

  @override
  void initState() {
    super.initState();
    _profileSubTabController = TabController(length: 4, vsync: this);
    _mainSettingsTabController = TabController(length: 3, vsync: this);

    final profile = widget.userProfile ?? UserProfile.fromFullnameAndEmail('New User', 'user@parsajournals.com');
    _firstNameController = TextEditingController(text: profile.firstName);
    _lastNameController = TextEditingController(text: profile.lastName);
    _usernameController = TextEditingController(text: profile.username);
    _bioController = TextEditingController(text: profile.bio);
    _websiteLinkController = TextEditingController(text: profile.websiteLink);
    _githubLinkController = TextEditingController(text: profile.githubLink);
    _socialLinkController = TextEditingController(text: profile.socialLink);
  }

  // Custom Granular Access Search Controller
  final _searchUsernameController = TextEditingController();
  final List<String> _grantedUsernames = ['alex_dev', 'sarah_m'];

  // Dynamic Blocked Users List
  final List<String> _blockedUsers = ['spammer_bot_99'];

  String _selectedLocale = 'en';
  bool _notificationsEnabled = true;

  // Dynamic Security Toggles (Default is OFF so users can dynamically enable them)
  bool _pincodeFaceId = false;
  bool _twoStepAuth = false;
  String _autoDeleteMessages = 'Off'; // Options: Off, 15 Hours, 1 Day

  // Privacy Controls State - Default is EVERYONE
  final String _lastSeenPrivacy = 'Everyone';
  final String _profilePhotoPrivacy = 'Everyone';
  final String _bioPrivacy = 'Everyone';
  final String _giftsPrivacy = 'Everyone';
  final String _birthdayPrivacy = 'Everyone';
  final String _savedMusicPrivacy = 'Everyone';
  final String _forwardsPrivacy = 'Everyone';
  final String _callsPrivacy = 'Everyone';
  final String _voiceMessagesPrivacy = 'Everyone';
  final String _textMessagesPrivacy = 'Everyone';
  final String _invitesPrivacy = 'Everyone';

  final List<String> _appThemes = [
    'OLED Pitch Black', 'Telegram Blue', 'Cyberpunk Neon', 'Midnight Purple',
    'Emerald Matrix', 'Deep Space', 'Sunset Orange', 'Solarized Dark',
    'Rose Gold', 'Ice Cyan', 'Tokyo Night', 'Dracula Dark',
    'Nord Frost', 'Synthwave', 'Obsidian Gold', 'Emerald Forest',
    'Coral Red', 'Sapphire Deep', 'Amethyst Dark', 'Slate Gray',
    'Vaporwave', 'Aurora Borealis', 'Titanium Black', 'Quantum Teal', 'Royal Violet'
  ];



  // 1. Passcode & Face ID Setup Flow Modal
  void _openPasscodeSetupModal() {
    final pinController = TextEditingController();
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
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: const [
                        Icon(Icons.fingerprint, color: AppTheme.emeraldAccent, size: 22),
                        SizedBox(width: 8),
                        Text('Passcode & Face ID Setup', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                      ],
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white54),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                const Text('Create a 4-digit Passcode PIN for app lock:', style: TextStyle(color: Colors.white70, fontSize: 12)),
                const SizedBox(height: 14),
                TextField(
                  controller: pinController,
                  keyboardType: TextInputType.number,
                  obscureText: true,
                  maxLength: 4,
                  textAlign: TextAlign.center,
                  style: const TextStyle(fontSize: 22, color: Colors.white, letterSpacing: 8),
                  decoration: InputDecoration(
                    hintText: '0000',
                    hintStyle: const TextStyle(color: Colors.white38, letterSpacing: 8),
                    filled: true,
                    fillColor: Colors.white.withValues(alpha: 0.08),
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(16)),
                  ),
                ),
                const SizedBox(height: 16),
                GradientButton(
                  text: 'Enable Passcode & Face ID',
                  gradient: AppTheme.telegramGradient,
                  onPressed: () {
                    if (pinController.text.length == 4) {
                      setState(() {
                        _pincodeFaceId = true;
                      });
                      Navigator.pop(context);
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Passcode & Face ID protection activated!')),
                      );
                    }
                  },
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  // 2. 2FA Enablement Setup Flow Modal
  void _open2FASetupModal() {
    final codeController = TextEditingController();
    const base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    final rng = Random.secure();
    final String secretKey = List.generate(16, (_) => base32Chars[rng.nextInt(32)]).join();

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
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: const [
                        Icon(Icons.shield, color: Colors.amberAccent, size: 22),
                        SizedBox(width: 8),
                        Text('Enable 2FA Protection', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                      ],
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white54),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Center(
                  child: Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(14),
                    ),
                    child: Image.network(
                      'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${Uri.encodeComponent("otpauth://totp/ParsaJournals:User?secret=$secretKey&issuer=ParsaJournals")}',
                      height: 120,
                      width: 120,
                      fit: BoxFit.contain,
                      errorBuilder: (context, error, stackTrace) => const Icon(Icons.qr_code_2, size: 80, color: Colors.black87),
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  decoration: BoxDecoration(
                    color: Colors.black45,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: Colors.amberAccent.withValues(alpha: 0.4)),
                  ),
                  child: Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Scan QR or Copy Key in Authenticator', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 11, color: Colors.white)),
                            const SizedBox(height: 2),
                            SelectableText('Secret Key: $secretKey', style: const TextStyle(color: Colors.amberAccent, fontSize: 11, fontFamily: 'monospace', fontWeight: FontWeight.bold)),
                          ],
                        ),
                      ),
                      IconButton(
                        icon: const Icon(Icons.copy, color: Colors.amberAccent, size: 18),
                        onPressed: () {
                          Clipboard.setData(ClipboardData(text: secretKey));
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Secret Key copied to clipboard!')),
                          );
                        },
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 14),
                const Text('Enter 6-digit code from Google Authenticator to confirm:', style: TextStyle(color: Colors.white70, fontSize: 12)),
                const SizedBox(height: 10),
                TextField(
                  controller: codeController,
                  keyboardType: TextInputType.number,
                  maxLength: 6,
                  textAlign: TextAlign.center,
                  style: const TextStyle(fontSize: 20, color: Colors.white, letterSpacing: 6),
                  decoration: InputDecoration(
                    hintText: '000000',
                    hintStyle: const TextStyle(color: Colors.white38, letterSpacing: 6),
                    filled: true,
                    fillColor: Colors.white.withValues(alpha: 0.08),
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(16)),
                  ),
                ),
                const SizedBox(height: 16),
                GradientButton(
                  text: 'Confirm & Activate 2FA',
                  gradient: AppTheme.cyanPurpleGradient,
                  onPressed: () {
                    if (codeController.text.length == 6) {
                      setState(() {
                        _twoStepAuth = true;
                      });
                      Navigator.pop(context);
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Two-Step Verification (2FA) activated!')),
                      );
                    }
                  },
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  // 3. Blocked Users Management Modal
  void _openBlockedUsersModal() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return GlassCard(
              margin: const EdgeInsets.all(16),
              child: SizedBox(
                height: 380,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text('Blocked Users (${_blockedUsers.length})', style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold)),
                        IconButton(
                          icon: const Icon(Icons.close, color: Colors.white54),
                          onPressed: () => Navigator.pop(context),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Expanded(
                      child: _blockedUsers.isEmpty
                          ? const Center(child: Text('No blocked users.', style: TextStyle(color: Colors.white54, fontSize: 13)))
                          : ListView.builder(
                              itemCount: _blockedUsers.length,
                              itemBuilder: (context, index) {
                                final user = _blockedUsers[index];
                                return Container(
                                  margin: const EdgeInsets.only(bottom: 8),
                                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                  decoration: BoxDecoration(
                                    color: Colors.white.withValues(alpha: 0.05),
                                    borderRadius: BorderRadius.circular(12),
                                    border: Border.all(color: Colors.redAccent.withValues(alpha: 0.3)),
                                  ),
                                  child: Row(
                                    children: [
                                      CircleAvatar(
                                        radius: 14,
                                        backgroundColor: Colors.redAccent,
                                        child: Text(user[0].toUpperCase(), style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold)),
                                      ),
                                      const SizedBox(width: 10),
                                      Expanded(
                                        child: Text(user, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.white)),
                                      ),
                                      ElevatedButton(
                                        style: ElevatedButton.styleFrom(backgroundColor: AppTheme.telegramBlue, padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4)),
                                        onPressed: () {
                                          setModalState(() {
                                            _blockedUsers.removeAt(index);
                                          });
                                          setState(() {});
                                        },
                                        child: const Text('Unblock', style: TextStyle(fontSize: 11, color: Colors.white)),
                                      ),
                                    ],
                                  ),
                                );
                              },
                            ),
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  // 4. Auto-Delete Messages Timer Modal
  void _openAutoDeleteModal() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Auto-Delete Messages Duration', style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold)),
              const SizedBox(height: 14),
              _buildAutoDeleteTile('Off (Disabled)'),
              _buildAutoDeleteTile('15 Hours'),
              _buildAutoDeleteTile('1 Day (24 Hours)'),
            ],
          ),
        );
      },
    );
  }

  Widget _buildAutoDeleteTile(String option) {
    final isSelected = _autoDeleteMessages == option;
    return ListTile(
      leading: Icon(Icons.timer, color: isSelected ? AppTheme.telegramBlue : Colors.white54),
      title: Text(option, style: TextStyle(color: isSelected ? AppTheme.telegramBlue : Colors.white, fontWeight: isSelected ? FontWeight.bold : FontWeight.normal)),
      onTap: () {
        setState(() {
          _autoDeleteMessages = option;
        });
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Auto-delete timer set to $option')),
        );
      },
    );
  }

  void _openCustomUserAccessModal(String privacyFeature) {
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
                  height: 440,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('Custom User Access ($privacyFeature)', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                          IconButton(
                            icon: const Icon(Icons.close, color: Colors.white54),
                            onPressed: () => Navigator.pop(context),
                          ),
                        ],
                      ),
                      const SizedBox(height: 10),
                      const Text('Search username to grant or revoke explicit access:', style: TextStyle(color: Colors.white60, fontSize: 12)),
                      const SizedBox(height: 10),
                      Row(
                        children: [
                          Expanded(
                            child: TextField(
                              controller: _searchUsernameController,
                              style: const TextStyle(color: Colors.white, fontSize: 13),
                              decoration: InputDecoration(
                                prefixIcon: const Icon(Icons.search, color: AppTheme.telegramBlue),
                                hintText: 'Enter username (e.g. john_doe)...',
                                hintStyle: const TextStyle(color: Colors.white38, fontSize: 12),
                                filled: true,
                                fillColor: Colors.white.withValues(alpha: 0.05),
                                contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                              ),
                            ),
                          ),
                          const SizedBox(width: 8),
                          ElevatedButton(
                            style: ElevatedButton.styleFrom(backgroundColor: AppTheme.telegramBlue, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                            onPressed: () {
                              final text = _searchUsernameController.text.trim();
                              if (text.isNotEmpty && !_grantedUsernames.contains(text)) {
                                setModalState(() {
                                  _grantedUsernames.add(text);
                                });
                                _searchUsernameController.clear();
                              }
                            },
                            child: const Text('Grant'),
                          ),
                        ],
                      ),
                      const SizedBox(height: 14),
                      const Text('GRANTED USERS PREVIEW CARD', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.white54)),
                      const SizedBox(height: 8),
                      Expanded(
                        child: _grantedUsernames.isEmpty
                            ? const Center(child: Text('No custom user access granted yet.', style: TextStyle(color: Colors.white54, fontSize: 13)))
                            : ListView.builder(
                                itemCount: _grantedUsernames.length,
                                itemBuilder: (context, index) {
                                  final user = _grantedUsernames[index];
                                  return Container(
                                    margin: const EdgeInsets.only(bottom: 8),
                                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                    decoration: BoxDecoration(
                                      color: Colors.white.withValues(alpha: 0.05),
                                      borderRadius: BorderRadius.circular(12),
                                      border: Border.all(color: AppTheme.emeraldAccent.withValues(alpha: 0.3)),
                                    ),
                                    child: Row(
                                      children: [
                                        CircleAvatar(
                                          radius: 14,
                                          backgroundColor: AppTheme.telegramBlue,
                                          child: Text(user[0].toUpperCase(), style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold)),
                                        ),
                                        const SizedBox(width: 10),
                                        Expanded(
                                          child: Text('@$user', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.white)),
                                        ),
                                        IconButton(
                                          icon: const Icon(Icons.remove_circle, color: Colors.redAccent, size: 20),
                                          onPressed: () {
                                            setModalState(() {
                                              _grantedUsernames.removeAt(index);
                                            });
                                          },
                                        ),
                                      ],
                                    ),
                                  );
                                },
                              ),
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

  void _openThemeSelector() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: SizedBox(
            height: 400,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Select App Theme (25+ Color Palettes)', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                const SizedBox(height: 12),
                Expanded(
                  child: ListView.builder(
                    itemCount: _appThemes.length,
                    itemBuilder: (context, index) {
                      final themeName = _appThemes[index];
                      final isSelected = widget.currentTheme == themeName;
                      return ListTile(
                        leading: Icon(Icons.palette, color: isSelected ? AppTheme.telegramBlue : Colors.white54),
                        title: Text(themeName, style: TextStyle(color: Colors.white, fontWeight: isSelected ? FontWeight.bold : FontWeight.normal)),
                        trailing: isSelected ? const Icon(Icons.check, color: AppTheme.telegramBlue) : null,
                        onTap: () {
                          widget.onThemeChanged(themeName);
                          Navigator.pop(context);
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(content: Text('App Theme updated to $themeName!')),
                          );
                        },
                      );
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

  void _openProfileCustomizerSuite() {
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
              child: Container(
                margin: const EdgeInsets.all(16),
                child: ConstrainedBox(
                  constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * 0.85),
                  child: GlassCard(
                    padding: const EdgeInsets.all(20),
                    child: SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Expanded(
                                child: Row(
                                  children: const [
                                    Icon(Icons.settings, color: AppTheme.telegramBlue, size: 20),
                                    SizedBox(width: 8),
                                    Expanded(
                                      child: Text(
                                        'Profile Settings & Customizer Suite',
                                        style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white),
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              IconButton(
                                icon: const Icon(Icons.close, color: Colors.white54),
                                onPressed: () => Navigator.pop(context),
                              ),
                            ],
                          ),
                          const SizedBox(height: 14),

                          Container(
                            height: 120,
                            width: double.infinity,
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(16),
                              gradient: const LinearGradient(
                                colors: [Color(0xFF2E1065), Color(0xFF5B21B6), Color(0xFF1E1B4B)],
                                begin: Alignment.topLeft,
                                end: Alignment.bottomRight,
                              ),
                              border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
                            ),
                          ),

                          Transform.translate(
                            offset: const Offset(16, -30),
                            child: Row(
                              children: [
                                CircleAvatar(
                                  radius: 36,
                                  backgroundColor: Colors.black,
                                  child: const CircleAvatar(
                                    radius: 34,
                                    backgroundColor: AppTheme.telegramBlue,
                                    child: Text('PB', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.white)),
                                  ),
                                ),
                                const SizedBox(width: 14),
                                Padding(
                                  padding: const EdgeInsets.only(top: 26.0),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        '${_firstNameController.text} ${_lastNameController.text}',
                                        style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white),
                                      ),
                                      Text('@${_usernameController.text}', style: const TextStyle(color: Colors.white54, fontSize: 13)),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(height: 4),

                          Row(
                            children: [
                              Expanded(
                                child: _buildFormField('First Name *', _firstNameController),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: _buildFormField('Last Name *', _lastNameController),
                              ),
                            ],
                          ),
                          const SizedBox(height: 12),

                          _buildFormField('@Username *', _usernameController),
                          const SizedBox(height: 12),

                          _buildFormField('Bio / Headline (Optional)', _bioController, maxLines: 2),
                          const SizedBox(height: 12),

                          _buildFormField('Website / Personal Link (Optional)', _websiteLinkController),
                          const SizedBox(height: 12),

                          _buildFormField('GitHub / Portfolio Link (Optional)', _githubLinkController),
                          const SizedBox(height: 12),

                          _buildFormField('Social / X / LinkedIn Link (Optional)', _socialLinkController),
                          const SizedBox(height: 20),

                          Row(
                            children: [
                              Expanded(
                                child: TextButton(
                                  style: TextButton.styleFrom(
                                    padding: const EdgeInsets.symmetric(vertical: 14),
                                    backgroundColor: Colors.white.withValues(alpha: 0.08),
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                  ),
                                  onPressed: () => Navigator.pop(context),
                                  child: const Text('Cancel', style: TextStyle(color: Colors.white70)),
                                ),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                flex: 2,
                                child: GradientButton(
                                  text: 'Save All Settings',
                                  gradient: AppTheme.telegramGradient,
                                  onPressed: () async {
                                    final messenger = ScaffoldMessenger.of(context);
                                    final nav = Navigator.of(context);

                                    if (widget.userProfile != null) {
                                      widget.userProfile!.firstName = _firstNameController.text.trim();
                                      widget.userProfile!.lastName = _lastNameController.text.trim();
                                      widget.userProfile!.username = _usernameController.text.trim();
                                      widget.userProfile!.bio = _bioController.text.trim();
                                      widget.userProfile!.websiteLink = _websiteLinkController.text.trim();
                                      widget.userProfile!.githubLink = _githubLinkController.text.trim();
                                      widget.userProfile!.socialLink = _socialLinkController.text.trim();
                                    }
                                    setState(() {});

                                    try {
                                      final prefs = await SharedPreferences.getInstance();
                                      await prefs.setString('user_first_name', _firstNameController.text.trim());
                                      await prefs.setString('user_last_name', _lastNameController.text.trim());
                                      await prefs.setString('user_name', '${_firstNameController.text.trim()} ${_lastNameController.text.trim()}'.trim());
                                      await prefs.setString('user_username', _usernameController.text.trim());
                                      await prefs.setString('user_bio', _bioController.text.trim());
                                      await prefs.setString('user_website', _websiteLinkController.text.trim());
                                      await prefs.setString('user_github', _githubLinkController.text.trim());
                                      await prefs.setString('user_social', _socialLinkController.text.trim());

                                      if (widget.userProfile != null) {
                                        await ApiService().updateUserProfile(widget.userProfile!.toJson());
                                      }
                                    } catch (_) {}

                                    if (mounted) {
                                      nav.pop();
                                      messenger.showSnackBar(
                                        const SnackBar(content: Text('Profile Settings & Database Synced!')),
                                      );
                                    }
                                  },
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            );
          },
        );
      },
    );
  }

  void _confirmAccountDeletionModal() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: const Color(0xFF1E1E2C),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(20),
            side: BorderSide(color: Colors.redAccent.withValues(alpha: 0.5), width: 1.5),
          ),
          title: const Row(
            children: [
              Icon(Icons.warning_amber_rounded, color: Colors.redAccent, size: 24),
              SizedBox(width: 10),
              Text('Delete Account?', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 17)),
            ],
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Are you sure you want to permanently delete your Parsa Journals account (${widget.userProfile?.email ?? ''})?',
                style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.4),
              ),
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.redAccent.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: Colors.redAccent.withValues(alpha: 0.3)),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.info_outline, color: Colors.redAccent, size: 18),
                    SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'This will wipe all local sessions, 2FA credentials, journals, and database records. This action cannot be undone.',
                        style: TextStyle(color: Colors.white60, fontSize: 11, height: 1.3),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel', style: TextStyle(color: Colors.white60)),
            ),
            ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.redAccent,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              icon: const Icon(Icons.delete_forever, color: Colors.white, size: 18),
              label: const Text('Delete Account', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              onPressed: () async {
                final nav = Navigator.of(context);
                final messenger = ScaffoldMessenger.of(context);
                nav.pop();

                try {
                  final email = widget.userProfile?.email ?? '';
                  if (email.isNotEmpty) {
                    await ApiService().deleteAccount(email);
                  }
                  final prefs = await SharedPreferences.getInstance();
                  await prefs.clear();
                } catch (_) {}

                widget.onRequireLogin();
                messenger.showSnackBar(
                  const SnackBar(
                    content: Text('Your account and profile data have been permanently deleted.'),
                    backgroundColor: Colors.redAccent,
                  ),
                );
              },
            ),
          ],
        );
      },
    );
  }

  Widget _buildFormField(String label, TextEditingController controller, {int maxLines = 1}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(color: Colors.white70, fontSize: 12)),
        const SizedBox(height: 4),
        TextField(
          controller: controller,
          maxLines: maxLines,
          style: const TextStyle(color: Colors.white, fontSize: 13),
          decoration: InputDecoration(
            filled: true,
            fillColor: Colors.white.withValues(alpha: 0.05),
            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.white.withValues(alpha: 0.15))),
            focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: AppTheme.telegramBlue)),
          ),
        ),
      ],
    );
  }

  String _extractDomainOrLabel(String rawUrl, String fallback) {
    try {
      final clean = rawUrl.replaceAll(RegExp(r'^https?://(www\.)?'), '');
      if (clean.length > 22) {
        return '${clean.substring(0, 20)}...';
      }
      return clean.isNotEmpty ? clean : fallback;
    } catch (_) {
      return fallback;
    }
  }

  Widget _buildLinkButton(IconData icon, String label, String rawUrl, Color color) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: () async {
          if (rawUrl.isEmpty) return;
          try {
            final uri = Uri.parse(rawUrl.startsWith('http') ? rawUrl : 'https://$rawUrl');
            if (await canLaunchUrl(uri)) {
              await launchUrl(uri, mode: LaunchMode.externalApplication);
            } else {
              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('Opening link: $rawUrl')),
                );
              }
            }
          } catch (_) {}
        },
        borderRadius: BorderRadius.circular(16),
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 9),
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.14),
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: color.withValues(alpha: 0.4), width: 1.2),
            boxShadow: [
              BoxShadow(
                color: color.withValues(alpha: 0.1),
                blurRadius: 8,
              ),
            ],
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(icon, size: 16, color: color),
              const SizedBox(width: 8),
              ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 180),
                child: Text(
                  label,
                  style: TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.bold,
                    color: color,
                  ),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              const SizedBox(width: 6),
              Icon(Icons.arrow_outward, size: 14, color: color.withValues(alpha: 0.8)),
            ],
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 8),
          color: Colors.transparent,
          child: TabBar(
            controller: _mainSettingsTabController,
            isScrollable: true,
            tabAlignment: TabAlignment.start,
            indicatorSize: TabBarIndicatorSize.tab,
            indicatorPadding: const EdgeInsets.symmetric(horizontal: 4, vertical: 4),
            indicator: BoxDecoration(
              color: AppTheme.cyanAccent.withValues(alpha: 0.15),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: AppTheme.cyanAccent.withValues(alpha: 0.4)),
            ),
            labelColor: AppTheme.cyanAccent,
            unselectedLabelColor: Colors.white54,
            labelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
            padding: EdgeInsets.zero,
            tabs: const [
              Tab(child: Padding(padding: EdgeInsets.symmetric(horizontal: 8), child: Row(children: [Icon(Icons.person, size: 16), SizedBox(width: 6), Text('Profile View')]))),
              Tab(child: Padding(padding: EdgeInsets.symmetric(horizontal: 8), child: Row(children: [Icon(Icons.security_rounded, size: 16), SizedBox(width: 6), Text('Privacy Suite')]))),
              Tab(child: Padding(padding: EdgeInsets.symmetric(horizontal: 8), child: Row(children: [Icon(Icons.settings, size: 16), SizedBox(width: 6), Text('App Preferences')]))),
            ],
          ),
        ),

        Expanded(
          child: TabBarView(
            controller: _mainSettingsTabController,
            children: [
              // 1. Profile View Subtab
              SingleChildScrollView(
                physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 120),
                child: Column(
                  children: [
                    GlassCard(
                      padding: const EdgeInsets.all(20),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              CircleAvatar(
                                radius: 40,
                                backgroundColor: Colors.black,
                                child: CircleAvatar(
                                  radius: 38,
                                  backgroundColor: AppTheme.telegramBlue,
                                  child: Text(
                                    UserProfile(firstName: _firstNameController.text, lastName: _lastNameController.text, email: '', username: '').initials,
                                    style: const TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.white),
                                  ),
                                ),
                              ),
                              const SizedBox(width: 16),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      children: [
                                        Text(
                                          '${_firstNameController.text} ${_lastNameController.text}',
                                          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                                        ),
                                        const SizedBox(width: 6),
                                        const Icon(Icons.check_circle, color: AppTheme.emeraldAccent, size: 18),
                                      ],
                                    ),
                                    const SizedBox(height: 2),
                                    Text('@${_usernameController.text}', style: const TextStyle(color: Colors.white54, fontSize: 13)),
                                  ],
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 18),

                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceAround,
                            children: [
                              _buildMetricItem('0', 'Posts'),
                              _buildMetricItem('0', 'Followers'),
                              _buildMetricItem('0', 'Following'),
                              _buildMetricItem('0', 'Journals'),
                              _buildMetricItem((widget.userProfile?.email.trim().toLowerCase() ?? '') == 'parsabe99@gmail.com' ? '∞' : (widget.userProfile?.cp ?? '0'), 'CP'),
                            ],
                          ),
                          const SizedBox(height: 16),

                          Text(
                            _bioController.text,
                            style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.w500),
                          ),
                          const SizedBox(height: 12),

                          // Optional User Links Cards with Href Launching
                          Wrap(
                            spacing: 10,
                            runSpacing: 10,
                            children: [
                              if (_websiteLinkController.text.isNotEmpty)
                                _buildLinkButton(Icons.language, _extractDomainOrLabel(_websiteLinkController.text, 'Website'), _websiteLinkController.text, AppTheme.telegramBlue),
                              if (_githubLinkController.text.isNotEmpty)
                                _buildLinkButton(Icons.code_rounded, _extractDomainOrLabel(_githubLinkController.text, 'GitHub'), _githubLinkController.text, AppTheme.cyanAccent),
                              if (_socialLinkController.text.isNotEmpty)
                                _buildLinkButton(Icons.share_rounded, _extractDomainOrLabel(_socialLinkController.text, 'Social / X'), _socialLinkController.text, AppTheme.pinkPrimary),
                            ],
                          ),
                          const SizedBox(height: 16),

                          Row(
                            children: [
                              Expanded(
                                flex: 3,
                                child: Container(
                                  decoration: BoxDecoration(
                                    gradient: const LinearGradient(
                                      colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
                                      begin: Alignment.topLeft,
                                      end: Alignment.bottomRight,
                                    ),
                                    borderRadius: BorderRadius.circular(14),
                                    boxShadow: [
                                      BoxShadow(
                                        color: const Color(0xFF6366F1).withValues(alpha: 0.35),
                                        blurRadius: 10,
                                        offset: const Offset(0, 4),
                                      ),
                                    ],
                                  ),
                                  child: Material(
                                    color: Colors.transparent,
                                    child: InkWell(
                                      borderRadius: BorderRadius.circular(14),
                                      onTap: _openProfileCustomizerSuite,
                                      child: const Padding(
                                        padding: EdgeInsets.symmetric(vertical: 12),
                                        child: Row(
                                          mainAxisAlignment: MainAxisAlignment.center,
                                          children: [
                                            Icon(Icons.tune_rounded, size: 18, color: Colors.white),
                                            SizedBox(width: 8),
                                            Text(
                                              'Edit Profile & Suite',
                                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.white),
                                            ),
                                          ],
                                        ),
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                              const SizedBox(width: 10),
                              Expanded(
                                flex: 2,
                                child: Container(
                                  decoration: BoxDecoration(
                                    color: Colors.redAccent.withValues(alpha: 0.15),
                                    borderRadius: BorderRadius.circular(14),
                                    border: Border.all(color: Colors.redAccent.withValues(alpha: 0.4)),
                                  ),
                                  child: Material(
                                    color: Colors.transparent,
                                    child: InkWell(
                                      borderRadius: BorderRadius.circular(14),
                                      onTap: () {
                                        showDialog(
                                          context: context,
                                          builder: (context) {
                                            return AlertDialog(
                                              backgroundColor: AppTheme.surface,
                                              title: const Text('Log Out?', style: TextStyle(color: Colors.white, fontSize: 16)),
                                              content: const Text('Are you sure you want to log out of your session?', style: TextStyle(color: Colors.white70, fontSize: 13)),
                                              actions: [
                                                TextButton(
                                                  onPressed: () => Navigator.pop(context),
                                                  child: const Text('Cancel', style: TextStyle(color: Colors.white54)),
                                                ),
                                                ElevatedButton(
                                                  style: ElevatedButton.styleFrom(backgroundColor: Colors.redAccent),
                                                  onPressed: () {
                                                    Navigator.pop(context);
                                                    widget.onRequireLogin();
                                                  },
                                                  child: const Text('Log Out', style: TextStyle(color: Colors.white)),
                                                ),
                                              ],
                                            );
                                          },
                                        );
                                      },
                                      child: const Padding(
                                        padding: EdgeInsets.symmetric(vertical: 12),
                                        child: Row(
                                          mainAxisAlignment: MainAxisAlignment.center,
                                          children: [
                                            Icon(Icons.logout_rounded, size: 18, color: Colors.redAccent),
                                            SizedBox(width: 6),
                                            Text(
                                              'Log Out',
                                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.redAccent),
                                            ),
                                          ],
                                        ),
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 12),
                          Container(
                            decoration: BoxDecoration(
                              color: Colors.red.withValues(alpha: 0.12),
                              borderRadius: BorderRadius.circular(14),
                              border: Border.all(color: Colors.redAccent.withValues(alpha: 0.5), width: 1.2),
                            ),
                            child: Material(
                              color: Colors.transparent,
                              child: InkWell(
                                borderRadius: BorderRadius.circular(14),
                                onTap: _confirmAccountDeletionModal,
                                child: const Padding(
                                  padding: EdgeInsets.symmetric(vertical: 12),
                                  child: Row(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Icon(Icons.delete_forever_rounded, size: 18, color: Colors.redAccent),
                                      SizedBox(width: 8),
                                      Text(
                                        'Delete Account & Erase All Data',
                                        style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.redAccent),
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),

                    Container(
                      height: 44,
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.05),
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: Colors.white.withValues(alpha: 0.1)),
                      ),
                      child: TabBar(
                        controller: _profileSubTabController,
                        isScrollable: true,
                        tabAlignment: TabAlignment.start,
                        indicatorSize: TabBarIndicatorSize.tab,
                        indicatorPadding: const EdgeInsets.symmetric(horizontal: 3, vertical: 3),
                        indicator: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [AppTheme.telegramBlue, AppTheme.cyanAccent],
                          ),
                          borderRadius: BorderRadius.circular(11),
                          boxShadow: [
                            BoxShadow(
                              color: AppTheme.cyanAccent.withValues(alpha: 0.3),
                              blurRadius: 6,
                            ),
                          ],
                        ),
                        labelColor: Colors.white,
                        unselectedLabelColor: Colors.white54,
                        labelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
                        padding: EdgeInsets.zero,
                        tabs: const [
                          Tab(text: '🧩 Posts (0)'),
                          Tab(text: '📓 Journals'),
                          Tab(text: '⚔ Sandika'),
                          Tab(text: '🎧 Amadeus'),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),

                    SizedBox(
                      height: 140,
                      child: TabBarView(
                        controller: _profileSubTabController,
                        children: [
                          GlassCard(
                            child: const Center(
                              child: Text('No posts published yet.', style: TextStyle(color: Colors.white54, fontSize: 14)),
                            ),
                          ),
                          GlassCard(
                            child: const Center(
                              child: Text('No journal entries published yet.', style: TextStyle(color: Colors.white54, fontSize: 14)),
                            ),
                          ),
                          GlassCard(
                            child: const Center(
                              child: Text('Sandika & Nigma Progress: Level 1 (0 XP)', style: TextStyle(color: AppTheme.cyanAccent, fontSize: 14, fontWeight: FontWeight.bold)),
                            ),
                          ),

                          GlassCard(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: const [
                                    Text('Amadeus Arkham Unlocked Audio Tapes', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Colors.amberAccent)),
                                    Icon(Icons.lock_open, color: Colors.amberAccent, size: 16),
                                  ],
                                ),
                                const SizedBox(height: 8),
                                const Text('Decipher ciphers in Sandika Portal to unlock permanent voice logs.', style: TextStyle(color: Colors.white54, fontSize: 11)),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              // 2. Full Privacy & Security Settings Subtab
              ListView(
                physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 120),
                children: [
                  const Text('Privacy & Security Suite', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white)),
                  const SizedBox(height: 14),

                  // Dynamic Security Group (Passkeys removed, Passcode & 2FA Setup Flow Modals)
                  GlassCard(
                    child: Column(
                      children: [
                        _buildPrivacyTile(Icons.block, 'Blocked Users', '${_blockedUsers.length} >', Colors.redAccent, _openBlockedUsersModal),
                        const Divider(color: Colors.white12),

                        // Dynamic Passcode & Face ID Toggle & Setup Modal
                        _buildPrivacyToggle(
                          Icons.fingerprint,
                          'Passcode & Face ID',
                          _pincodeFaceId,
                          (v) {
                            if (v) {
                              _openPasscodeSetupModal();
                            } else {
                              setState(() => _pincodeFaceId = false);
                            }
                          },
                          AppTheme.emeraldAccent,
                        ),
                        const Divider(color: Colors.white12),

                        // Dynamic 2FA Toggle & Setup Modal
                        _buildPrivacyToggle(
                          Icons.lock,
                          'Two-Step Verification (2FA)',
                          _twoStepAuth,
                          (v) {
                            if (v) {
                              _open2FASetupModal();
                            } else {
                              setState(() => _twoStepAuth = false);
                            }
                          },
                          Colors.amberAccent,
                        ),
                        const Divider(color: Colors.white12),

                        // Dynamic Auto-Delete Messages Selector (Off, 15 Hours, 1 Day)
                        _buildPrivacyTile(Icons.timer, 'Auto-Delete Messages', '$_autoDeleteMessages >', AppTheme.purpleAccent, _openAutoDeleteModal),
                      ],
                    ),
                  ),
                  const SizedBox(height: 18),

                  const Text('PRIVACY CONTROLS (DEFAULT: EVERYONE)', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.white54)),
                  const SizedBox(height: 8),

                  GlassCard(
                    child: Column(
                      children: [
                        _buildPrivacyTile(Icons.remove_red_eye, 'Last Seen & Online Status', _lastSeenPrivacy, Colors.white70, () => _openCustomUserAccessModal('Last Seen')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.account_circle, 'Profile Photos', _profilePhotoPrivacy, Colors.white70, () => _openCustomUserAccessModal('Profile Photos')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.info, 'Bio & Headline', _bioPrivacy, Colors.white70, () => _openCustomUserAccessModal('Bio')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.card_giftcard, 'Gifts & Badges', _giftsPrivacy, Colors.white70, () => _openCustomUserAccessModal('Gifts')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.cake, 'Birthday', _birthdayPrivacy, Colors.white70, () => _openCustomUserAccessModal('Birthday')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.music_note, 'Saved Music', _savedMusicPrivacy, Colors.white70, () => _openCustomUserAccessModal('Saved Music')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.shortcut, 'Forwarded Messages & Quotes', _forwardsPrivacy, Colors.white70, () => _openCustomUserAccessModal('Forwards')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.call, 'Voice & Video Calls', _callsPrivacy, Colors.white70, () => _openCustomUserAccessModal('Calls')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.mic, 'Voice Messages Privacy', _voiceMessagesPrivacy, Colors.white70, () => _openCustomUserAccessModal('Voice Messages')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.chat, 'Text Messages Privacy', _textMessagesPrivacy, Colors.white70, () => _openCustomUserAccessModal('Text Messages')),
                        const Divider(color: Colors.white12),
                        _buildPrivacyTile(Icons.group_add, 'Group & Channel Invites', _invitesPrivacy, Colors.white70, () => _openCustomUserAccessModal('Invites')),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),

                  const Text('DANGER ZONE', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.redAccent)),
                  const SizedBox(height: 8),
                  GlassCard(
                    child: ListTile(
                      leading: const Icon(Icons.delete_forever_rounded, color: Colors.redAccent, size: 24),
                      title: const Text('Delete Account & Wipe Data', style: TextStyle(color: Colors.redAccent, fontWeight: FontWeight.bold, fontSize: 14)),
                      subtitle: const Text('Permanently remove profile credentials, 2FA keys, and local session', style: TextStyle(color: Colors.white60, fontSize: 11)),
                      trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.redAccent),
                      onTap: _confirmAccountDeletionModal,
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),

              // 3. App Preferences Subtab
              ListView(
                physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
                children: [
                  const Text('App Preferences & Themes', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  GlassCard(
                    child: Column(
                      children: [
                        ListTile(
                          leading: const Icon(Icons.palette, color: AppTheme.cyanAccent),
                          title: const Text('App Theme (25+ Palettes)'),
                          subtitle: Text('Current: ${widget.currentTheme}'),
                          trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.white38),
                          onTap: _openThemeSelector,
                        ),
                        const Divider(color: Colors.white12),
                        ListTile(
                          leading: const Icon(Icons.language, color: AppTheme.telegramBlue),
                          title: const Text('Language (English / Deutsch)'),
                          subtitle: Text('Current: ${_selectedLocale.toUpperCase()}'),
                          trailing: DropdownButton<String>(
                            value: _selectedLocale,
                            dropdownColor: AppTheme.surface,
                            items: const [
                              DropdownMenuItem(value: 'en', child: Text('EN (English)')),
                              DropdownMenuItem(value: 'de', child: Text('DE (Deutsch)')),
                            ],
                            onChanged: (val) {
                              if (val != null) {
                                setState(() {
                                  _selectedLocale = val;
                                });
                              }
                            },
                          ),
                        ),
                        const Divider(color: Colors.white12),
                        SwitchListTile(
                          secondary: const Icon(Icons.notifications_active, color: AppTheme.pinkPrimary),
                          title: const Text('Push Notifications'),
                          subtitle: const Text('Real-time alerts for messages & papers'),
                          value: _notificationsEnabled,
                          onChanged: (val) {
                            setState(() {
                              _notificationsEnabled = val;
                            });
                          },
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      icon: const Icon(Icons.logout, color: Colors.white, size: 18),
                      label: const Text('Log Out of Account', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.white)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.redAccent,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                      ),
                      onPressed: () {
                        showDialog(
                          context: context,
                          builder: (context) {
                            return AlertDialog(
                              backgroundColor: AppTheme.surface,
                              title: const Text('Log Out?', style: TextStyle(color: Colors.white, fontSize: 16)),
                              content: const Text('Are you sure you want to log out of your session?', style: TextStyle(color: Colors.white70, fontSize: 13)),
                              actions: [
                                TextButton(
                                  onPressed: () => Navigator.pop(context),
                                  child: const Text('Cancel', style: TextStyle(color: Colors.white54)),
                                ),
                                ElevatedButton(
                                  style: ElevatedButton.styleFrom(backgroundColor: Colors.redAccent),
                                  onPressed: () {
                                    Navigator.pop(context);
                                    widget.onRequireLogin();
                                  },
                                  child: const Text('Log Out', style: TextStyle(color: Colors.white)),
                                ),
                              ],
                            );
                          },
                        );
                      },
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildPrivacyTile(IconData icon, String title, String trailing, Color color, VoidCallback onTap) {
    return ListTile(
      leading: Icon(icon, color: color, size: 20),
      title: Text(title, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500)),
      trailing: Text('$trailing >', style: const TextStyle(color: Colors.white54, fontSize: 12)),
      onTap: onTap,
    );
  }

  Widget _buildPrivacyToggle(IconData icon, String title, bool value, Function(bool) onChanged, Color color) {
    return SwitchListTile(
      secondary: Icon(icon, color: color, size: 20),
      title: Text(title, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500)),
      value: value,
      onChanged: onChanged,
    );
  }

  Widget _buildMetricItem(String count, String label) {
    return Column(
      children: [
        Text(count, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white)),
        const SizedBox(height: 2),
        Text(label, style: const TextStyle(fontSize: 11, color: Colors.white54)),
      ],
    );
  }
}
