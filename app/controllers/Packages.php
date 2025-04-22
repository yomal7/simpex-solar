<?php
    class Packages extends Controller {
        private $packagesModel;
        private $userModel;
        private $preProjectModel;

        public function __construct() {
            
            $this->packagesModel = $this->model("M_Packages");
            $this->userModel = $this->model("M_Users");
            $this->preProjectModel = $this->model('M_CustomerPreProject');
        }

        public function index() {
            $packages = $this->packagesModel->getAllPackagesWithFeatures();
            $data = [
                'packages' => $packages
            ];
            $this->view('packages/v_packageShowcase', $data);
        }

        public function packageConformation($slug) {
            if (!isLoggedIn()) {
                redirect('users/index');
                return;
            }
            $package = $this->packagesModel->getPackageBySlug($slug);
            if (!$package) {
                redirect('packages/index');
            }
            $data['package'] = $package;
            $this->view('packages/v_packageConformation', $data);
        }

        public function submitQuotation() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                error_log("Processing POST request in submitQuotation");
                
                $data = [
                    'user_id' => $_SESSION['user_id'], // Make sure this exists!
                    'package_id' => trim($_POST['package_id']),
                    'package_type' => trim($_POST['package_type']),
                    'address' => trim($_POST['address']),
                    'monthly_consumption' => trim($_POST['monthly_consumption']),
                    'nearest_city' => trim($_POST['nearest_city']),
                    'customizations' => trim($_POST['customizations']),
                    'phone' => trim($_POST['phone'])
                ];
        
                error_log("Data prepared: " . print_r($data, true));
        
                // Check if user is logged in
                if(!isset($_SESSION['user_id'])) {
                    error_log("No user_id in session");
                    flash('error_msg', 'Please login to submit a quotation');
                    redirect('users/login');
                    return;
                }
        
                // Try to submit
                if($this->preProjectModel->submitQuotation($data)) {
                    error_log("Quotation submitted successfully");
                    flash('success_msg', 'Your quotation has been submitted successfully');
                    redirect('client/operationDashboard');
                } else {
                    error_log("Quotation submission failed");
                    flash('error_msg', 'Something went wrong. Please try again.');
                    redirect('packages/submitQuotation');
                }
            } else {
                redirect('packages/index');
            }
        }

        public function packageDetails($slug = null) {
            if ($slug) {
                $package = $this->packagesModel->getPackageBySlug($slug);
                if ($package) {
                    $equipment = $this->packagesModel->getPackageEquipmentDetails($package->package_id);
                    $data = [
                        'package' => $package,
                        'equipment' => $equipment
                    ];
                    $this->view('packages/v_packageDetails', $data);
                } else {
                    redirect('pages/error');
                }
            } else {
                redirect('pages/error');
            }
        }

    
        public function filterByType() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Set proper headers
                header('Content-Type: application/json');
                header('X-Requested-With: XMLHttpRequest');
        
                try {
                    $type = isset($_POST['type']) ? trim($_POST['type']) : 'all';
                    
                    if ($type === 'all') {
                        $packages = $this->packagesModel->getAllPackagesWithFeatures();
                    } else {
                        $packages = $this->packagesModel->getPackagesByType($type);
                    }
                    
                    // Convert to array and ensure all required fields are present
                    $response = array_map(function($package) {
                        return [
                            'package_id' => $package->package_id,
                            'title' => $package->title,
                            'type' => $package->type,
                            'price' => $package->price,
                            'final_price' => $package->final_price,
                            'image' => $package->image,
                            'features' => $package->features ? explode('|', $package->features) : []
                        ];
                    }, $packages);
                    
                    echo json_encode($response);
                } catch (Exception $e) {
                    echo json_encode(['error' => 'Failed to fetch packages']);
                }
                exit;
            }
            redirect('packages/index');

        }

        // public function selectPackage() {
        //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //         // Get user inputs
        //         $data = [
        //             'budget' => isset($_POST['price']) ? trim($_POST['price']) : null,
        //             'consumption' => isset($_POST['consumption']) ? trim($_POST['consumption']) : null,
        //             'system_type' => isset($_POST['systemType']) ? trim($_POST['systemType']) : null
        //         ];
        
        //         // Validate input data
        //         if (!$data['budget'] || !$data['consumption'] || !$data['system_type']) {
        //             echo json_encode(['success' => false, 'error' => 'Missing required input fields.']);
        //             return;
        //         }
        
        //         try {
        //             // Get packages by system type
        //             $packages = $this->packagesModel->getPackagesByType($data['system_type']);
        
        //             if (empty($packages)) {
        //                 echo json_encode(['success' => false, 'error' => 'No packages available for the selected system type.']);
        //                 return;
        //             }
        
        //             // Prepare data for AI
        //             $aiRequest = $this->preparePackageData($packages, $data);
        
        //             // Get AI recommendation
        //             $recommendation = $this->getAIRecommendation($aiRequest);
        
        //             if ($recommendation && isset($recommendation['package_id'])) {
        //                 // Get package details
        //                 $package = $this->packagesModel->getPackageById($recommendation['package_id']);
        
        //                 if (!$package) {
        //                     echo json_encode(['success' => false, 'error' => 'Recommended package not found in the database.']);
        //                     return;
        //                 }
        
        //                 $features = $this->packagesModel->getPackageFeatures($package->package_id);
        //                 $equipment = $this->packagesModel->getPackageEquipmentDetails($package->package_id);
        
        //                 // Prepare image URL properly
        //                 $imageUrl = !empty($package->image) ? URLROOT . "/public/" . ltrim($package->image, '/') : URLROOT . "/public/images/default-package.jpg";
        
        //                 echo json_encode([
        //                     'success' => true,
        //                     'package' => [
        //                         'details' => $package,
        //                         'features' => array_map(function ($feature) {
        //                             return $feature->feature_name;
        //                         }, $features),
        //                         'equipment' => $equipment,
        //                         'reasoning' => $recommendation['reasoning'],
        //                         'image' => $imageUrl,
        //                         'slug' => $package->slug
        //                     ]
        //                 ]);
        //                 return;
        //             } else {
        //                 echo json_encode(['success' => false, 'error' => 'No suitable package found from AI recommendation.']);
        //                 return;
        //             }
        //         } catch (Exception $e) {
        //             error_log("Package selection error: " . $e->getMessage());
        //             echo json_encode(['success' => false, 'error' => 'An error occurred while selecting the package.']);
        //             return;
        //         }
        //     }
        
        //     // Handle GET request: show package selector page
        //     $this->view('packages/v_packageSelector', [
        //         'package' => null,
        //         'features' => null,
        //         'imageUrl' => null,
        //         'reasoning' => null
        //     ]);
        // }
        
        
        
        
        
        
        
        // private function preparePackageData($packages, $userInput) {
        //     if (isset($userInput['budget']) && isset($userInput['consumption']) && isset($userInput['system_type'])) {
        //         $aiData = [
        //             'user_requirements' => [
        //                 'budget' => floatval($userInput['budget']),
        //                 'monthly_consumption' => floatval($userInput['consumption']),
        //                 'system_type' => $userInput['system_type']
        //             ],
        //             'packages' => array_map(function($package) {
        //                 return [
        //                     'package_id' => $package->package_id,
        //                     'name' => $package->title,
        //                     'price' => floatval($package->price),
        //                     'type' => $package->type,
        //                     'features' => explode(',', $package->features)
        //                 ];
        //             }, $packages)
        //         ];
        
        //         return $aiData;
        //     }
        //     return null;
        // }
        
        // private function getAIRecommendation($data) {
        //     $prompt = "As a solar energy expert, analyze the following customer requirements and available solar packages to recommend the most suitable package.
                        
        //                 Customer Requirements:
        //                 - Budget: {$data['user_requirements']['budget']} LKR
        //                 - Monthly Power Consumption: {$data['user_requirements']['monthly_consumption']} kWh
        //                 - Preferred System Type: {$data['user_requirements']['system_type']}
                        
        //                 Available Packages:
        //                 " . json_encode($data['packages'], JSON_PRETTY_PRINT) . "
                        
        //                 Please analyze these options and recommend the single most suitable package based on:
        //                 1. Budget compatibility
        //                 2. Power output matching consumption needs (package size in at the package title)
        //                 3. System type alignment
        //                 4. Value for money considering features and equipment
        //                 5. Should select at least 1 package
                        
        //                 Return your response in JSON format with the following structure:
        //                 {
        //                     'package_id': 'selected_package_id',
        //                     'reasoning': 'brief explanation of why this package was selected and tell for customer about that. Identify package with package name when telling the customer.'
        //                 }";
        
        //     try {
        //         // Send the request using cURL for better error handling and debugging
        //         $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
        //         $apiKey = 'AIzaSyDK58e3NbmRRXaIU4ScgWRbGS3aBrY2xL4'; // Replace with your valid key
        
        //         $postData = json_encode([
        //             'contents' => [
        //                 ['parts' => [['text' => $prompt]]]
        //             ]
        //         ]);
        
        //         $headers = [
        //             'Content-Type: application/json',
        //             'x-goog-api-key: ' . $apiKey
        //         ];
        
        //         $ch = curl_init();
        //         curl_setopt($ch, CURLOPT_URL, $apiUrl);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //         curl_setopt($ch, CURLOPT_POST, true);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
        //         $response = curl_exec($ch);
        //         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //         curl_close($ch);
        
        //         // Log full response content for debugging
        
        //         if ($httpCode === 401) {
        //             error_log("🔴 ERROR: API Key is invalid or unauthorized.");
        //             return null;
        //         }
        
        //         if ($httpCode !== 200) {
        //             error_log("🔴 AI API request failed with status code: " . $httpCode);
        //             return null;
        //         }
        
        //         // Decode the raw response
        //         $decodedResponse = json_decode($response, true);
        //         if (json_last_error() === JSON_ERROR_NONE) {
        //             // Extract the raw response content
        //             $rawText = $decodedResponse['candidates'][0]['content']['parts'][0]['text'];
        
        //             // Remove markdown code block markers and trim excess spaces
        //             $cleanedText = preg_replace('/```json\\n|\\n```/', '', $rawText); // Strip markdown
        
        //             // Decode the cleaned JSON content
        //             $finalResult = json_decode($cleanedText, true);
        
        //             // Check if decoding was successful
        //             if (json_last_error() === JSON_ERROR_NONE) {
        //                 // Log and return the recommendation, including package_id
        //                 error_log("Selected Package ID: " . $finalResult['package_id']);
        //                 return $finalResult;
        //             } else {
        //                 error_log("🔴 Invalid JSON returned after cleaning: " . json_last_error_msg());
        //             }
        //         } else {
        //             error_log("🔴 Invalid JSON returned: " . json_last_error_msg());
        //         }
        
        //     } catch (Exception $e) {
        //         error_log("AI API error: " . $e->getMessage());
        //     }
        
        //     return null;
        // }
        public function selectPackage() {
            $data = [
                'price' => isset($_POST['price']) ? trim($_POST['price']) : '',
                'consumption' => isset($_POST['consumption']) ? trim($_POST['consumption']) : '',
                'systemType' => isset($_POST['systemType']) ? trim($_POST['systemType']) : '',
                'error' => '',
                'packages' => [], // Array to hold multiple packages
                'package' => null, // For backward compatibility
                'features' => [],
                'reasoning' => '',
                'imageUrl' => '',
                'slug' => ''
            ];
        
            // Only process form if it was submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validate input data
                if (!$data['price'] || !$data['consumption'] || !$data['systemType']) {
                    $data['error'] = 'Missing required input fields.';
                } else {
                    try {
                        // Get packages by system type
                        $packages = $this->packagesModel->getPackagesByType($data['systemType']);
        
                        if (empty($packages)) {
                            $data['error'] = 'No packages available for the selected system type.';
                        } else {
                            // Sort packages by match score (using the existing findBestPackage logic)
                            $matchedPackages = $this->findMatchingPackages($packages, $data);
        
                            if (!empty($matchedPackages)) {
                                // Get top 3 matched packages
                                $topPackages = array_slice($matchedPackages, 0, 3);
                                
                                // Prepare data for each package
                                foreach ($topPackages as $pkg) {
                                    $package = $this->packagesModel->getPackageById($pkg->package_id);
                                    
                                    if ($package) {
                                        $features = $this->packagesModel->getPackageFeatures($package->package_id);
                                        $featureNames = array_map(function ($feature) {
                                            return $feature->feature_name;
                                        }, $features);
                                        
                                        // Prepare image URL
                                        $imageUrl = !empty($package->image) ? 
                                            URLROOT . "/public/" . ltrim($package->image, '/') : 
                                            URLROOT . "/public/images/default-package.jpg";
                                        
                                        // Generate reasoning
                                        $reasoning = $this->generateReasoningText($package, $data);
                                        
                                        // Add to packages array
                                        $data['packages'][] = [
                                            'package' => $package,
                                            'features' => $featureNames,
                                            'reasoning' => $reasoning,
                                            'imageUrl' => $imageUrl,
                                            'slug' => $package->slug,
                                            'match_score' => isset($pkg->match_score) ? $pkg->match_score : 0
                                        ];
                                    }
                                }
                                
                                // For backward compatibility, set the first package as the "best" one
                                if (!empty($data['packages'])) {
                                    $bestPkg = $data['packages'][0];
                                    $data['package'] = $bestPkg['package'];
                                    $data['features'] = $bestPkg['features'];
                                    $data['reasoning'] = $bestPkg['reasoning'];
                                    $data['imageUrl'] = $bestPkg['imageUrl'];
                                    $data['slug'] = $bestPkg['slug'];
                                }
                            } else {
                                $data['error'] = 'No suitable packages found for your requirements.';
                            }
                        }
                    } catch (Exception $e) {
                        error_log("Package selection error: " . $e->getMessage());
                        $data['error'] = 'An error occurred while selecting packages.';
                    }
                }
        
                // If this was an AJAX request, return JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    if ($data['error']) {
                        echo json_encode(['success' => false, 'error' => $data['error']]);
                    } else {
                        echo json_encode([
                            'success' => true,
                            'packages' => $data['packages']
                        ]);
                    }
                    return;
                }
            }
        
            // Load the view with the data
            $this->view('packages/v_packageSelector', $data);
        }
        
        /**
         * Find matching packages based on user requirements
         * 
         * @param array $packages List of packages matching the system type
         * @param array $data User's requirements
         * @return array List of matching packages with match scores
         */
        private function findMatchingPackages($packages, $data) {
            $budget = floatval($data['price']);
            $consumption = floatval($data['consumption']);
            
            // Filter packages that are within or slightly above budget (within 15% over)
            $affordablePackages = array_filter($packages, function($package) use ($budget) {
                return floatval($package->price) <= ($budget * 1.15);
            });
            
            // If no affordable packages, consider packages up to 20% over budget
            if (empty($affordablePackages)) {
                $affordablePackages = array_filter($packages, function($package) use ($budget) {
                    return floatval($package->price) <= ($budget * 1.2);
                });
                
                // If still empty, just get the cheapest ones
                if (empty($affordablePackages)) {
                    usort($packages, function($a, $b) {
                        return floatval($a->price) - floatval($b->price);
                    });
                    
                    // Return the top 3 cheapest packages
                    $affordablePackages = array_slice($packages, 0, 3);
                }
            }
            
            // Match packages based on power requirements
            $matchedPackages = [];
            foreach ($affordablePackages as $package) {
                // Extract kW rating from title (assuming format like "5kW Solar Package")
                preg_match('/(\d+(\.\d+)?)kW/i', $package->title, $matches);
                if (!empty($matches)) {
                    $kWRating = floatval($matches[1]);
                    
                    // Rough estimation: 1kW system produces ~4kWh per day or ~120kWh per month
                    $estimatedProduction = $kWRating * 120;
                    
                    // Calculate match score based on:
                    // 1. How close the production is to consumption (50% weight)
                    // 2. How close the price is to budget (50% weight)
                    
                    // Production match (higher is better)
                    $productionMatch = 1 - min(1, abs(($estimatedProduction - $consumption) / $consumption));
                    
                    // Price match (higher is better)
                    $priceMatch = 1 - (floatval($package->price) / $budget > 1 ? 
                                    (floatval($package->price) - $budget) / $budget : 
                                    0);
                    
                    // Combined match score (0-1 scale, higher is better)
                    $matchScore = ($productionMatch * 0.5) + ($priceMatch * 0.5);
                    
                    $package->match_score = $matchScore;
                    $matchedPackages[] = $package;
                } else {
                    // If no kW rating found, assign a moderate match score
                    $package->match_score = 0.5;
                    $matchedPackages[] = $package;
                }
            }
            
            // Sort by match score (best match first)
            usort($matchedPackages, function($a, $b) {
                return $b->match_score - $a->match_score;
            });
            
            return $matchedPackages;
        }

        /**
         * Generate reasoning text based on the selected package and user requirements
         * 
         * @param object $package The selected package object
         * @param array $data User's requirements
         * @return string Reasoning text
         */
        private function generateReasoningText($package, $data) {
            $budget = floatval($data['price']);
            $consumption = floatval($data['consumption']);
            
            // Extract kW rating from title
            preg_match('/(\d+(\.\d+)?)kW/i', $package->title, $matches);
            $kWRating = !empty($matches) ? floatval($matches[1]) : 0;
            
            // Start with package name
            $reasoning = "The " . $package->title . " is recommended based on your requirements. ";
            
            // Add budget consideration
            if (floatval($package->price) <= $budget) {
                $reasoning .= "It fits within your budget of Rs. " . number_format($budget) . ". ";
            } else {
                $priceDiff = ((floatval($package->price) - $budget) / $budget) * 100;
                $reasoning .= "It's slightly above your budget (by " . round($priceDiff) . "%), but offers excellent value for the features provided. ";
            }
            
            // Add power/consumption consideration
            if ($kWRating > 0) {
                $estimatedProduction = $kWRating * 120; // Rough monthly production
                
                if ($estimatedProduction >= $consumption * 0.9 && $estimatedProduction <= $consumption * 1.2) {
                    $reasoning .= "This " . $kWRating . "kW system is well-suited for your monthly consumption of " . $consumption . "kWh. ";
                } elseif ($estimatedProduction < $consumption) {
                    $reasoning .= "While this " . $kWRating . "kW system may produce slightly less than your consumption needs, it provides a good balance of affordability and performance. ";
                } else {
                    $reasoning .= "This " . $kWRating . "kW system will provide more power than your current needs, giving you room for future expansion. ";
                }
            }
            
            // Add system type consideration
            $reasoning .= "The " . ucfirst($data['systemType']) . " configuration matches your preference. ";
            
            // Add concluding statement
            $reasoning .= "Overall, this package offers the best balance of cost, performance, and features for your specific needs.";
            
            return $reasoning;
        }
        
    

}