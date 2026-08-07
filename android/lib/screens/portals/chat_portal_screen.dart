import 'package:flutter/material.dart';
import '../../models/chat_message.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';
import '../../widgets/gradient_button.dart';

class ChatPortalScreen extends StatefulWidget {
  const ChatPortalScreen({super.key});

  @override
  State<ChatPortalScreen> createState() => _ChatPortalScreenState();
}

class _ChatPortalScreenState extends State<ChatPortalScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final List<ChatMessage> _messages = ChatMessage.sampleMessages;
  final TextEditingController _msgController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  void _sendMessage() {
    final text = _msgController.text.trim();
    if (text.isNotEmpty) {
      setState(() {
        _messages.add(
          ChatMessage(
            id: DateTime.now().toString(),
            sender: 'You (Guest User)',
            avatarUrl: '',
            message: text,
            timestamp: 'Just now',
            isUser: true,
          ),
        );
        _msgController.clear();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Social Portal & Community Chat'),
        bottom: TabBar(
          controller: _tabController,
          indicatorColor: AppTheme.orangePrimary,
          labelColor: AppTheme.orangePrimary,
          unselectedLabelColor: Colors.white60,
          tabs: const [
            Tab(icon: Icon(Icons.forum), text: 'Public Feed'),
            Tab(icon: Icon(Icons.chat), text: 'Live Chat'),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          // Public Feed Tab
          ListView(
            physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
            children: [
              // Post creation box
              GlassCard(
                child: Column(
                  children: [
                    Row(
                      children: [
                        const CircleAvatar(
                          radius: 18,
                          backgroundColor: AppTheme.orangePrimary,
                          child: Icon(
                            Icons.person,
                            color: Colors.white,
                            size: 20,
                          ),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: TextField(
                            controller: _msgController,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 13,
                            ),
                            decoration: const InputDecoration(
                              hintText:
                                  'Share thoughts, photos, or research updates...',
                              hintStyle: TextStyle(
                                color: Colors.white38,
                                fontSize: 13,
                              ),
                              border: InputBorder.none,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Row(
                          children: const [
                            Icon(
                              Icons.image_outlined,
                              color: AppTheme.cyanAccent,
                              size: 20,
                            ),
                            SizedBox(width: 12),
                            Icon(
                              Icons.poll_outlined,
                              color: AppTheme.pinkPrimary,
                              size: 20,
                            ),
                          ],
                        ),
                        GradientButton(
                          text: 'Post',
                          height: 36,
                          onPressed: _sendMessage,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 20),

              // Stories Bar
              SizedBox(
                height: 90,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  children: [
                    _buildStoryItem(
                      'Parsa AI',
                      AppTheme.orangePrimary,
                      isAdd: true,
                    ),
                    _buildStoryItem('Vectra DB', AppTheme.pinkPrimary),
                    _buildStoryItem('TU Freiberg', AppTheme.cyanAccent),
                    _buildStoryItem('Blackwall', AppTheme.purpleAccent),
                    _buildStoryItem('CyberSec', AppTheme.emeraldAccent),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // Posts Stream
              ..._messages.map(
                (msg) => Padding(
                  padding: const EdgeInsets.only(bottom: 12.0),
                  child: GlassCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            CircleAvatar(
                              radius: 16,
                              backgroundColor: msg.isUser
                                  ? AppTheme.cyanAccent
                                  : AppTheme.orangePrimary,
                              child: Text(
                                msg.sender[0],
                                style: const TextStyle(
                                  fontWeight: FontWeight.bold,
                                  color: Colors.white,
                                ),
                              ),
                            ),
                            const SizedBox(width: 10),
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  msg.sender,
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 13,
                                  ),
                                ),
                                Text(
                                  msg.timestamp,
                                  style: const TextStyle(
                                    color: Colors.white38,
                                    fontSize: 11,
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        Text(
                          msg.message,
                          style: const TextStyle(fontSize: 14, height: 1.4),
                        ),
                        const SizedBox(height: 12),
                        Row(
                          children: [
                            const Icon(
                              Icons.favorite_border,
                              color: AppTheme.pinkPrimary,
                              size: 18,
                            ),
                            const SizedBox(width: 4),
                            Text(
                              '${msg.likes}',
                              style: const TextStyle(
                                color: Colors.white54,
                                fontSize: 12,
                              ),
                            ),
                            const SizedBox(width: 20),
                            const Icon(
                              Icons.repeat,
                              color: AppTheme.cyanAccent,
                              size: 18,
                            ),
                            const SizedBox(width: 4),
                            Text(
                              '${msg.reposts}',
                              style: const TextStyle(
                                color: Colors.white54,
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ],
          ),

          // Live Chat Tab
          Column(
            children: [
              Expanded(
                child: ListView.builder(
                  padding: const EdgeInsets.all(20),
                  itemCount: _messages.length,
                  itemBuilder: (context, index) {
                    final msg = _messages[index];
                    return Align(
                      alignment: msg.isUser
                          ? Alignment.centerRight
                          : Alignment.centerLeft,
                      child: Container(
                        margin: const EdgeInsets.only(bottom: 10),
                        padding: const EdgeInsets.symmetric(
                          horizontal: 14,
                          vertical: 10,
                        ),
                        constraints: BoxConstraints(
                          maxWidth: MediaQuery.of(context).size.width * 0.75,
                        ),
                        decoration: BoxDecoration(
                          color: msg.isUser
                              ? AppTheme.orangePrimary.withValues(alpha: 0.3)
                              : AppTheme.surface,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(
                            color: msg.isUser
                                ? AppTheme.orangePrimary
                                : Colors.white12,
                          ),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              msg.sender,
                              style: const TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.bold,
                                color: Colors.white70,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              msg.message,
                              style: const TextStyle(
                                fontSize: 13,
                                color: Colors.white,
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
              ),
              Container(
                padding: const EdgeInsets.all(12),
                color: AppTheme.surface,
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _msgController,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 13,
                        ),
                        decoration: InputDecoration(
                          hintText: 'Type a message...',
                          hintStyle: const TextStyle(color: Colors.white38),
                          filled: true,
                          fillColor: Colors.black26,
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(20),
                            borderSide: BorderSide.none,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    IconButton(
                      icon: const Icon(
                        Icons.send,
                        color: AppTheme.orangePrimary,
                      ),
                      onPressed: _sendMessage,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildStoryItem(String title, Color color, {bool isAdd = false}) {
    return Container(
      margin: const EdgeInsets.only(right: 12),
      child: Column(
        children: [
          CircleAvatar(
            radius: 28,
            backgroundColor: color,
            child: CircleAvatar(
              radius: 25,
              backgroundColor: AppTheme.surface,
              child: isAdd
                  ? const Icon(Icons.add, color: Colors.white)
                  : Text(
                      title[0],
                      style: TextStyle(
                        color: color,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
            ),
          ),
          const SizedBox(height: 4),
          Text(
            title,
            style: const TextStyle(fontSize: 11, color: Colors.white70),
          ),
        ],
      ),
    );
  }
}
