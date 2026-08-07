import 'dart:async';
import 'dart:convert';
import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/chat_message.dart';
import '../models/user_profile.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';

class ChatConversationScreen extends StatefulWidget {
  final String contactName;
  final String contactAvatar;
  final UserProfile? currentUser;

  const ChatConversationScreen({
    super.key,
    required this.contactName,
    required this.contactAvatar,
    this.currentUser,
  });

  @override
  State<ChatConversationScreen> createState() => _ChatConversationScreenState();
}

class _ChatConversationScreenState extends State<ChatConversationScreen> {
  final List<ChatMessage> _messages = [];
  final _textController = TextEditingController();
  Color _wallpaperColor = const Color(0xFF0E1621);

  // Pinned Message State
  ChatMessage? _pinnedMessage;

  // Recording State
  bool _isRecordingVoice = false;

  // Call State
  bool _isCallActive = false;
  String _callType = 'Voice Call';
  bool _isMuted = false;

  // Chat Privacy & Security Controls
  String _autoDeleteTimer = 'Off'; // Options: Off, 15 Hours, 1 Day
  bool _isUserBlocked = false;

  final Map<String, Map<String, dynamic>> _iosEmojiCategories = {
    'smileys': {
      'icon': '😀',
      'title': 'Smileys & People',
      'emojis': [
        '😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🥰', '😍', '🤩', '😘', '😗', '😚', '😋',
        '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌',
        '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '🥸', '😎', '🤓',
        '🧐', '😕', '😟', '🙁', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞',
        '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾', '🤖',
        '👋', '🤚', '🖐️', '✋', '🖖', '👌', '🤌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👍',
        '👎', '✊', '👊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💅', '🤳', '💪', '🦾', '🦵', '🦿', '🦶', '👂'
      ],
    },
    'animals': {
      'icon': '🐶',
      'title': 'Animals & Nature',
      'emojis': [
        '🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐻‍❄️', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🙈', '🙉', '🙊', '🐒',
        '🐔', '🐧', '🐦', '🐤', '🐣', '🐥', '🦆', '🦅', '🦉', '🦇', '🐺', '🐗', '🐴', '🦄', '🐝', '🪲', '🐛', '🦋', '🐌', '🐞',
        '🐜', '🪰', '🪳', '<ctrl42>', '🦗', '🕷️', '🕸️', '🦂', '🐍', '🦎', '🦖', '🦕', '🐢', '🐅', '🐆', '🦓', '🦍', '🦧', '🦣', '🐘',
        '🦛', '🦏', '🐪', '🐫', '🦒', '🦘', '🦬', '🐃', '🐂', '🐄', '🐎', '🐖', '🐏', '🐑', '🦙', '🐐', 'deer', '🐕', '🐩', '🦮',
        '🐈', '🐓', '🦃', '🦚', '🦜', 'swan', '🦩', '🕊️', '🐇', '🦝', '🦨', '🦡', '🦫', 'otter', '🦥', '🐁', '🐀', '🐿️', '🦔', '🐾'
      ],
    },
    'food': {
      'icon': '🍔',
      'title': 'Food & Drink',
      'emojis': [
        '🍏', '🍎', '🍐', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🫐', '🍈', '🍒', '🍑', '🥭', '🍍', '🥥', '🥝', '🍅', '🍆', '🥑',
        '🥦', '🥬', '🥒', '🌶️', '🫑', '🌽', '🥕', '🫒', '🧄', '🧅', '🥔', '🍠', '🥐', '🥯', '🍞', '🥖', '🥨', '🧀', '🥚', '🍳',
        '🥞', '🧇', '🥓', '🥩', '🍗', '🍖', '🦴', '🌭', '🍔', '🍟', '🍕', '🥪', '🥙', '🧆', '🌮', '🌯', '🥗', '🥘', '🥫', '🍝',
        '🍜', '🍲', '🍛', '🍣', '🍱', '🥟', '🦪', '🍤', '🍙', '🍚', '🍘', '🍥', '🥠', '🥮', '🍢', '🍡', '🍧', '🍨', '🍦', '🥧',
        '🍰', '🎂', '🍮', '🍭', '🍬', '🍫', '🍿', '🍩', '🍪', '🌰', '🥜', '🥛', '🍼', '☕️', '🍵', '🧃', '🥤', '🧋', '🍺', '🍻'
      ],
    },
    'activity': {
      'icon': '⚽️',
      'title': 'Activity & Sports',
      'emojis': [
        '⚽️', '🏀', '🏈', '⚾️', '🥎', '🎾', '🏐', '🏉', '🥏', '🎱', '🪀', '🏓', '🏸', '🏒', '🏑', '🥍', '🏏', '🪃', '🥅', '⛳️',
        '🏹', '🎣', '🤿', '🥊', '🥋', '🎽', '🛹', '🛼', '🛷', '⛸️', '🥌', '🎿', '⛷️', '🏂', '🪂', '🏋️‍♀️', '🤼‍♂️', '🤸‍♀️', '⛹️‍♂️', '🤺',
        '🤾‍♂️', '🏌️‍♀️', '🏇', '🧘‍♀️', '🏄‍♂️', '🏊‍♀️', '🤽‍♂️', '🚣‍♀️', '🧗‍♂️', '🚵‍♀️', '🚴‍♂️', '🏆', '🥇', '🥈', '🥉', '🏅', '🎖️', '🏵️', '🎗️', '🎫',
        '🎟️', '🎪', '🤹‍♂️', '🎭', '🎨', '🎬', '🎤', '🎧', '🎼', '🎹', '🥁', '🎷', '🎺', '🎸', '🪕', '🎻', '🎲', '♟️', '🎯', ' bowling', '🎮'
      ],
    },
    'travel': {
      'icon': '🚗',
      'title': 'Travel & Places',
      'emojis': [
        '🚗', '🚕', '🚙', '🚌', '🚎', '🏎️', '🚓', '🚑', '🚒', '🚐', '🛻', '🚚', '🚛', '🚜', '🦯', '🦽', '🦼', '🛴', '🚲', '🛵',
        '🏍️', '🛺', '🚨', '🚔', '🚍', '🚘', '🚖', '🚡', '🚠', '🚟', '🚃', '🚋', '🚞', '🚝', '<ctrl42>', '🚅', '🚈', '🚂', '🚆', '🚇',
        '🚊', '🚉', '✈️', '🛫', '🛬', '🛩️', '💺', '🛰️', '🚀', '🛸', '🚁', '🛶', '⛵️', '🚤', '🛥️', '🛳️', '⚙️', '⛴️', '🚢', '⚓️',
        '⛽️', '🚧', '🚦', '🚥', '🚏', '🗺️', '🗿', '🏙️', '🌃', '🌆', '🌇', '♨️', '🏰', '🏯', '🏟️', '🗽', '🏠', '🏡', '🏢', '🏣', '🏥'
      ],
    },
    'objects': {
      'icon': '💡',
      'title': 'Objects & Tech',
      'emojis': [
        '⌚️', '📱', '📲', '💻', '⌨️', '🖥️', '🖨️', '🖱️', '🖲️', '🕹️', '💽', '💾', '💿', '📀', '📼', '📷', '📸', '📹', '🎥', '📽️',
        '🎞️', '📞', '☎️', '📟', '📠', '📺', '📻', '🎙️', '🎚️', '🎛️', '⏱️', '⏲️', '⏰', '🕰️', '⌛️', '⏳', '📡', '🔋', '🔌', '💡',
        '🔦', '🕯️', '🪔', '🧯', '🛢️', '💸', '💵', '💴', '💶', '💷', '🪙', '💰', '💳', '💎', '⚖️', '🧰', '🔧', '🔨', '⚒️', '🛠️',
        '⛏️', '🪛', '🔩', '⚙️', '🧱', '⛓️', '🧲', '🔫', '💣', '🔪', '🗡️', '⚔️', '🛡️', '🚬', '⚰️', '🪦', '⚱️', '🏺', '🔮', '📿'
      ],
    },
    'symbols': {
      'icon': '💎',
      'title': 'Symbols & Flags',
      'emojis': [
        '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '☮️',
        '✝️', '☪️', '🕉️', '☸️', '✡️', '<ctrl42>', '🕎', '☯️', '☦️', '🛐', '⛎', '♈️', '♉️', '♊️', '♋️', '<ctrl42>', '♍️', '♎️', '♏️', '♐️',
        '♑️', '♒️', '♓️', '🆔', '⚛️', '🉑', '☢️', '☣️', '📴', '📳', '🈶', '🈚️', '🈸', '🈺', '<ctrl42>', '✴️', '🆚', '💮', '🉐', '㊙️',
        '㊗️', '🈴', '🈵', '🈹', '🈲', '🅰️', '🅱️', '🆎', '🆑', '🅾️', '🆘', '❌', '⭕️', '🛑', '⛔️', '📛', '🚫', '💯', '💢', '♨️',
        '🚷', '🚯', '🚳', '🚱', '🔞', '📵', '🚭', '❗️', '❕', '❓', '❔', '‼️', '⁉️', '🔆', '🔅', '⚠️', '🚸', '🔱', '⚜️', '🔰', '♻️'
      ],
    },
  };

