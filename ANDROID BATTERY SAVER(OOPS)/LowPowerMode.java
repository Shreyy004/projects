import java.util.HashMap;
import java.util.Map;
import java.util.Scanner;

class LowPowerMode extends Battery implements PowerSaver, BrightnessControl, AppUsageStatistics , BatteryModeCustomization{
    private Map<String, Double> appUsageStatistics;

    public LowPowerMode(int level) {
        super(level);
        appUsageStatistics = new HashMap<>();
    }

    @Override
    public void enablePowerSavingMode() {
        System.out.println("Low Power Mode enabled. Battery level: " + getLevel());
    }

    @Override
    public void setBrightness(int level) {
        System.out.println("Setting low brightness level: " + level);
    }

    @Override
    public void trackAppUsage(String appName) {
        if (appUsageStatistics.containsKey(appName)) {
            double usageTime = appUsageStatistics.get(appName);
            System.out.println("Tracking app usage in Low Power Mode: " + appName + ", Battery Consumption: " + usageTime + "%");
        } else {
            System.out.println("App not found in app usage statistics.");
        }
    }

    public void setAppUsageStatistics(Map<String, Double> appUsageStatistics) {
        this.appUsageStatistics = appUsageStatistics;
    }

        @Override
    public void customizeBatteryMode(Scanner scanner) {
        int choice;
        do {
            System.out.println("Menu for Low Power Mode Customization:");
            System.out.println("1. Adjust Brightness");
            System.out.println("2. Track App Usage");
            System.out.println("3. Exit");

            System.out.print("Enter your choice: ");
            choice = scanner.nextInt();

            switch (choice) {
                case 1:
                    System.out.print("Enter brightness level: ");
                    int brightnessLevel = scanner.nextInt();
                    setBrightness(brightnessLevel);
                    break;
                case 2:
                    System.out.print("Enter app name to track usage: ");
                    String appName = scanner.next();
                    trackAppUsage(appName);
                    break;
                case 3:
                    break;
                default:
                    System.out.println("Invalid choice. Please enter a valid option.");
            }

        } while (choice != 3);
    }
        // Encapsulation for battery level
        public int getBatteryLevel() {
            return getLevel();
        }
    
        public void setBatteryLevel(int level) {
            setLevel(level);
        }
}
