import 'package:flutter_test/flutter_test.dart';
import 'package:android/main.dart';

void main() {
  testWidgets('ParsaApp smoke test', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(const ParsaApp());

    // Verify that the title text exists.
    expect(find.text('Parsa Besharat AI'), findsWidgets);
  });
}
