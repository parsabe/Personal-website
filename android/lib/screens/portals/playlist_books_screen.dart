import 'package:flutter/material.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';

class PlaylistBooksScreen extends StatefulWidget {
  const PlaylistBooksScreen({super.key});

  @override
  State<PlaylistBooksScreen> createState() => _PlaylistBooksScreenState();
}

class _PlaylistBooksScreenState extends State<PlaylistBooksScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  int _playingTrackIndex = -1;

  final List<Map<String, String>> _tracks = [
    {'title': 'Cyberpunk Research Waves', 'artist': 'Parsa Besharat AI Mix', 'duration': '3:45'},
    {'title': 'Neural Focus Deep Work', 'artist': 'Ambient Frequency', 'duration': '4:20'},
    {'title': 'Sub-Millisecond Vectors', 'artist': 'Vectra Synth', 'duration': '2:58'},
    {'title': 'Post-Quantum Lattices', 'artist': 'Cryptographic Beats', 'duration': '3:30'},
  ];

  final List<Map<String, String>> _books = [
    {'title': 'Deep Learning', 'author': 'Ian Goodfellow, Yoshua Bengio, Aaron Courville', 'desc': 'The definitive textbook on deep learning principles and algorithms.'},
    {'title': 'Designing Data-Intensive Applications', 'author': 'Martin Kleppmann', 'desc': 'The architecture behind reliable, scalable, and maintainable data systems.'},
    {'title': 'Applied Cryptography', 'author': 'Bruce Schneier', 'desc': 'Protocols, algorithms, and source code in C for modern security.'},
    {'title': 'Machine Learning Yearning', 'author': 'Andrew Ng', 'desc': 'Structuring machine learning projects and hyperparameter strategies.'},
  ];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Playlist & Books'),
        bottom: TabBar(
          controller: _tabController,
          indicatorColor: AppTheme.purpleAccent,
          labelColor: AppTheme.purpleAccent,
          unselectedLabelColor: Colors.white60,
          tabs: const [
            Tab(icon: Icon(Icons.music_note), text: 'Playlist'),
            Tab(icon: Icon(Icons.menu_book), text: 'Books'),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          // Playlist Tab
          ListView.builder(
            physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
            itemCount: _tracks.length,
            itemBuilder: (context, index) {
              final track = _tracks[index];
              final isPlaying = _playingTrackIndex == index;

              return Padding(
                padding: const EdgeInsets.only(bottom: 12.0),
                child: GlassCard(
                  border: Border.all(color: isPlaying ? AppTheme.purpleAccent : Colors.white12),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: isPlaying ? AppTheme.purpleAccent : Colors.white10,
                      child: Icon(
                        isPlaying ? Icons.equalizer : Icons.music_note,
                        color: isPlaying ? Colors.white : Colors.white54,
                      ),
                    ),
                    title: Text(track['title']!, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                    subtitle: Text(track['artist']!, style: const TextStyle(fontSize: 12, color: Colors.white60)),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(track['duration']!, style: const TextStyle(color: Colors.white54, fontSize: 12)),
                        IconButton(
                          icon: Icon(isPlaying ? Icons.pause_circle_filled : Icons.play_circle_fill, color: AppTheme.purpleAccent, size: 30),
                          onPressed: () {
                            setState(() {
                              _playingTrackIndex = isPlaying ? -1 : index;
                            });
                          },
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),

          // Books Tab
          ListView.builder(
            physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
            itemCount: _books.length,
            itemBuilder: (context, index) {
              final book = _books[index];
              return Padding(
                padding: const EdgeInsets.only(bottom: 14.0),
                child: GlassCard(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          const Icon(Icons.book, color: AppTheme.orangePrimary),
                          const SizedBox(width: 10),
                          Expanded(
                            child: Text(book['title']!, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                          ),
                        ],
                      ),
                      const SizedBox(height: 6),
                      Text('By ${book['author']}', style: const TextStyle(color: AppTheme.cyanAccent, fontSize: 12, fontWeight: FontWeight.w600)),
                      const SizedBox(height: 8),
                      Text(book['desc']!, style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.4)),
                    ],
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }
}
