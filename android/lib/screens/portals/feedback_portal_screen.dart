import 'package:flutter/material.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';
import '../../widgets/gradient_button.dart';

class FeedbackPortalScreen extends StatefulWidget {
  const FeedbackPortalScreen({super.key});

  @override
  State<FeedbackPortalScreen> createState() => _FeedbackPortalScreenState();
}

class _FeedbackPortalScreenState extends State<FeedbackPortalScreen> {
  double _rating = 5.0;
  final TextEditingController _feedbackController = TextEditingController();
  bool _submitted = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('CS Feedback Portal'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Submit Portal Feedback', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            const SizedBox(height: 6),
            const Text('Rate your experience or submit suggestions for Parsa AI services.', style: TextStyle(color: Colors.white60, fontSize: 13)),
            const SizedBox(height: 20),

            if (_submitted)
              GlassCard(
                border: Border.all(color: AppTheme.emeraldAccent),
                child: Column(
                  children: [
                    const Icon(Icons.stars, color: AppTheme.emeraldAccent, size: 50),
                    const SizedBox(height: 12),
                    const Text('Feedback Submitted!', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 6),
                    const Text('Thank you for helping improve the Parsa AI & CS platform.', style: TextStyle(color: Colors.white70, fontSize: 13)),
                    const SizedBox(height: 16),
                    GradientButton(
                      text: 'Submit New Review',
                      onPressed: () {
                        setState(() {
                          _submitted = false;
                          _feedbackController.clear();
                        });
                      },
                    )
                  ],
                ),
              )
            else
              GlassCard(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Overall Rating', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                    const SizedBox(height: 10),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: List.generate(5, (index) {
                        return IconButton(
                          icon: Icon(
                            index < _rating ? Icons.star : Icons.star_border,
                            color: Colors.amber,
                            size: 32,
                          ),
                          onPressed: () {
                            setState(() {
                              _rating = (index + 1).toDouble();
                            });
                          },
                        );
                      }),
                    ),
                    const SizedBox(height: 16),
                    TextField(
                      controller: _feedbackController,
                      style: const TextStyle(color: Colors.white),
                      maxLines: 4,
                      decoration: InputDecoration(
                        hintText: 'Share detailed comments or technical suggestions...',
                        hintStyle: const TextStyle(color: Colors.white38, fontSize: 13),
                        filled: true,
                        fillColor: Colors.black26,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: GradientButton(
                        text: 'Submit Feedback',
                        icon: Icons.send,
                        onPressed: () {
                          if (_feedbackController.text.trim().isNotEmpty) {
                            setState(() {
                              _submitted = true;
                            });
                          }
                        },
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
