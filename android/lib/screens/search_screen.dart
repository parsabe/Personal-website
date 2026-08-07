import 'package:flutter/material.dart';
import '../models/project.dart';
import '../models/publication.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_card.dart';
import 'project_detail_screen.dart';

class SearchScreen extends StatefulWidget {
  const SearchScreen({super.key});

  @override
  State<SearchScreen> createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _query = '';

  @override
  Widget build(BuildContext context) {
    final allProjects = Project.sampleProjects;
    final allPublications = Publication.samplePublications;

    final matchingProjects = _query.isEmpty
        ? <Project>[]
        : allProjects.where((p) => p.title.toLowerCase().contains(_query.toLowerCase()) || p.shortDescription.toLowerCase().contains(_query.toLowerCase()) || p.tags.any((t) => t.toLowerCase().contains(_query.toLowerCase()))).toList();

    final matchingPublications = _query.isEmpty
        ? <Publication>[]
        : allPublications.where((pub) => pub.title.toLowerCase().contains(_query.toLowerCase()) || pub.abstractText.toLowerCase().contains(_query.toLowerCase()) || pub.keywords.any((k) => k.toLowerCase().contains(_query.toLowerCase()))).toList();

    final allUsers = [
      {
        'name': 'Parsa Besharat',
        'email': 'parsabe99@gmail.com',
        'username': 'parsabe',
        'role': 'Owner & Platform Founder',
        'isVerified': true,
      },
      {
        'name': 'Alex Dev',
        'email': 'alex.dev@parsajournals.com',
        'username': 'alex_dev',
        'role': 'Senior Core Engineer',
        'isVerified': true,
      },
      {
        'name': 'Sarah Miller',
        'email': 'sarah.m@parsajournals.com',
        'username': 'sarah_m',
        'role': 'AI Research Specialist',
        'isVerified': false,
      },
    ];

    final matchingUsers = _query.isEmpty
        ? <Map<String, Object>>[]
        : allUsers.where((u) {
            final q = _query.toLowerCase();
            return (u['name'] as String).toLowerCase().contains(q) ||
                (u['email'] as String).toLowerCase().contains(q) ||
                (u['username'] as String).toLowerCase().contains(q) ||
                (u['role'] as String).toLowerCase().contains(q) ||
                q == 'owner' || q == 'admin' || q == 'parsa';
          }).toList();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Universal Search'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          children: [
            // Search Input Box
            TextField(
              controller: _searchController,
              style: const TextStyle(color: Colors.white),
              onChanged: (val) {
                setState(() {
                  _query = val.trim();
                });
              },
              decoration: InputDecoration(
                prefixIcon: const Icon(Icons.search, color: AppTheme.orangePrimary),
                suffixIcon: _query.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear, color: Colors.white54),
                        onPressed: () {
                          _searchController.clear();
                          setState(() {
                            _query = '';
                          });
                        },
                      )
                    : null,
                hintText: 'Search users, projects, papers, research topics...',
                hintStyle: const TextStyle(color: Colors.white38, fontSize: 14),
                filled: true,
                fillColor: AppTheme.surface,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: const BorderSide(color: Colors.white12),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: const BorderSide(color: AppTheme.orangePrimary),
                ),
              ),
            ),
            const SizedBox(height: 20),

            if (_query.isEmpty)
              const Expanded(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.search, size: 60, color: Colors.white24),
                      SizedBox(height: 12),
                      Text(
                        'Search user (e.g. "parsabe99", "Parsa"), projects, or research topics',
                        style: TextStyle(color: Colors.white38, fontSize: 13),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                ),
              )
            else
              Expanded(
                child: ListView(
                  physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                  padding: const EdgeInsets.fromLTRB(16, 8, 16, 120),
                  children: [
                    if (matchingUsers.isNotEmpty) ...[
                      const Text('USER & AUTHOR RESULTS', style: TextStyle(fontWeight: FontWeight.bold, color: AppTheme.emeraldAccent, fontSize: 13)),
                      const SizedBox(height: 8),
                      ...matchingUsers.map((u) => Padding(
                        padding: const EdgeInsets.only(bottom: 10.0),
                        child: GlassCard(
                          child: ListTile(
                            leading: CircleAvatar(
                              radius: 20,
                              backgroundColor: u['isVerified'] == true ? AppTheme.telegramBlue : AppTheme.pinkPrimary,
                              child: Text((u['name'] as String)[0], style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                            ),
                            title: Row(
                              children: [
                                Text(u['name'] as String, style: const TextStyle(fontWeight: FontWeight.bold)),
                                if (u['isVerified'] == true) ...[
                                  const SizedBox(width: 6),
                                  const Icon(Icons.check_circle, color: AppTheme.emeraldAccent, size: 16),
                                ],
                              ],
                            ),
                            subtitle: Text('${u['email']} • @${u['username']}\n${u['role']}', style: const TextStyle(color: Colors.white60, fontSize: 12)),
                          ),
                        ),
                      )),
                      const SizedBox(height: 16),
                    ],

                    if (matchingProjects.isNotEmpty) ...[
                      const Text('PROJECT RESULTS', style: TextStyle(fontWeight: FontWeight.bold, color: AppTheme.orangePrimary, fontSize: 13)),
                      const SizedBox(height: 8),
                      ...matchingProjects.map((p) => Padding(
                        padding: const EdgeInsets.only(bottom: 10.0),
                        child: GlassCard(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(builder: (_) => ProjectDetailScreen(project: p)),
                            );
                          },
                          child: ListTile(
                            title: Text(p.title, style: const TextStyle(fontWeight: FontWeight.bold)),
                            subtitle: Text(p.shortDescription, maxLines: 2, overflow: TextOverflow.ellipsis),
                            trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.white38),
                          ),
                        ),
                      )),
                      const SizedBox(height: 16),
                    ],

                    if (matchingPublications.isNotEmpty) ...[
                      const Text('PUBLICATION RESULTS', style: TextStyle(fontWeight: FontWeight.bold, color: AppTheme.cyanAccent, fontSize: 13)),
                      const SizedBox(height: 8),
                      ...matchingPublications.map((pub) => Padding(
                        padding: const EdgeInsets.only(bottom: 10.0),
                        child: GlassCard(
                          child: ListTile(
                            title: Text(pub.title, style: const TextStyle(fontWeight: FontWeight.bold)),
                            subtitle: Text(pub.abstractText, maxLines: 2, overflow: TextOverflow.ellipsis),
                          ),
                        ),
                      )),
                    ],

                    if (matchingUsers.isEmpty && matchingProjects.isEmpty && matchingPublications.isEmpty)
                      const Padding(
                        padding: EdgeInsets.only(top: 40),
                        child: Center(
                          child: Text(
                            'No matching users, projects, or publications found.',
                            style: TextStyle(color: Colors.white54),
                          ),
                        ),
                      ),
                  ],
                ),
              ),
          ],
        ),
      ),
    );
  }
}
