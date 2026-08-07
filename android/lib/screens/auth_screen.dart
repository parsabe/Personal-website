import 'dart:math';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import '../widgets/gradient_button.dart';

class AuthScreen extends StatefulWidget {
  final Function(String name, String email, String username) onAuthSuccess;

  const AuthScreen({super.key, required this.onAuthSuccess});

  @override
  State<AuthScreen> createState() => _AuthScreenState();
}

class _AuthScreenState extends State<AuthScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final ApiService _api = ApiService();

  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _nameController = TextEditingController();
  final _usernameController = TextEditingController();
  final _twoFactorCodeController = TextEditingController();

  bool _isLoading = false;
  bool _requires2FA = false; // Step 2: 2FA Verification Mode!
  bool _isNewAccountSetup = false; // True during initial registration
  bool _showQRCode = true;
  String _user2FAKey = '';
  String _statusMessage = '';
  String _locale = 'en';

  String _generateBase32Secret() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    final rng = Random.secure();
    return List.generate(16, (_) => chars[rng.nextInt(32)]).join();
  }

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  void _submitAuth() async {
    final email = _emailController.text.trim();
    final password = _passwordController.text.trim();
    final name = _nameController.text.trim();
    final username = _usernameController.text.trim().replaceAll('@', '');

    final isSignUp = _tabController.index == 1;

    if (email.isEmpty || password.isEmpty) {
      setState(() {
        _statusMessage = 'Please fill in both Email and Password fields.';
      });
      return;
    }

    if (isSignUp && username.isEmpty) {
      setState(() {
        _statusMessage = 'Mandatory: Please enter a custom @Username for your account.';
      });
      return;
    }

    setState(() {
      _isLoading = true;
      _statusMessage = '';
    });

    final res = isSignUp
        ? await _api.register(name.isNotEmpty ? name : 'Parsa User', email, password)
        : await _api.login(email, password);

    setState(() {
      _isLoading = false;
    });

    if (res['status'] == 'success' || res['requires_2fa'] == true) {
      setState(() {
        _requires2FA = true; // Transition to 2FA Verification Gate!
        if (isSignUp) {
          _isNewAccountSetup = true;
          _showQRCode = true;
          _user2FAKey = _generateBase32Secret();
          _statusMessage = 'Account Created with @$username! Scan QR Code or copy Secret Key into Google Authenticator, then enter your 6-digit TOTP code.';
        } else {
          _isNewAccountSetup = false;
          _showQRCode = false; // Direct 6-digit code entry for existing users!
          _statusMessage = '2FA Protection Active for $email. Enter your 6-digit code from Google Authenticator.';
        }
      });
    } else {
      setState(() {
        _statusMessage = res['message'] ?? 'Authentication failed. Check your server settings.';
      });
    }
  }

  void _verify2FACode() async {
    final code = _twoFactorCodeController.text.trim();
    if (code.length != 6) {
      setState(() {
        _statusMessage = 'Please enter a valid 6-digit 2FA code (e.g. 123456).';
      });
      return;
    }

    setState(() {
      _isLoading = true;
    });

    final res = await _api.verify2FA(code);

    setState(() {
      _isLoading = false;
    });

    if (res['status'] == 'success' || res['verified'] == true) {
      if (mounted && Navigator.canPop(context)) {
        Navigator.pop(context);
      }
      final name = _nameController.text.trim().isNotEmpty ? _nameController.text.trim() : (_emailController.text.contains('@') ? _emailController.text.split('@')[0] : 'User');
      final email = _emailController.text.trim().isNotEmpty ? _emailController.text.trim() : 'user@parsajournals.com';
      String username = _usernameController.text.trim().replaceAll('@', '');
      if (username.isEmpty) {
        username = email.contains('@') ? email.split('@')[0] : 'user';
      }
      widget.onAuthSuccess(name, email, username); // Authentication & 2FA complete with custom username!
    } else {
      setState(() {
        _statusMessage = res['message'] ?? 'Invalid 2FA Verification Code.';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          color: AppTheme.background,
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 20.0, vertical: 16.0),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const CircleAvatar(
                    radius: 36,
                    backgroundColor: AppTheme.telegramBlue,
                    child: Text('PJ', style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.white)),
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'Parsa Journals',
                    style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.white),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Authentication Gate & Research Portal Access',
                    style: TextStyle(color: Colors.white60, fontSize: 12),
                  ),
                  const SizedBox(height: 20),

                  GlassCard(
                    padding: const EdgeInsets.all(16),
                    child: _requires2FA ? _build2FAGateView() : _buildLoginSignupView(),
                  ),
                  const SizedBox(height: 16),

                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      TextButton.icon(
                        icon: const Icon(Icons.language, color: Colors.white54, size: 16),
                        label: Text('Lang: ${_locale.toUpperCase()}', style: const TextStyle(color: Colors.white54, fontSize: 12)),
                        onPressed: () {
                          setState(() {
                            _locale = _locale == 'en' ? 'de' : 'en';
                          });
                        },
                      ),
                      TextButton(
                        onPressed: () {
                          widget.onAuthSuccess('Guest User', 'guest@parsajournals.com', 'guest');
                        },
                        child: const Text('Continue as Guest →', style: TextStyle(color: AppTheme.telegramBlue, fontSize: 13)),
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
  }

  Widget _buildLoginSignupView() {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          decoration: BoxDecoration(
            color: Colors.white.withValues(alpha: 0.08),
            borderRadius: BorderRadius.circular(20),
          ),
          child: TabBar(
            controller: _tabController,
            indicator: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.2),
              borderRadius: BorderRadius.circular(20),
            ),
            indicatorSize: TabBarIndicatorSize.tab,
            labelColor: Colors.white,
            unselectedLabelColor: Colors.white54,
            tabs: const [
              Tab(text: 'Sign In'),
              Tab(text: 'Create Account'),
            ],
          ),
        ),
        const SizedBox(height: 16),

        if (_statusMessage.isNotEmpty) ...[
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.amberAccent.withValues(alpha: 0.15),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.amberAccent),
            ),
            child: Text(_statusMessage, style: const TextStyle(color: Colors.amberAccent, fontSize: 12), textAlign: TextAlign.center),
          ),
          const SizedBox(height: 12),
        ],

        SizedBox(
          height: 310,
          child: TabBarView(
            controller: _tabController,
            children: [
              SingleChildScrollView(
                child: Column(
                  children: [
                    _buildGlassTextField(_emailController, 'Email Address', Icons.email),
                    const SizedBox(height: 10),
                    _buildGlassTextField(_passwordController, 'Password', Icons.lock, isPassword: true),
                    const SizedBox(height: 16),
                    SizedBox(
                      width: double.infinity,
                      child: _isLoading
                          ? const Center(child: CircularProgressIndicator(color: AppTheme.telegramBlue))
                          : GradientButton(
                              text: 'Sign In & Verify 2FA',
                              gradient: AppTheme.telegramGradient,
                              onPressed: _submitAuth,
                            ),
                    ),
                  ],
                ),
              ),

              SingleChildScrollView(
                child: Column(
                  children: [
                    _buildGlassTextField(_nameController, 'Full Name', Icons.person),
                    const SizedBox(height: 8),
                    _buildGlassTextField(_usernameController, 'Choose @Username (Required)', Icons.alternate_email),
                    const SizedBox(height: 8),
                    _buildGlassTextField(_emailController, 'Email Address', Icons.email),
                    const SizedBox(height: 8),
                    _buildGlassTextField(_passwordController, 'Password', Icons.lock, isPassword: true),
                    const SizedBox(height: 14),
                    SizedBox(
                      width: double.infinity,
                      child: _isLoading
                          ? const Center(child: CircularProgressIndicator(color: AppTheme.pinkPrimary))
                          : GradientButton(
                              text: 'Create Account with 2FA',
                              gradient: AppTheme.primaryGradient,
                              onPressed: _submitAuth,
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

  // 2FA TOTP Code Gate Screen View (Matching Laravel TwoFactorAuthController.php)
  Widget _build2FAGateView() {
    final email = _emailController.text.isNotEmpty ? _emailController.text : "user@parsajournals.com";
    final qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${Uri.encodeComponent("otpauth://totp/ParsaJournals:$email?secret=$_user2FAKey&issuer=ParsaJournals")}';

    return Column(
      mainAxisSize: MainAxisSize.min,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              children: const [
                Icon(Icons.shield, color: AppTheme.emeraldAccent, size: 22),
                SizedBox(width: 8),
                Text('Two-Factor Verification (2FA)', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
              ],
            ),
            IconButton(
              icon: const Icon(Icons.arrow_back, color: Colors.white54),
              onPressed: () {
                setState(() {
                  _requires2FA = false;
                  _statusMessage = '';
                });
              },
            ),
          ],
        ),
        const SizedBox(height: 10),

        if (_isNewAccountSetup) ...[
          // Interactive Mode Selector Chips (Shown only during 1-time setup for new accounts)
          Row(
            children: [
              Expanded(
                child: FilterChip(
                  showCheckmark: false,
                  avatar: const Icon(Icons.qr_code_2, size: 16, color: AppTheme.emeraldAccent),
                  label: const Text('Scan QR / Key', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.white)),
                  selected: _showQRCode,
                  selectedColor: AppTheme.emeraldAccent.withValues(alpha: 0.25),
                  backgroundColor: Colors.white.withValues(alpha: 0.05),
                  onSelected: (val) => setState(() => _showQRCode = true),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: FilterChip(
                  showCheckmark: false,
                  avatar: const Icon(Icons.pin, size: 16, color: AppTheme.telegramBlue),
                  label: const Text('Enter 6-Digit Code', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.white)),
                  selected: !_showQRCode,
                  selectedColor: AppTheme.telegramBlue.withValues(alpha: 0.25),
                  backgroundColor: Colors.white.withValues(alpha: 0.05),
                  onSelected: (val) => setState(() => _showQRCode = false),
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
        ],

        if (_showQRCode) ...[
          // Scannable QR Code Image
          Center(
            child: Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(color: AppTheme.emeraldAccent.withValues(alpha: 0.3), blurRadius: 10),
                ],
              ),
              child: Image.network(
                qrUrl,
                height: 130,
                width: 130,
                fit: BoxFit.contain,
                errorBuilder: (context, error, stackTrace) => Column(
                  mainAxisSize: MainAxisSize.min,
                  children: const [
                    Icon(Icons.qr_code_2, size: 70, color: Colors.black87),
                    Text('Scan 2FA QR', style: TextStyle(color: Colors.black87, fontSize: 11, fontWeight: FontWeight.bold)),
                  ],
                ),
              ),
            ),
          ),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: Colors.black45,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: AppTheme.emeraldAccent.withValues(alpha: 0.4)),
            ),
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Account: $email', style: const TextStyle(fontSize: 11, color: Colors.white70), overflow: TextOverflow.ellipsis),
                      const SizedBox(height: 2),
                      SelectableText(
                        'Secret: $_user2FAKey',
                        style: const TextStyle(color: AppTheme.emeraldAccent, fontSize: 11, fontFamily: 'monospace', fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.copy, color: AppTheme.emeraldAccent, size: 18),
                  onPressed: () {
                    Clipboard.setData(ClipboardData(text: _user2FAKey));
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('2FA Secret Key copied to clipboard!')),
                    );
                  },
                ),
              ],
            ),
          ),
        ] else ...[
          const Text('Enter 6-digit code from Google Authenticator to complete verification:', style: TextStyle(color: Colors.white70, fontSize: 12)),
          const SizedBox(height: 12),
          TextField(
            controller: _twoFactorCodeController,
            keyboardType: TextInputType.number,
            maxLength: 6,
            textAlign: TextAlign.center,
            style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold, letterSpacing: 8),
            decoration: InputDecoration(
              hintText: '000000',
              hintStyle: const TextStyle(color: Colors.white38, letterSpacing: 8),
              filled: true,
              fillColor: Colors.white.withValues(alpha: 0.08),
              counterText: '',
              contentPadding: const EdgeInsets.symmetric(vertical: 14),
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: AppTheme.emeraldAccent)),
              focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: AppTheme.emeraldAccent, width: 2)),
            ),
          ),
        ],

        const SizedBox(height: 16),
        if (_statusMessage.isNotEmpty) ...[
          Container(
            padding: const EdgeInsets.all(10),
            width: double.infinity,
            decoration: BoxDecoration(
              color: AppTheme.emeraldAccent.withValues(alpha: 0.15),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: AppTheme.emeraldAccent),
            ),
            child: Text(_statusMessage, style: const TextStyle(color: AppTheme.emeraldAccent, fontSize: 12), textAlign: TextAlign.center),
          ),
          const SizedBox(height: 14),
        ],

        SizedBox(
          width: double.infinity,
          child: _isLoading
              ? const Center(child: CircularProgressIndicator(color: AppTheme.emeraldAccent))
              : GradientButton(
                  text: _showQRCode ? 'Next: Enter 6-Digit Code →' : 'Verify 2FA & Access Portal',
                  gradient: AppTheme.cyanPurpleGradient,
                  onPressed: () {
                    if (_showQRCode) {
                      setState(() => _showQRCode = false);
                    } else {
                      _verify2FACode();
                    }
                  },
                ),
        ),
      ],
    );
  }

  Widget _buildGlassTextField(TextEditingController controller, String hint, IconData icon, {bool isPassword = false}) {
    return TextField(
      controller: controller,
      obscureText: isPassword,
      style: const TextStyle(color: Colors.white, fontSize: 13),
      decoration: InputDecoration(
        prefixIcon: Icon(icon, color: Colors.white54, size: 18),
        hintText: hint,
        hintStyle: const TextStyle(color: Colors.white38, fontSize: 12),
        filled: true,
        fillColor: Colors.white.withValues(alpha: 0.05),
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide(color: Colors.white.withValues(alpha: 0.15))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: const BorderSide(color: AppTheme.telegramBlue)),
      ),
    );
  }
}
