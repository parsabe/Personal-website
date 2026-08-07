import 'package:flutter/material.dart';
import '../../models/vpn_node.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';
import '../../widgets/gradient_button.dart';

class VpnStatusScreen extends StatefulWidget {
  const VpnStatusScreen({super.key});

  @override
  State<VpnStatusScreen> createState() => _VpnStatusScreenState();
}

class _VpnStatusScreenState extends State<VpnStatusScreen> {
  final List<VpnNode> _nodes = VpnNode.sampleNodes;
  String? _connectedNodeId;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('VPN Server Monitor'),
      ),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
        padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Secure Node Infrastructure', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            const SizedBox(height: 6),
            const Text('Real-time node latencies, IP endpoints & load status.', style: TextStyle(color: Colors.white60, fontSize: 13)),
            const SizedBox(height: 20),

            // Connection Status Banner
            GlassCard(
              border: Border.all(
                color: _connectedNodeId != null ? AppTheme.emeraldAccent : Colors.white24,
              ),
              child: Column(
                children: [
                  Row(
                    children: [
                      Icon(
                        _connectedNodeId != null ? Icons.shield : Icons.shield_outlined,
                        color: _connectedNodeId != null ? AppTheme.emeraldAccent : Colors.white38,
                        size: 36,
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              _connectedNodeId != null ? 'PROTECTED & CONNECTED' : 'NOT CONNECTED',
                              style: TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 15,
                                color: _connectedNodeId != null ? AppTheme.emeraldAccent : Colors.white60,
                              ),
                            ),
                            Text(
                              _connectedNodeId != null ? 'Tunneling active via node $_connectedNodeId' : 'Select a node below to establish secure tunnel',
                              style: const TextStyle(fontSize: 12, color: Colors.white54),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  if (_connectedNodeId != null) ...[
                    const SizedBox(height: 14),
                    GradientButton(
                      text: 'Disconnect Tunnel',
                      height: 38,
                      gradient: const LinearGradient(colors: [Colors.redAccent, Colors.deepOrange]),
                      onPressed: () {
                        setState(() {
                          _connectedNodeId = null;
                        });
                      },
                    )
                  ],
                ],
              ),
            ),
            const SizedBox(height: 24),

            const Text('Available VPN Nodes', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),

            ..._nodes.map((node) {
              final isConnected = _connectedNodeId == node.id;
              return Padding(
                padding: const EdgeInsets.only(bottom: 12.0),
                child: GlassCard(
                  border: Border.all(color: isConnected ? AppTheme.emeraldAccent : Colors.white12),
                  child: ListTile(
                    leading: Text(node.flagEmoji, style: const TextStyle(fontSize: 28)),
                    title: Text('${node.country} (${node.city})', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                    subtitle: Text('IP: ${node.ipAddress} • Load: ${node.loadPercentage}%', style: const TextStyle(fontSize: 12, color: Colors.white60)),
                    trailing: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Text('${node.latencyMs} ms', style: const TextStyle(color: AppTheme.cyanAccent, fontWeight: FontWeight.bold, fontSize: 13)),
                        const SizedBox(height: 4),
                        GestureDetector(
                          onTap: () {
                            setState(() {
                              _connectedNodeId = isConnected ? null : node.id;
                            });
                          },
                          child: Text(
                            isConnected ? 'Disconnect' : 'Connect',
                            style: TextStyle(
                              color: isConnected ? Colors.redAccent : AppTheme.orangePrimary,
                              fontWeight: FontWeight.bold,
                              fontSize: 12,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            }),
          ],
        ),
      ),
    );
  }
}
