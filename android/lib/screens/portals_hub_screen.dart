import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import 'portals/sandika_portal_screen.dart';
import 'portals/nigma_portal_screen.dart';
import 'portals/journal_portal_screen.dart';

class PortalsHubScreen extends StatefulWidget {
  const PortalsHubScreen({super.key});

  @override
  State<PortalsHubScreen> createState() => _PortalsHubScreenState();
}

class _PortalsHubScreenState extends State<PortalsHubScreen> with SingleTickerProviderStateMixin {
  late TabController _portalsTabController;

  @override
  void initState() {
    super.initState();
    _portalsTabController = TabController(length: 3, vsync: this);
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          color: Colors.transparent,
          child: TabBar(
            controller: _portalsTabController,
            indicatorColor: AppTheme.telegramBlue,
            labelColor: AppTheme.telegramBlue,
            unselectedLabelColor: Colors.white54,
            tabs: const [
              Tab(icon: Icon(Icons.auto_awesome), text: 'Sandika AI'),
              Tab(icon: Icon(Icons.menu_book), text: 'Journal'),
              Tab(icon: Icon(Icons.psychology), text: 'Nigma Riddles'),
            ],
          ),
        ),
        Expanded(
          child: TabBarView(
            controller: _portalsTabController,
            children: const [
              SandikaPortalScreen(),
              JournalPortalScreen(),
              NigmaPortalScreen(),
            ],
          ),
        ),
      ],
    );
  }
}