  final List<Map<String, String>> _gifs = [
    {'title': 'Matrix Code Rain GIF', 'url': 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExdWp3dWR5ZzRyeG5mbnJjMWg2aGpxcm9nY3M1cjBsZXJpdm8yYnRxZyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3oKIPnAiaMCws8nOsE/giphy.gif'},
    {'title': 'Neural Network GIF', 'url': 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExOHk5ZHdndzBzMWwxdTBicHFxbDRndXZiaXh5MXV1dW9sbmlwbXRleCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/L0qTl8S64PPZvEDlZz/giphy.gif'},
    {'title': 'Quantum Compute GIF', 'url': 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMjMwbG92ZmFzOHBsYXdqZXdwNGp4dmVtdHhhM3pxNDVycXBkdzRqNSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3o7TKSjRrfIPjeiVyM/giphy.gif'},
  ];

  @override
  void initState() {
    super.initState();
    _loadPersistedMessages();
  }

  Future<void> _updateConversationIndex(String ownerEmail, String contactName, String contactEmail, String lastMsg, {required bool isUnread}) async {
    try {
      final key = 'user_conversations_${ownerEmail.toLowerCase()}';
      final prefs = await SharedPreferences.getInstance();
      final List<String> list = prefs.getStringList(key) ?? [];
      
      List<Map<String, dynamic>> convs = [];
      for (final item in list) {
        try {
          final m = jsonDecode(item);
          if (m is Map<String, dynamic>) convs.add(m);
        } catch (_) {}
      }

      final existingIndex = convs.indexWhere((c) => c['name'].toString().toLowerCase() == contactName.toLowerCase() || c['email'].toString().toLowerCase() == contactEmail.toLowerCase());

      final entry = {
        'id': 'c_${DateTime.now().millisecondsSinceEpoch}',
        'name': contactName,
        'email': contactEmail,
        'lastMsg': lastMsg,
        'time': 'Just now',
        'unread': isUnread ? 1 : 0,
        'isPinned': true,
      };

      if (existingIndex >= 0) {
        convs[existingIndex] = entry;
      } else {
        convs.insert(0, entry);
      }

      await prefs.setStringList(key, convs.map((c) => jsonEncode(c)).toList());
    } catch (_) {}
  }

  Future<void> _loadPersistedMessages() async {
    final myEmail = widget.currentUser?.email ?? 'guest';
    final chatKey = 'chat_${myEmail.toLowerCase()}_${widget.contactName.toLowerCase()}';
    try {
      final prefs = await SharedPreferences.getInstance();
      final List<String>? rawList = prefs.getStringList(chatKey);
      if (rawList != null && rawList.isNotEmpty) {
        final List<ChatMessage> loaded = rawList.map((str) => ChatMessage.fromJson(jsonDecode(str))).toList();
        setState(() {
          _messages.clear();
          _messages.addAll(loaded);
        });
      }
      // Clear unread flag for this conversation
      _updateConversationIndex(myEmail, widget.contactName, widget.contactName, _messages.isNotEmpty ? _messages.last.message : 'Chat active', isUnread: false);
    } catch (_) {}
  }

  Future<void> _saveMessage(ChatMessage newMsg) async {
    final myEmail = widget.currentUser?.email ?? 'guest';
    final myName = widget.currentUser?.fullName ?? 'Me';
    final chatKeyMySide = 'chat_${myEmail.toLowerCase()}_${widget.contactName.toLowerCase()}';
    final chatKeyOtherSide = 'chat_${widget.contactName.toLowerCase()}_${myEmail.toLowerCase()}';

    try {
      SystemSound.play(SystemSoundType.click);

      final prefs = await SharedPreferences.getInstance();

      final List<String> myExisting = prefs.getStringList(chatKeyMySide) ?? [];
      myExisting.add(jsonEncode(newMsg.toJson()));
      await prefs.setStringList(chatKeyMySide, myExisting);

      final recipientMsg = ChatMessage(
        id: newMsg.id,
        sender: myName,
        avatarUrl: newMsg.avatarUrl,
        message: newMsg.message,
        timestamp: newMsg.timestamp,
        isUser: false,
      );
      final List<String> otherExisting = prefs.getStringList(chatKeyOtherSide) ?? [];
      otherExisting.add(jsonEncode(recipientMsg.toJson()));
      await prefs.setStringList(chatKeyOtherSide, otherExisting);

      await _updateConversationIndex(myEmail, widget.contactName, widget.contactName, newMsg.message, isUnread: false);
      await _updateConversationIndex(widget.contactName, myName, myEmail, newMsg.message, isUnread: true);

      await ApiService().sendChatMessage(newMsg.message, recipientEmail: widget.contactName);
    } catch (_) {}
  }

  void _sendMessage({String? customText}) {
    if (_isUserBlocked) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Cannot send message: User is blocked.')),
      );
      return;
    }

    final text = customText ?? _textController.text.trim();
    if (text.isNotEmpty) {
      final msg = ChatMessage(
        id: DateTime.now().millisecondsSinceEpoch.toString(),
        sender: widget.currentUser?.fullName ?? 'You',
        avatarUrl: '',
        message: text,
        timestamp: 'Just now',
        isUser: true,
      );

      setState(() {
        _messages.add(msg);
        if (customText == null) _textController.clear();
      });

      _saveMessage(msg);
    }
  }

  void _openAutoDeleteSettings() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: AppTheme.surface,
          title: const Text('Auto-Delete Messages Timer', style: TextStyle(color: Colors.white, fontSize: 16)),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Automatically erase chat messages after chosen duration:', style: TextStyle(color: Colors.white70, fontSize: 12)),
              const SizedBox(height: 12),
              _buildAutoDeleteOption('Off (Disabled)'),
              _buildAutoDeleteOption('15 Hours'),
              _buildAutoDeleteOption('1 Day (24 Hours)'),
            ],
          ),
        );
      },
    );
  }

  Widget _buildAutoDeleteOption(String option) {
    final isSelected = _autoDeleteTimer == option;
    return ListTile(
      leading: Icon(Icons.timer, color: isSelected ? AppTheme.telegramBlue : Colors.white54),
      title: Text(option, style: TextStyle(color: isSelected ? AppTheme.telegramBlue : Colors.white, fontWeight: isSelected ? FontWeight.bold : FontWeight.normal)),
      onTap: () {
        setState(() {
          _autoDeleteTimer = option;
        });
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Auto-delete timer set to $option')),
        );
      },
    );
  }

  void _eraseChatHistory() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: AppTheme.surface,
          title: const Text('Erase Entire Chat History?', style: TextStyle(color: Colors.white, fontSize: 16)),
          content: const Text('Are you sure you want to permanently erase all messages in this conversation?', style: TextStyle(color: Colors.white70, fontSize: 13)),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel', style: TextStyle(color: Colors.white54)),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(backgroundColor: Colors.redAccent),
              onPressed: () {
                setState(() {
                  _messages.clear();
                });
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Chat history permanently erased.')),
                );
              },
              child: const Text('Erase Chat', style: TextStyle(color: Colors.white)),
            ),
          ],
        );
      },
    );
  }

  void _blockAndReportUser() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: AppTheme.surface,
          title: Text('Block & Report ${widget.contactName}?', style: const TextStyle(color: Colors.white, fontSize: 16)),
          content: const Text('Blocked users will be added to your Blocked Users privacy list and will not be able to message or call you.', style: TextStyle(color: Colors.white70, fontSize: 13)),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel', style: TextStyle(color: Colors.white54)),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(backgroundColor: Colors.redAccent),
              onPressed: () {
                setState(() {
                  _isUserBlocked = true;
                });
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('${widget.contactName} blocked and reported. Added to Blocked Users list.')),
                );
              },
              child: const Text('Block & Report', style: TextStyle(color: Colors.white)),
            ),
          ],
        );
      },
    );
  }

  void _showMessageContextMenu(ChatMessage msg) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.white.withValues(alpha: 0.08),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.chat_bubble_outline, color: AppTheme.telegramBlue, size: 18),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        msg.caption != null && msg.caption!.isNotEmpty ? msg.caption! : msg.message,
                        style: const TextStyle(color: Colors.white70, fontSize: 12),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 8),

              ListTile(
                leading: const Icon(Icons.push_pin, color: AppTheme.cyanAccent),
                title: const Text('Pin Message', style: TextStyle(color: Colors.white, fontSize: 14)),
                onTap: () {
                  Navigator.pop(context);
                  setState(() {
                    _pinnedMessage = msg;
                  });
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Message pinned to chat header 📌')),
                  );
                },
              ),

              ListTile(
                leading: const Icon(Icons.copy, color: AppTheme.telegramBlue),
                title: const Text('Copy Text', style: TextStyle(color: Colors.white, fontSize: 14)),
                onTap: () {
                  Navigator.pop(context);
                  final textToCopy = msg.caption != null && msg.caption!.isNotEmpty ? msg.caption! : msg.message;
                  Clipboard.setData(ClipboardData(text: textToCopy));
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Text copied to clipboard 📋')),
                  );
                },
              ),

              ListTile(
                leading: const Icon(Icons.edit_note, color: Colors.amberAccent),
                title: const Text('Copy & Append to Input', style: TextStyle(color: Colors.white, fontSize: 14)),
                onTap: () {
                  Navigator.pop(context);
                  final textToAppend = msg.caption != null && msg.caption!.isNotEmpty ? msg.caption! : msg.message;
                  setState(() {
                    if (_textController.text.isNotEmpty) {
                      _textController.text += ' $textToAppend';
                    } else {
                      _textController.text = textToAppend;
                    }
                  });
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Appended text to chat input field ✏️')),
                  );
                },
              ),

              ListTile(
                leading: const Icon(Icons.forward, color: Colors.purpleAccent),
                title: const Text('Forward Message', style: TextStyle(color: Colors.white, fontSize: 14)),
                onTap: () {
                  Navigator.pop(context);
                  _openForwardUserModal(msg);
                },
              ),

              ListTile(
                leading: const Icon(Icons.delete_forever, color: Colors.redAccent),
                title: const Text('Delete Message', style: TextStyle(color: Colors.redAccent, fontSize: 14, fontWeight: FontWeight.bold)),
                onTap: () {
                  Navigator.pop(context);
                  _deleteMessage(msg);
                },
              ),
            ],
          ),
        );
      },
    );
  }

  void _deleteMessage(ChatMessage msg) async {
    setState(() {
      _messages.removeWhere((m) => m.id == msg.id);
      if (_pinnedMessage?.id == msg.id) {
        _pinnedMessage = null;
      }
    });

    try {
      final prefs = await SharedPreferences.getInstance();
      final keys = prefs.getKeys();

      // Delete message from both sender and recipient chat storage keys
      for (final key in keys) {
        if (key.startsWith('chat_')) {
          final List<String> existing = prefs.getStringList(key) ?? [];
          final originalLength = existing.length;
          existing.removeWhere((str) {
            try {
              final m = jsonDecode(str);
              if (m['id'] == msg.id) return true;
              if (m['message'] == msg.message && m['timestamp'] == msg.timestamp) return true;
              return false;
            } catch (_) {
              return false;
            }
          });
          if (existing.length != originalLength) {
            await prefs.setStringList(key, existing);
          }
        }
      }

      // Update conversation indices for both users if deleted message was the lastMsg
      for (final key in keys) {
        if (key.startsWith('user_conversations_')) {
          final List<String> list = prefs.getStringList(key) ?? [];
          bool modified = false;
          final List<Map<String, dynamic>> convs = [];
          for (final item in list) {
            try {
              final m = jsonDecode(item);
              if (m is Map<String, dynamic>) {
                if (m['lastMsg'] == msg.message || (msg.caption != null && m['lastMsg'] == msg.caption)) {
                  m['lastMsg'] = 'Message deleted';
                  modified = true;
                }
                convs.add(m);
              }
            } catch (_) {}
          }
          if (modified) {
            await prefs.setStringList(key, convs.map((c) => jsonEncode(c)).toList());
          }
        }
      }
    } catch (_) {}

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Message deleted for both users 🗑️')),
      );
    }
  }

  void _openForwardUserModal(ChatMessage msg) async {
    final myEmail = widget.currentUser?.email.trim().toLowerCase() ?? '';
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

    final List<Map<String, dynamic>> contacts = fetchedDbUsers.where((u) {
      final email = (u['email']?.toString() ?? '').trim().toLowerCase();
      return email.isNotEmpty && email != myEmail;
    }).toList();

    if (!mounted) return;
    final messenger = ScaffoldMessenger.of(context);

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Forward Message To...', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              const SizedBox(height: 12),
              SizedBox(
                height: 250,
                child: ListView.builder(
                  itemCount: contacts.length,
                  itemBuilder: (context, index) {
                    final contact = contacts[index];
                    final name = (contact['name'] ?? contact['first_name'] ?? 'User').toString();
                    final email = (contact['email'] ?? '').toString();

                    return ListTile(
                      leading: CircleAvatar(
                        backgroundColor: AppTheme.telegramBlue,
                        child: Text(name.isNotEmpty ? name[0] : 'U', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                      ),
                      title: Text(name, style: const TextStyle(color: Colors.white, fontSize: 14)),
                      subtitle: Text(email, style: const TextStyle(color: Colors.white54, fontSize: 11)),
                      trailing: const Icon(Icons.send_rounded, color: AppTheme.cyanAccent),
                      onTap: () async {
                        Navigator.pop(context);
                        final forwardMsg = ChatMessage(
                          id: DateTime.now().millisecondsSinceEpoch.toString(),
                          sender: widget.currentUser?.fullName ?? 'You',
                          avatarUrl: '',
                          message: '⏩ Forwarded: ${msg.caption ?? msg.message}',
                          timestamp: 'Just now',
                          isUser: true,
                          attachmentType: msg.attachmentType,
                          attachmentUrl: msg.attachmentUrl,
                          fileName: msg.fileName,
                          fileSize: msg.fileSize,
                          caption: msg.caption,
                          duration: msg.duration,
                        );

                        final chatKeyMySide = 'chat_${myEmail.toLowerCase()}_${name.toLowerCase()}';
                        final chatKeyOtherSide = 'chat_${name.toLowerCase()}_${myEmail.toLowerCase()}';

                        try {
                          final prefs = await SharedPreferences.getInstance();
                          final List<String> myExisting = prefs.getStringList(chatKeyMySide) ?? [];
                          myExisting.add(jsonEncode(forwardMsg.toJson()));
                          await prefs.setStringList(chatKeyMySide, myExisting);

                          final List<String> otherExisting = prefs.getStringList(chatKeyOtherSide) ?? [];
                          otherExisting.add(jsonEncode(forwardMsg.toJson()));
                          await prefs.setStringList(chatKeyOtherSide, otherExisting);

                          await ApiService().sendChatMessage(forwardMsg.message, recipientEmail: email);
                        } catch (_) {}

                        messenger.showSnackBar(
                          SnackBar(content: Text('Message forwarded to $name! ⏩')),
                        );
                      },
                    );
                  },
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  void _pickFileAndOpenCaptionDialog(String attachmentType) async {
    FileType pickerFileType = FileType.any;
    if (attachmentType == 'photo') {
      pickerFileType = FileType.image;
    } else if (attachmentType == 'video') {
      pickerFileType = FileType.video;
    } else if (attachmentType == 'voice') {
      pickerFileType = FileType.audio;
    }

    try {
      final result = await FilePicker.platform.pickFiles(
        type: pickerFileType,
        allowMultiple: false,
      );

      if (result != null && result.files.isNotEmpty) {
        final picked = result.files.first;
        final fileName = picked.name;
        final fileSizeInBytes = picked.size;
        final fileSizeMb = (fileSizeInBytes / (1024 * 1024)).toStringAsFixed(1);
        final fileSizeStr = fileSizeInBytes > 1024 * 1024 ? '$fileSizeMb MB' : '${(fileSizeInBytes / 1024).toStringAsFixed(0)} KB';
        final filePath = picked.path ?? '';

        if (mounted) {
          _openAttachmentDialog(
            attachmentType,
            fileName: fileName,
            fileSize: fileSizeStr,
            filePath: filePath,
          );
        }
      }
    } catch (_) {
      if (mounted) {
        _openAttachmentDialog(attachmentType);
      }
    }
  }

  void _openAttachmentDialog(
    String attachmentType, {
    String? fileName,
    String? fileSize,
    String? filePath,
  }) {
    String currentFileName = fileName ?? (attachmentType == 'photo' ? 'Photo_Media_01.png' : (attachmentType == 'video' ? 'Video_Note_01.mp4' : (attachmentType == 'voice' ? 'Voice_Record_01.mp3' : 'Document_File.pdf')));
    String currentFileSize = fileSize ?? '3.5 MB';
    String currentFilePath = filePath ?? '';

    final fileNameController = TextEditingController(text: currentFileName);
    final captionController = TextEditingController();

    String label = attachmentType.toUpperCase();
    IconData icon = Icons.image;
    Color color = AppTheme.telegramBlue;

    if (attachmentType == 'photo') {
      label = 'Photo Attachment 📷';
      icon = Icons.photo_library;
      color = AppTheme.cyanAccent;
    } else if (attachmentType == 'video') {
      label = 'Video Note / Message 🎥';
      icon = Icons.videocam;
      color = AppTheme.pinkPrimary;
    } else if (attachmentType == 'document') {
      label = 'File / Document 📁';
      icon = Icons.insert_drive_file;
      color = AppTheme.emeraldAccent;
    } else if (attachmentType == 'voice') {
      label = 'Voice Message 🎙️';
      icon = Icons.mic;
      color = Colors.amberAccent;
    } else {
      label = 'Location Marker 📍';
      icon = Icons.location_on;
      color = Colors.redAccent;
    }

    showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return AlertDialog(
              backgroundColor: const Color(0xFF1E1E2C),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              title: Row(
                children: [
                  Icon(icon, color: color, size: 22),
                  const SizedBox(width: 8),
                  Text(label, style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
                ],
              ),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: color.withValues(alpha: 0.12),
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: color.withValues(alpha: 0.3)),
                      ),
                      child: Column(
                        children: [
                          Icon(icon, size: 36, color: color),
                          const SizedBox(height: 6),
                          Text(
                            currentFileName,
                            style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                            textAlign: TextAlign.center,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                          const SizedBox(height: 2),
                          Text(currentFileSize, style: const TextStyle(color: Colors.white60, fontSize: 11)),
                          const SizedBox(height: 10),

                          ElevatedButton.icon(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: color.withValues(alpha: 0.25),
                              foregroundColor: color,
                              side: BorderSide(color: color),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            ),
                            icon: const Icon(Icons.folder_open, size: 18),
                            label: const Text('📂 Browse Device Storage / Windows', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold)),
                            onPressed: () async {
                              FileType pickerFileType = FileType.any;
                              if (attachmentType == 'photo') pickerFileType = FileType.image;
                              if (attachmentType == 'video') pickerFileType = FileType.video;
                              if (attachmentType == 'voice') pickerFileType = FileType.audio;

                              try {
                                final result = await FilePicker.platform.pickFiles(
                                  type: pickerFileType,
                                  allowMultiple: false,
                                );
                                if (result != null && result.files.isNotEmpty) {
                                  final picked = result.files.first;
                                  final name = picked.name;
                                  final sizeInBytes = picked.size;
                                  final sizeMb = (sizeInBytes / (1024 * 1024)).toStringAsFixed(1);
                                  final sizeStr = sizeInBytes > 1024 * 1024 ? '$sizeMb MB' : '${(sizeInBytes / 1024).toStringAsFixed(0)} KB';

                                  setModalState(() {
                                    currentFileName = name;
                                    currentFileSize = sizeStr;
                                    currentFilePath = picked.path ?? '';
                                    fileNameController.text = name;
                                  });
                                }
                              } catch (_) {}
                            },
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 12),

                    TextField(
                      controller: fileNameController,
                      style: const TextStyle(color: Colors.white, fontSize: 13),
                      decoration: InputDecoration(
                        prefixIcon: const Icon(Icons.insert_drive_file_outlined, color: Colors.white54, size: 18),
                        labelText: 'File Name / Label',
                        labelStyle: const TextStyle(color: Colors.white54, fontSize: 12),
                        filled: true,
                        fillColor: Colors.white.withValues(alpha: 0.05),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide.none),
                      ),
                    ),
                    const SizedBox(height: 10),

                    TextField(
                      controller: captionController,
                      style: const TextStyle(color: Colors.white, fontSize: 13),
                      decoration: InputDecoration(
                        prefixIcon: const Icon(Icons.short_text, color: Colors.white54, size: 18),
                        hintText: 'Add a caption (optional)...',
                        hintStyle: const TextStyle(color: Colors.white38, fontSize: 12),
                        filled: true,
                        fillColor: Colors.white.withValues(alpha: 0.05),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide.none),
                      ),
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Cancel', style: TextStyle(color: Colors.white54)),
                ),
                ElevatedButton(
                  style: ElevatedButton.styleFrom(backgroundColor: color),
                  onPressed: () {
                    Navigator.pop(context);
                    final finalFileName = fileNameController.text.trim().isNotEmpty ? fileNameController.text.trim() : currentFileName;
                    _sendAttachmentMessage(
                      type: attachmentType,
                      caption: captionController.text.trim(),
                      fileName: finalFileName,
                      fileSize: currentFileSize,
                      filePath: currentFilePath,
                    );
                  },
                  child: const Text('Send Attachment', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                ),
              ],
            );
          },
        );
      },
    );
  }

  void _sendAttachmentMessage({
    required String type,
    required String caption,
    String? fileName,
    String? fileSize,
    String? filePath,
  }) {
    String msgText = caption;
    if (msgText.isEmpty) {
      if (type == 'photo') {
        msgText = fileName ?? '📷 Photo Attachment';
      } else if (type == 'video') {
        msgText = fileName ?? '🎥 Video Note';
      } else if (type == 'voice') {
        msgText = fileName ?? '🎙️ Voice Message';
      } else if (type == 'document') {
        msgText = fileName ?? '📄 Document File';
      } else {
        msgText = '📍 Shared Location';
      }
    }

    final msg = ChatMessage(
      id: DateTime.now().millisecondsSinceEpoch.toString(),
      sender: widget.currentUser?.fullName ?? 'You',
      avatarUrl: '',
      message: msgText,
      timestamp: 'Just now',
      isUser: true,
      attachmentType: type,
      attachmentUrl: filePath,
      fileName: fileName,
      fileSize: fileSize,
      caption: caption,
      duration: type == 'voice' ? '0:15' : (type == 'video' ? '0:30' : null),
    );

    setState(() {
      _messages.add(msg);
    });
    _saveMessage(msg);
  }

  void _openAttachmentMenu() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Attach Media & Documents with Captions', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              const SizedBox(height: 16),
              Wrap(
                spacing: 16,
                runSpacing: 16,
                alignment: WrapAlignment.spaceAround,
                children: [
                  _buildAttachOption(Icons.image, 'Gallery / Photo', Colors.purpleAccent, () {
                    Navigator.pop(context);
                    _pickFileAndOpenCaptionDialog('photo');
                  }),
                  _buildAttachOption(Icons.videocam, 'Video Note', Colors.pinkAccent, () {
                    Navigator.pop(context);
                    _pickFileAndOpenCaptionDialog('video');
                  }),
                  _buildAttachOption(Icons.insert_drive_file, 'Document', AppTheme.telegramBlue, () {
                    Navigator.pop(context);
                    _pickFileAndOpenCaptionDialog('document');
                  }),
                  _buildAttachOption(Icons.audiotrack, 'Voice Note', AppTheme.emeraldAccent, () {
                    Navigator.pop(context);
                    _pickFileAndOpenCaptionDialog('voice');
                  }),
                  _buildAttachOption(Icons.camera_alt, 'Camera', Colors.amberAccent, () {
                    Navigator.pop(context);
                    _pickFileAndOpenCaptionDialog('photo');
                  }),
                  _buildAttachOption(Icons.location_on, 'Location', Colors.redAccent, () {
                    Navigator.pop(context);
                    _pickFileAndOpenCaptionDialog('location');
                  }),
                ],
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildAttachOption(IconData icon, String label, Color color, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      child: Column(
        children: [
          CircleAvatar(
            radius: 26,
            backgroundColor: color.withValues(alpha: 0.2),
            child: Icon(icon, color: color, size: 26),
          ),
          const SizedBox(height: 4),
          Text(label, style: const TextStyle(fontSize: 11, color: Colors.white70)),
        ],
      ),
    );
  }

  void _openWallpaperCustomizer() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return GlassCard(
          margin: const EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Select Chat Wallpaper Background', style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold)),
              const SizedBox(height: 14),
              Wrap(
                spacing: 12,
                runSpacing: 12,
                children: [
                  _buildWallpaperOption('OLED Black', Colors.black),
                  _buildWallpaperOption('Midnight Purple', const Color(0xFF1E1B4B)),
                  _buildWallpaperOption('Cyber Neon', const Color(0xFF0284C7)),
                  _buildWallpaperOption('Matrix Green', const Color(0xFF064E3B)),
                  _buildWallpaperOption('Rose Sunset', const Color(0xFF881337)),
                ],
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildWallpaperOption(String label, Color color) {
    return GestureDetector(
      onTap: () {
        setState(() {
          _wallpaperColor = color;
        });
        Navigator.pop(context);
      },
      child: Column(
        children: [
          Container(
            width: 48,
            height: 48,
            decoration: BoxDecoration(
              color: color,
              shape: BoxShape.circle,
              border: Border.all(color: Colors.white38, width: 1.5),
            ),
          ),
          const SizedBox(height: 4),
          Text(label, style: const TextStyle(fontSize: 11, color: Colors.white70)),
        ],
      ),
    );
  }

  void _openEmojiGifPicker() {
    String searchQuery = '';
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        final categoryKeys = _iosEmojiCategories.keys.toList();
        return DefaultTabController(
          length: categoryKeys.length + 1,
          child: Container(
            height: 380,
            decoration: const BoxDecoration(
              color: Color(0xFF1C1C1E),
              borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
            ),
            child: StatefulBuilder(
              builder: (context, setPickerState) {
                return Column(
                  children: [
                    // iOS Top Handle Bar & Actions
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      child: Row(
                        children: [
                          Container(width: 36, height: 4, decoration: BoxDecoration(color: Colors.white24, borderRadius: BorderRadius.circular(2))),
                          const Spacer(),
                          const Text('iOS Emoji Keyboard 🍎', style: TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.bold)),
                          const Spacer(),
                          IconButton(
                            icon: const Icon(Icons.backspace_outlined, color: Colors.white70, size: 20),
                            onPressed: () {
                              if (_textController.text.isNotEmpty) {
                                setState(() {
                                  _textController.text = _textController.text.characters.skipLast(1).toString();
                                });
                              }
                            },
                          ),
                        ],
                      ),
                    ),

                    // iOS Emoji Search Field
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 4),
                      child: Container(
                        height: 36,
                        decoration: BoxDecoration(
                          color: Colors.white.withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: TextField(
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          onChanged: (val) {
                            setPickerState(() {
                              searchQuery = val.toLowerCase().trim();
                            });
                          },
                          decoration: const InputDecoration(
                            isDense: true,
                            contentPadding: EdgeInsets.symmetric(vertical: 8),
                            prefixIcon: Icon(Icons.search, color: Colors.white54, size: 18),
                            hintText: 'Search iOS Emojis & GIFs...',
                            hintStyle: TextStyle(color: Colors.white38, fontSize: 12),
                            border: InputBorder.none,
                          ),
                        ),
                      ),
                    ),

                    // iOS Emoji Category Tab Bar
                    TabBar(
                      isScrollable: true,
                      indicatorColor: AppTheme.telegramBlue,
                      labelColor: Colors.white,
                      unselectedLabelColor: Colors.white38,
                      labelPadding: const EdgeInsets.symmetric(horizontal: 10),
                      tabs: [
                        ...categoryKeys.map((k) {
                          final cat = _iosEmojiCategories[k]!;
                          return Tab(text: '${cat['icon']}');
                        }),
                        const Tab(text: '🎬 GIFs'),
                      ],
                    ),

                    Expanded(
                      child: TabBarView(
                        children: [
                          ...categoryKeys.map((k) {
                            final cat = _iosEmojiCategories[k]!;
                            final List<String> rawEmojis = List<String>.from(cat['emojis']);
                            final filtered = searchQuery.isEmpty
                                ? rawEmojis
                                : rawEmojis.where((e) => e.contains(searchQuery)).toList();

                            return GridView.builder(
                              padding: const EdgeInsets.all(10),
                              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                                crossAxisCount: 7,
                                mainAxisSpacing: 8,
                                crossAxisSpacing: 8,
                              ),
                              itemCount: filtered.length,
                              itemBuilder: (context, index) {
                                final emoji = filtered[index];
                                return InkWell(
                                  borderRadius: BorderRadius.circular(8),
                                  onTap: () {
                                    setState(() {
                                      _textController.text += emoji;
                                    });
                                  },
                                  child: Center(child: Text(emoji, style: const TextStyle(fontSize: 26))),
                                );
                              },
                            );
                          }),

                          // GIFs Tab
                          ListView.builder(
                            padding: const EdgeInsets.all(12),
                            itemCount: _gifs.length,
                            itemBuilder: (context, index) {
                              final gif = _gifs[index];
                              return ListTile(
                                leading: const Icon(Icons.gif, color: AppTheme.pinkPrimary, size: 30),
                                title: Text(gif['title']!, style: const TextStyle(fontSize: 13, color: Colors.white)),
                                onTap: () {
                                  Navigator.pop(context);
                                  _sendMessage(customText: '[GIF] ${gif['title']}');
                                },
                              );
                            },
                          ),
                        ],
                      ),
                    ),
                  ],
                );
              },
            ),
          ),
        );
      },
    );
  }

  void _startCall(String type) {
    if (_isUserBlocked) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Cannot initiate call: User is blocked.')),
      );
      return;
    }

    setState(() {
      _isCallActive = true;
      _callType = type;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            CircleAvatar(
              radius: 16,
              backgroundColor: AppTheme.telegramBlue,
              child: Text(widget.contactName[0], style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white, fontSize: 13)),
            ),
            const SizedBox(width: 8),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(widget.contactName, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold), overflow: TextOverflow.ellipsis),
                  Text(
                    _isUserBlocked ? 'Blocked' : 'Online • Auto-Delete: $_autoDeleteTimer',
                    style: TextStyle(fontSize: 10, color: _isUserBlocked ? Colors.redAccent : AppTheme.emeraldAccent),
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.phone, color: AppTheme.telegramBlue, size: 20),
            onPressed: () => _startCall('Voice Call'),
          ),
          IconButton(
            icon: const Icon(Icons.videocam, color: AppTheme.cyanAccent, size: 20),
            onPressed: () => _startCall('Video Call'),
          ),
          IconButton(
            icon: const Icon(Icons.wallpaper, color: Colors.white70, size: 20),
            onPressed: _openWallpaperCustomizer,
          ),
          // Popup Action Menu for Privacy, Block & Report, Auto-Delete & Erase Chat
          PopupMenuButton<String>(
            icon: const Icon(Icons.more_vert, color: Colors.white),
            color: AppTheme.surface,
            onSelected: (val) {
              if (val == 'auto_delete') {
                _openAutoDeleteSettings();
              } else if (val == 'erase_chat') {
                _eraseChatHistory();
              } else if (val == 'block_report') {
                _blockAndReportUser();
              }
            },
            itemBuilder: (context) => [
              PopupMenuItem(
                value: 'auto_delete',
                child: Row(
                  children: [
                    const Icon(Icons.timer, color: AppTheme.telegramBlue, size: 18),
                    const SizedBox(width: 8),
                    Text('Auto-Delete Timer ($_autoDeleteTimer)', style: const TextStyle(color: Colors.white, fontSize: 13)),
                  ],
                ),
              ),
              PopupMenuItem(
                value: 'erase_chat',
                child: Row(
                  children: const [
                    Icon(Icons.delete_forever, color: Colors.amberAccent, size: 18),
                    SizedBox(width: 8),
                    Text('Erase Chat History', style: TextStyle(color: Colors.white, fontSize: 13)),
                  ],
                ),
              ),
              PopupMenuItem(
                value: 'block_report',
                child: Row(
                  children: const [
                    Icon(Icons.block, color: Colors.redAccent, size: 18),
                    SizedBox(width: 8),
                    Text('Block & Report User', style: TextStyle(color: Colors.redAccent, fontSize: 13, fontWeight: FontWeight.bold)),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
      body: Stack(
        children: [
          Container(
            color: _wallpaperColor,
            child: Column(
              children: [
                if (_pinnedMessage != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                    decoration: BoxDecoration(
                      color: AppTheme.telegramBlue.withValues(alpha: 0.25),
                      border: Border(bottom: BorderSide(color: AppTheme.telegramBlue.withValues(alpha: 0.4))),
                    ),
                    child: Row(
                      children: [
                        const Icon(Icons.push_pin, color: AppTheme.cyanAccent, size: 18),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Pinned Message 📌', style: TextStyle(color: AppTheme.cyanAccent, fontSize: 11, fontWeight: FontWeight.bold)),
                              Text(
                                _pinnedMessage!.caption != null && _pinnedMessage!.caption!.isNotEmpty ? _pinnedMessage!.caption! : _pinnedMessage!.message,
                                style: const TextStyle(color: Colors.white, fontSize: 12),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ],
                          ),
                        ),
                        IconButton(
                          padding: EdgeInsets.zero,
                          constraints: const BoxConstraints(),
                          icon: const Icon(Icons.close, color: Colors.white54, size: 18),
                          onPressed: () {
                            setState(() {
                              _pinnedMessage = null;
                            });
                          },
                        ),
                      ],
                    ),
                  ),

                Expanded(
                  child: _messages.isEmpty
                      ? const Center(
                          child: Text('No messages yet. Send a message to start chatting!', style: TextStyle(color: Colors.white38, fontSize: 13)),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: _messages.length,
                          itemBuilder: (context, index) {
                            final msg = _messages[index];
                            final isVoice = msg.attachmentType == 'voice' || msg.message.contains('Voice Message') || msg.message.contains('Audio_Track');
                            final isVideo = msg.attachmentType == 'video' || msg.message.contains('Video Clip') || msg.message.contains('Video Note');
                            final isPhoto = msg.attachmentType == 'photo' || msg.message.contains('Photo') || msg.message.contains('Snapshot');
                            final isDoc = msg.attachmentType == 'document' || msg.message.contains('Document') || msg.message.contains('.pdf');
                            final isGif = msg.message.contains('[GIF]');

                            return Align(
                              alignment: msg.isUser ? Alignment.centerRight : Alignment.centerLeft,
                              child: GestureDetector(
                                onLongPress: () => _showMessageContextMenu(msg),
                                child: Container(
                                  margin: const EdgeInsets.only(bottom: 10),
                                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                                  constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.8),
                                  decoration: BoxDecoration(
                                    color: msg.isUser ? const Color(0xFF2B5278) : const Color(0xFF182533),
                                    borderRadius: msg.isUser
                                        ? const BorderRadius.only(
                                            topLeft: Radius.circular(16),
                                            topRight: Radius.circular(16),
                                            bottomLeft: Radius.circular(16),
                                            bottomRight: Radius.circular(4),
                                          )
                                        : const BorderRadius.only(
                                            topLeft: Radius.circular(16),
                                            topRight: Radius.circular(16),
                                            bottomLeft: Radius.circular(4),
                                            bottomRight: Radius.circular(16),
                                          ),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withValues(alpha: 0.25),
                                        blurRadius: 4,
                                        offset: const Offset(0, 2),
                                      ),
                                    ],
                                  ),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(msg.sender, style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: msg.isUser ? AppTheme.cyanAccent : Colors.amberAccent)),
                                      const SizedBox(height: 6),

                                      if (isVoice)
                                        VoiceMessagePlayerCard(
                                          duration: msg.duration ?? '0:15',
                                          caption: msg.caption ?? (msg.message != '🎙️ Voice Message' ? msg.message : null),
                                          isUser: msg.isUser,
                                        )
                                      else if (isVideo)
                                        VideoMessagePlayerCard(
                                          duration: msg.duration ?? '0:30',
                                          caption: msg.caption ?? (msg.message != '🎥 Video Note' ? msg.message : null),
                                          isUser: msg.isUser,
                                        )
                                      else if (isPhoto)
                                        Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Container(
                                              width: double.infinity,
                                              height: 140,
                                              decoration: BoxDecoration(
                                                color: Colors.purple.withValues(alpha: 0.2),
                                                borderRadius: BorderRadius.circular(12),
                                                border: Border.all(color: AppTheme.cyanAccent.withValues(alpha: 0.4)),
                                              ),
                                              child: Center(
                                                child: Column(
                                                  mainAxisAlignment: MainAxisAlignment.center,
                                                  children: [
                                                    const Icon(Icons.image, color: AppTheme.cyanAccent, size: 40),
                                                    const SizedBox(height: 4),
                                                    Text(
                                                      '${msg.fileName ?? 'Photo Attachment'} • ${msg.fileSize ?? '2.4 MB'}',
                                                      style: const TextStyle(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.bold),
                                                      maxLines: 1,
                                                      overflow: TextOverflow.ellipsis,
                                                    ),
                                                  ],
                                                ),
                                              ),
                                            ),
                                            if (msg.caption != null && msg.caption!.isNotEmpty) ...[
                                              const SizedBox(height: 6),
                                              Text(msg.caption!, style: const TextStyle(fontSize: 13, color: Colors.white, fontWeight: FontWeight.w500)),
                                            ],
                                          ],
                                        )
                                      else if (isDoc)
                                        Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Container(
                                              padding: const EdgeInsets.all(10),
                                              decoration: BoxDecoration(
                                                color: AppTheme.telegramBlue.withValues(alpha: 0.2),
                                                borderRadius: BorderRadius.circular(12),
                                                border: Border.all(color: AppTheme.telegramBlue.withValues(alpha: 0.4)),
                                              ),
                                              child: Row(
                                                children: [
                                                  const Icon(Icons.insert_drive_file, color: AppTheme.telegramBlue, size: 28),
                                                  const SizedBox(width: 8),
                                                  Expanded(
                                                    child: Column(
                                                      crossAxisAlignment: CrossAxisAlignment.start,
                                                      children: [
                                                        Text(msg.fileName ?? 'Document.pdf', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
                                                        Text('${msg.fileSize ?? '1.8 MB'} • Document', style: const TextStyle(color: Colors.white54, fontSize: 10)),
                                                      ],
                                                    ),
                                                  ),
                                                  const Icon(Icons.download_rounded, color: Colors.white70, size: 20),
                                                ],
                                              ),
                                            ),
                                            if (msg.caption != null && msg.caption!.isNotEmpty) ...[
                                              const SizedBox(height: 6),
                                              Text(msg.caption!, style: const TextStyle(fontSize: 13, color: Colors.white, fontWeight: FontWeight.w500)),
                                            ],
                                          ],
                                        )
                                      else if (isGif)
                                        Container(
                                          padding: const EdgeInsets.all(12),
                                          decoration: BoxDecoration(
                                            color: Colors.black45,
                                            borderRadius: BorderRadius.circular(12),
                                            border: Border.all(color: AppTheme.pinkPrimary.withValues(alpha: 0.5)),
                                          ),
                                          child: Row(
                                            children: [
                                              const Icon(Icons.gif_box, color: AppTheme.pinkPrimary, size: 30),
                                              const SizedBox(width: 8),
                                              Text(msg.message.replaceAll('[GIF] ', ''), style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13)),
                                            ],
                                          ),
                                        )
                                      else
                                        Text(msg.message, style: const TextStyle(fontSize: 14, color: Colors.white)),

                                      const SizedBox(height: 4),
                                      Align(
                                        alignment: Alignment.bottomRight,
                                        child: Row(
                                          mainAxisSize: MainAxisSize.min,
                                          children: [
                                            Text(msg.timestamp, style: const TextStyle(fontSize: 10, color: Colors.white54)),
                                            if (msg.isUser) ...[
                                              const SizedBox(width: 4),
                                              const Text('✓✓', style: TextStyle(fontSize: 10, color: Color(0xFF64B5F6), fontWeight: FontWeight.bold)),
                                            ],
                                          ],
                                        ),
                                      ),
                                    ],
                                  ),
                              ),
                            ),
                          );
                          },
                        ),
                ),

                if (_isRecordingVoice)
                  Container(
                    padding: const EdgeInsets.all(12),
                    color: AppTheme.pinkPrimary.withValues(alpha: 0.2),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: const [
                        Icon(Icons.mic, color: AppTheme.pinkPrimary),
                        SizedBox(width: 8),
                        Text('Recording Voice Message... (Tap Mic to Send)', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),

                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppTheme.background,
                    border: Border(top: BorderSide(color: Colors.white.withValues(alpha: 0.1))),
                  ),
                  child: SafeArea(
                    child: Row(
                      children: [
                        IconButton(
                          icon: const Icon(Icons.emoji_emotions_outlined, color: Colors.amberAccent),
                          onPressed: _openEmojiGifPicker,
                        ),
                        IconButton(
                          icon: const Icon(Icons.attach_file, color: Colors.white54),
                          onPressed: _openAttachmentMenu,
                        ),
                        IconButton(
                          icon: const Icon(Icons.paste, color: AppTheme.cyanAccent, size: 20),
                          tooltip: 'Paste / Append Clipboard',
                          onPressed: () async {
                            final data = await Clipboard.getData(Clipboard.kTextPlain);
                            if (data != null && data.text != null && data.text!.isNotEmpty) {
                              setState(() {
                                if (_textController.text.isNotEmpty) {
                                  _textController.text += ' ${data.text!}';
                                } else {
                                  _textController.text = data.text!;
                                }
                              });
                            }
                          },
                        ),
                        Expanded(
                          child: TextField(
                            controller: _textController,
                            style: const TextStyle(color: Colors.white, fontSize: 14),
                            decoration: InputDecoration(
                              hintText: _isUserBlocked ? 'User is blocked' : 'Message...',
                              hintStyle: const TextStyle(color: Colors.white38),
                              filled: true,
                              fillColor: Colors.white.withValues(alpha: 0.08),
                              contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                              border: OutlineInputBorder(borderRadius: BorderRadius.circular(20), borderSide: BorderSide.none),
                            ),
                            enabled: !_isUserBlocked,
                          ),
                        ),
                        const SizedBox(width: 4),

                        IconButton(
                          icon: Icon(Icons.mic, color: _isRecordingVoice ? AppTheme.pinkPrimary : Colors.white54),
                          onPressed: () {
                            if (_isUserBlocked) return;
                            setState(() {
                              _isRecordingVoice = !_isRecordingVoice;
                              if (!_isRecordingVoice) {
                                _sendMessage(customText: '🎤 [Voice Message 0:14]');
                              }
                            });
                          },
                        ),
                        IconButton(
                          icon: const Icon(Icons.send, color: AppTheme.telegramBlue),
                          onPressed: () => _sendMessage(),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),

          if (_isCallActive)
            Container(
              color: Colors.black.withValues(alpha: 0.94),
              child: SafeArea(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CircleAvatar(
                      radius: 50,
                      backgroundColor: AppTheme.telegramBlue,
                      child: Text(widget.contactName[0], style: const TextStyle(fontSize: 40, fontWeight: FontWeight.bold, color: Colors.white)),
                    ),
                    const SizedBox(height: 16),
                    Text(widget.contactName, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.white)),
                    const SizedBox(height: 8),
                    Text('Active $_callType • 00:42', style: const TextStyle(color: AppTheme.emeraldAccent, fontSize: 14)),
                    const SizedBox(height: 60),

                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        IconButton(
                          iconSize: 32,
                          icon: Icon(_isMuted ? Icons.mic_off : Icons.mic, color: Colors.white),
                          onPressed: () {
                            setState(() {
                              _isMuted = !_isMuted;
                            });
                          },
                        ),
                        FloatingActionButton(
                          backgroundColor: Colors.redAccent,
                          child: const Icon(Icons.call_end, color: Colors.white, size: 30),
                          onPressed: () {
                            setState(() {
                              _isCallActive = false;
                            });
                          },
                        ),
                        IconButton(
                          iconSize: 32,
                          icon: const Icon(Icons.cameraswitch, color: Colors.white),
                          onPressed: () {},
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class VoiceMessagePlayerCard extends StatefulWidget {
  final String duration;
  final String? caption;
  final bool isUser;

  const VoiceMessagePlayerCard({
    super.key,
    required this.duration,
    this.caption,
    required this.isUser,
  });

  @override
  State<VoiceMessagePlayerCard> createState() => _VoiceMessagePlayerCardState();
}

class _VoiceMessagePlayerCardState extends State<VoiceMessagePlayerCard> {
  bool _isPlaying = false;
  double _progress = 0.0;
  String _speed = '1.0x';
  Timer? _timer;

  void _togglePlay() {
    SystemSound.play(SystemSoundType.click);
    if (_isPlaying) {
      _timer?.cancel();
      setState(() {
        _isPlaying = false;
      });
    } else {
      setState(() {
        _isPlaying = true;
        if (_progress >= 1.0) _progress = 0.0;
      });
      final step = _speed == '1.0x' ? 0.04 : (_speed == '1.5x' ? 0.07 : 0.10);
      _timer = Timer.periodic(const Duration(milliseconds: 120), (t) {
        if (!mounted) {
          t.cancel();
          return;
        }
        setState(() {
          _progress += step;
          if (_progress >= 1.0) {
            _progress = 1.0;
            _isPlaying = false;
            t.cancel();
          }
        });
      });
    }
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final totalSec = 15;
    final elapsedSec = (_progress * totalSec).floor();
    final elapsedStr = '0:${elapsedSec.toString().padLeft(2, '0')}';

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            IconButton(
              padding: EdgeInsets.zero,
              constraints: const BoxConstraints(),
              icon: Icon(
                _isPlaying ? Icons.pause_circle_filled : Icons.play_circle_fill,
                color: AppTheme.cyanAccent,
                size: 38,
              ),
              onPressed: _togglePlay,
            ),
            const SizedBox(width: 8),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SliderTheme(
                    data: SliderThemeData(
                      thumbShape: const RoundSliderThumbShape(enabledThumbRadius: 6),
                      overlayShape: const RoundSliderOverlayShape(overlayRadius: 10),
                      trackHeight: 3,
                    ),
                    child: Slider(
                      value: _progress.clamp(0.0, 1.0),
                      activeColor: AppTheme.cyanAccent,
                      inactiveColor: Colors.white24,
                      onChanged: (val) {
                        setState(() {
                          _progress = val;
                        });
                      },
                    ),
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('$elapsedStr / ${widget.duration}', style: const TextStyle(fontSize: 10, color: Colors.white60)),
                      InkWell(
                        onTap: () {
                          setState(() {
                            _speed = _speed == '1.0x' ? '1.5x' : (_speed == '1.5x' ? '2.0x' : '1.0x');
                          });
                        },
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1),
                          decoration: BoxDecoration(
                            color: AppTheme.cyanAccent.withValues(alpha: 0.2),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(_speed, style: const TextStyle(fontSize: 10, color: AppTheme.cyanAccent, fontWeight: FontWeight.bold)),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
        if (widget.caption != null && widget.caption!.trim().isNotEmpty) ...[
          const SizedBox(height: 6),
          Text(
            widget.caption!,
            style: const TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w500),
          ),
        ],
      ],
    );
  }
}

class VideoMessagePlayerCard extends StatefulWidget {
  final String? caption;
  final String duration;
  final bool isUser;

  const VideoMessagePlayerCard({
    super.key,
    this.caption,
    this.duration = '0:30',
    required this.isUser,
  });

  @override
  State<VideoMessagePlayerCard> createState() => _VideoMessagePlayerCardState();
}

class _VideoMessagePlayerCardState extends State<VideoMessagePlayerCard> {
  bool _isPlaying = false;
  double _progress = 0.0;
  Timer? _timer;

  void _togglePlay() {
    SystemSound.play(SystemSoundType.click);
    if (_isPlaying) {
      _timer?.cancel();
      setState(() {
        _isPlaying = false;
      });
    } else {
      setState(() {
        _isPlaying = true;
        if (_progress >= 1.0) _progress = 0.0;
      });
      _timer = Timer.periodic(const Duration(milliseconds: 150), (t) {
        if (!mounted) {
          t.cancel();
          return;
        }
        setState(() {
          _progress += 0.05;
          if (_progress >= 1.0) {
            _progress = 1.0;
            _isPlaying = false;
            t.cancel();
          }
        });
      });
    }
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        GestureDetector(
          onTap: _togglePlay,
          child: Container(
            width: 170,
            height: 170,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              gradient: const LinearGradient(
                colors: [Color(0xFF8B5CF6), Color(0xFFEC4899)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              border: Border.all(color: Colors.white54, width: 2),
              boxShadow: [
                BoxShadow(
                  color: Colors.purple.withValues(alpha: 0.35),
                  blurRadius: 12,
                ),
              ],
            ),
            child: Stack(
              alignment: Alignment.center,
              children: [
                Icon(
                  _isPlaying ? Icons.pause_circle_filled : Icons.play_circle_fill,
                  color: Colors.white,
                  size: 48,
                ),
                Positioned(
                  bottom: 12,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                    decoration: BoxDecoration(
                      color: Colors.black54,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(
                      '🎥 ${widget.duration}',
                      style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
        if (widget.caption != null && widget.caption!.trim().isNotEmpty) ...[
          const SizedBox(height: 8),
          Text(
            widget.caption!,
            style: const TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w500),
          ),
        ],
      ],
    );
  }
}
