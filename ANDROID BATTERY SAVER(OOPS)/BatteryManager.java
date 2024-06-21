import java.util.HashMap;
import java.util.Map;
import java.util.Scanner;

public class BatteryManager {
    public static void main(String[] args) {
        System.out.println("Android Battery Saver: ");
        Scanner scanner = new Scanner(System.in);
        System.out.print("Enter the battery percentage: ");
        int batteryPercentage = scanner.nextInt();

        Map<String, Double> appUsageStatistics = new HashMap<>();
        appUsageStatistics.put("Instagram", 27.0);
        appUsageStatistics.put("Spotify", 18.0);
        appUsageStatistics.put("Snapchat", 17.0);
        appUsageStatistics.put("Youtube", 12.0);
        appUsageStatistics.put("Whatsapp", 6.0);
        appUsageStatistics.put("Twitter", 3.0);
        appUsageStatistics.put("Google Photos", 2.0);
        appUsageStatistics.put("Message", 0.46);
        appUsageStatistics.put("Chrome", 4.7);
        appUsageStatistics.put("Outlook", 10.0);
        appUsageStatistics.put("Google play Services", 5.0);

        LowPowerMode lowPowerMode = new LowPowerMode(batteryPercentage);
        lowPowerMode.setAppUsageStatistics(appUsageStatistics);

        printBatteryStatus(batteryPercentage);

        customizeBatteryMode(lowPowerMode);

        scanner.close();
    }

    private static void printBatteryStatus(int batteryPercentage) {
        if (batteryPercentage > 30) {
            System.out.println("Normal Battery Level.Turning on Battery saver manually.");
        } else {
            System.out.println("Low battery! Switching on in low-power mode.");
        }
    }

private static void customizeBatteryMode(LowPowerMode lowPowerMode) {
    Scanner scanner = new Scanner(System.in);
    int choice;
do {
    System.out.println("Menu for Battery Mode Customization:");
    System.out.println("1. Adjust Brightness");
    System.out.println("2. Track App Usage");
    System.out.println("3. Grant Permission (High Performance Mode)");
    System.out.println("4. Exit");

    System.out.print("Enter your choice: ");
    choice = scanner.nextInt();

    switch (choice) {
        case 1:
            System.out.print("Enter brightness level: ");
            int brightnessLevel = scanner.nextInt();
            lowPowerMode.setBrightness(brightnessLevel);
            break;
        case 2:
            System.out.print("Enter app name to track usage: ");
            String appName = scanner.next();
            lowPowerMode.trackAppUsage(appName);
            break;
        case 3:
            if (lowPowerMode.getLevel() > 30) {
                HighPerformanceMode highPerformanceMode = new HighPerformanceMode(lowPowerMode.getLevel());
                System.out.print("Enter permission to grant: ");
                String permission = scanner.next();

                highPerformanceMode.grantPermission(permission);
            } else {
                System.out.println("Permission can only be granted in High Performance Mode when battery level is above 30%.");
            }
            break;
        case 4:
        System.out.println("Turning off battery saver...");
            break;
        default:
            System.out.println("Invalid choice. Please enter a valid option.");
    }

} while (choice != 4);

scanner.close();

}
}

