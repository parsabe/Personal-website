import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_profile.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import 'chat_conversation_screen.dart';

class ChatsListScreen extends StatefulWidget {
  final bool isLoggedIn;
  final VoidCallback onRequireLogin;
  final UserProfile? userProfile;

  const ChatsListScreen({
    super.key,
    required this.isLoggedIn,
    required this.onRequireLogin,
    this.userProfile,
  });

  @override
  State<ChatsListScreen> createState() => _ChatsListScreenState();
}

class _ChatsListScreenState extends State<ChatsListScreen> {
  final List<Map<String, dynamic>> _chats = [
    {
      'id': 'c1',
      'name': 'Saved Messages 📌',
      'lastMsg': 'Personal notes, research paper links, and code snippets...',
      'time': 'Just now',
      'unread': 0,
      'isPinned': true,
    },
  ];

  @override
  void initState() {
    super.initState();
    _loadUserConversations();
  }

  @override
  void didUpdateWidget(ChatsListScreen oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.userProfile?.email != widget.userProfile?.email) {
      _loadUserConversations();
    }
  }

  Future<void> _loadUserConversations() async {
    final myEmail = widget.userProfile?.email.trim().toLowerCase() ?? 'guest';
    final conversationsKey = 'user_conversations_$myEmail';

    try {
      final prefs = await SharedPreferences.getInstance();
      final List<String>? rawList = prefs.getStringList(conversationsKey);

      final List<Map<String, dynamic>> loadedChats = [
        {
          'id': 'c1',
          'name': 'Saved Messages 📌',
          'lastMsg': 'Personal notes, research paper links, and code snippets...',
          'time': 'Just now',
          'unread': 0,
          'isPinned': true,
        },
      ];

      if (rawList != null && rawList.isNotEmpty) {
        for (final item in rawList) {
          try {
            final m = jsonDecode(item);
            if (m is Map<String, dynamic> && m['name'] != null) {
              if (!loadedChats.any((c) => c['name'] == m['name'])) {
                loadedChats.add(m);
              }
            }
          } catch (_) {}
        }
      }

      final allKeys = prefs.getKeys();
      final chatPrefix = 'chat_${myEmail}_';
      for (final key in allKeys) {
        if (key.startsWith(chatPrefix)) {
          final contact = key.substring(chatPrefix.length);
          if (contact.isNotEmpty && !loadedChats.any((c) => c['name'].toString().toLowerCase() == contact.toLowerCase())) {
            final msgList = prefs.getStringList(key) ?? [];
            String lastMsgText = 'New Conversation';
            if (msgList.isNotEmpty) {
              try {
                final lastObj = jsonDecode(msgList.last);
                lastMsgText = lastObj['message'] ?? 'New Message';
              } catch (_) {}
            }
            loadedChats.insert(1, {
              'id': 'c_${DateTime.now().millisecondsSinceEpoch}',
              'name': contact.contains('@') ? contact.split('@')[0] : contact,
              'email': contact,
              'lastMsg': lastMsgText,
              'time': 'Just now',
              'unread': 1,
              'isPinned': false,
            });
          }
        }
      }

      setState(() {
        _chats.clear();
        _chats.addAll(loadedChats);
      });

      final hasUnread = loadedChats.any((c) => (c['unread'] ?? 0) > 0 && c['id'] != 'c1');
      if (hasUnread && mounted) {
        SystemSound.play(SystemSoundType.click);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('🔔 You have new unread messages in Parsa Journals Chat!'),
            backgroundColor: AppTheme.telegramBlue,
            duration: Duration(seconds: 3),
          ),
        );
      }
    } catch (_) {}
  }

  void _openNewChatSearchModal() async {
    if (!widget.isLoggedIn) {
      widget.onRequireLogin();
      return;
    }

    final myEmail = widget.userProfile?.email.trim().toLowerCase() ?? '';
    final myUsername = widget.userProfile?.username.trim().toLowerCase() ?? '';

    // Fetch database users from ApiService & SharedPreferences registered_users_db
    List<Map<String, dynamic>> fetchedDbUsers = [];
    try {
      fetchedDbUsers = await ApiService().fetchNetworkUsers(myEmail);
    } catch (_) {}

    try {
      final prefs = await SharedPreferences.getInstance();
      final List<String>? rawLocalList = prefs.getStringList('registered_users_db');
      if (rawLocalList != null && rawLocalList.isNotEmpty) {
        for (final item in rawLocalList) {
          try {
            final m = jsonDecode(item);
            if (m is Map<String, dynamic> && m['email'] != null) {
              if (!fetchedDbUsers.any((u) => u['email'] == m['email'])) {
                fetchedDbUsers.add(m);
              }
            }
          } catch (_) {}
        }
      }
    } catch (_) {}

    // Exclude logged in self from database list
    final List<Map<String, Object>> networkUsers = fetchedDbUsers.where((u) {
      final email = (u['email']?.toString() ?? '').trim().toLowerCase();
      final username = (u['username']?.toString() ?? '').trim().toLowerCase();
      return email.isNotEmpty && email != myEmail && username != myUsername;
    }).map<Map<String, Object>>((u) => {
      'name': u['name'] ?? u['first_name'] ?? 'User',
      'email': u['email'] ?? '',
      'username': u['username'] ?? '',
      'role': u['role'] ?? 'Parsa Journals Member',
      'isVerified': u['is_verified'] == true || u['email'] == 'parsabe99@gmail.com',
    }).toList();

    // If database user list is empty, include owner Parsa Besharat if current user is not owner
    if (networkUsers.isEmpty && myEmail != 'parsabe99@gmail.com') {
      networkUsers.add({
        'name': 'Parsa Besharat',
        'email': 'parsabe99@gmail.com',
        'username': 'parsabe',
        'role': 'Owner & Platform Founder',
        'isVerified': true,
      });
    }

    String modalQuery = '';
    final searchController = TextEditingController();

    if (!mounted) return;

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            final filteredUsers = networkUsers.where((u) {
              final q = modalQuery.toLowerCase();
              final name = (u['name'] as String).toLowerCase();
              final email = (u['email'] as String).toLowerCase();
              final username = (u['username'] as String).toLowerCase();
              return name.contains(q) || email.contains(q) || username.contains(q);
            }).toList();

            return Padding(
              padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
              child: GlassCard(
                margin: const EdgeInsets.all(16),
                child: SizedBox(
                  height: 420,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Start New Chat', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                          IconButton(
                            icon: const Icon(Icons.close, color: Colors.white54),
                            onPressed: () => Navigator.pop(context),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      TextField(
                        controller: searchController,
                        style: const TextStyle(color: Colors.white),
                        decoration: InputDecoration(
                          prefixIcon: const Icon(Icons.search, color: AppTheme.telegramBlue),
                          hintText: 'Search user by Name, Email, or @Username...',
                          hintStyle: const TextStyle(color: Colors.white38, fontSize: 13),
                          filled: true,
                          fillColor: Colors.white.withValues(alpha: 0.05),
                          contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                          border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
                        ),
                        onChanged: (val) {
                          setModalState(() {
                            modalQuery = val.trim();
                          });
                        },
                      ),
                      const SizedBox(height: 16),
                      const Text('Network Users Directory:', style: TextStyle(color: Colors.white60, fontSize: 12, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 10),
                      Expanded(
                        child: filteredUsers.isEmpty
                            ? Center(
                                child: Text('No users found matching "$modalQuery".', style: const TextStyle(color: Colors.white54, fontSize: 13)),
                              )
                            : ListView.builder(
                                itemCount: filteredUsers.length,
                                itemBuilder: (context, index) {
                                  final u = filteredUsers[index];
                                  final uName = u['name'] as String;
                                  final uEmail = u['email'] as String;
                                  final uUsername = u['username'] as String;
                                  final isVerified = u['isVerified'] == true;

                                  return Container(
                                    margin: const EdgeInsets.only(bottom: 8),
                                    child: GlassCard(
                                      onTap: () {
                                        Navigator.pop(context);
                                        setState(() {
                                          if (!_chats.any((c) => c['name'] == uName)) {
                                            _chats.insert(0, {
                                              'name': uName,
                                              'lastMsg': 'Connected with $uName via Parsa Journals Network',
                                              'time': 'Just now',
                                              'unread': 0,
                                              'isPinned': true,
                                            });
                                          }
                                        });
                                        Navigator.push(
                                          context,
                                          MaterialPageRoute(
                                            builder: (_) => ChatConversationScreen(
                                              contactName: uName,
                                              contactAvatar: '',
                                              currentUser: widget.userProfile,
                                            ),
                                          ),
                                        );
                                      },
                                      child: ListTile(
                                        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                                        leading: CircleAvatar(
                                          radius: 20,
                                          backgroundColor: isVerified ? AppTheme.telegramBlue : AppTheme.pinkPrimary,
                                          child: Text(uName[0], style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                                        ),
                                        title: Row(
                                          children: [
                                            Text(uName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                                            if (isVerified) ...[
                                              const SizedBox(width: 6),
                                              const Icon(Icons.check_circle, color: AppTheme.emeraldAccent, size: 16),
                                            ],
                                          ],
                                        ),
                                        subtitle: Text('$uEmail • @$uUsername', style: const TextStyle(color: Colors.white60, fontSize: 11)),
                                        trailing: const Icon(Icons.chat_bubble_outline, color: AppTheme.telegramBlue, size: 18),
                                      ),
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.transparent,
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
        padding: const EdgeInsets.fromLTRB(16, 12, 16, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Telegram Chat Network', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                IconButton(
                  icon: const Icon(Icons.person_add, color: AppTheme.telegramBlue),
                  onPressed: _openNewChatSearchModal,
                ),
              ],
            ),
            const SizedBox(height: 12),

            ..._chats.map((chat) => Padding(
              padding: const EdgeInsets.only(bottom: 10.0),
              child: GlassCard(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => ChatConversationScreen(
                        contactName: chat['name'],
                        contactAvatar: '',
                        currentUser: widget.userProfile,
                      ),
                    ),
                  ).then((_) => _loadUserConversations());
                },
                child: ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: CircleAvatar(
                    radius: 22,
                    backgroundColor: chat['isPinned'] == true ? AppTheme.emeraldAccent : AppTheme.telegramBlue,
                    child: Text((chat['name'] as String)[0], style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                  ),
                  title: Row(
                    children: [
                      Text(chat['name'] as String, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                      if (chat['isPinned'] == true) ...[
                        const SizedBox(width: 4),
                        const Icon(Icons.push_pin, size: 14, color: AppTheme.cyanAccent),
                      ],
                    ],
                  ),
                  subtitle: Text(chat['lastMsg'] as String, style: const TextStyle(color: Colors.white60, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(chat['time'] as String, style: const TextStyle(color: Colors.white38, fontSize: 11)),
                      if ((chat['unread'] ?? 0) > 0) ...[
                        const SizedBox(height: 4),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: AppTheme.telegramBlue,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Text(
                            '${chat['unread']}',
                            style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
              ),
            )),
          ],
        ),
      ),

      floatingActionButton: Padding(
        padding: const EdgeInsets.only(bottom: 80),
        child: FloatingActionButton.extended(
          backgroundColor: AppTheme.telegramBlue,
          icon: const Icon(Icons.chat_bubble, color: Colors.white),
          label: const Text('New Chat', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
          onPressed: _openNewChatSearchModal,
        ),
      ),
    );
  }
}
