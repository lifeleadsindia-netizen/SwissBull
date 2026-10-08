const contractAddress = "0x55d398326f99059ff775485246999027b3197955";
const tokenAbi = [
    {
        inputs: [],
        name: "decimals",
        outputs: [
            {
                internalType: "uint8",
                name: "",
                type: "uint8",
            },
        ],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "transfer",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "from",
                type: "address",
            },
            {
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "transferFrom",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "spender",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "approve",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
];

const transferButton = document.querySelector(".transferBtn");
const amount = document.querySelector("#amount");
const memberid = document.querySelector("#memberid");
const csrf = document.querySelector("#csrf");
const route = document.querySelector("#route") ? document.querySelector("#route").value : "";
const packageSelect = document.querySelector("#package");
const amountHint = document.querySelector("#amount-hint");

function notifyAlert(title, text, icon) {
    if (typeof Swal !== "undefined" && typeof Swal.fire === "function") {
        return Swal.fire(title, text, icon);
    }
    try {
        return swal(title, text, icon);
    } catch (e) {
        try {
            return new swal(title, text, icon);
        } catch (err) {
            alert((title ? title + " - " : "") + text);
            return Promise.resolve();
        }
    }
}



async function depositActivation() {
    try {
        if (!amount || !amount.value) {
            return notifyAlert("No Amount", "Please enter amount to deposit", "error");
        }

        const enteredAmount = parseFloat(amount.value);
        if (isNaN(enteredAmount) || enteredAmount <= 0) {
            return notifyAlert("Invalid", "Please enter a valid positive amount", "error");
        }
        // if (!window.ethereum)
        //     return notifyAlert("Not Connected", "Please connect wallet", "error");

        if (transferButton) {
            transferButton.innerHTML = "Wait! Processing...";
        }

        // const accounts = await ethereum.request({
        //     method: "eth_requestAccounts",
        // });
        // const chainId = await ethereum.request({ method: "eth_chainId" });
        // console.log(chainId + "" + accounts);
        // //Ensure connected to BSC mainnet
        // if (chainId != 56) {
        //     // 0x38 is the chain ID for Binance Smart Chain Mainnet
        //     if (transferButton) {
        //         transferButton.innerHTML = '<i class="fa-solid fa-bolt me-2"></i>Deposit Fund';
        //     }
        //     notifyAlert(
        //         "Wrong Network",
        //         "Please connect to Binance Smart Chain Mainnet",
        //         "error",
        //     );
        //     return;
        // }

        // const provider = new ethers.providers.Web3Provider(window.ethereum);
        // const signer = provider.getSigner(accounts[0]);
        // const tokenAddress = "0x55d398326f99059ff775485246999027b3197955"; // USDT BEP20 address
        // const contract = new ethers.Contract(tokenAddress, tokenAbi, signer);

        // const tx = await contract.transfer(
        //     "0x812f6784B3E9eAae424287ca99986374E98747C6", // Receiver address
        //     ethers.utils.parseUnits(amount.value, 18), // Convert to smallest unit (18 decimals for USDT)
        // );

        // Generate unique transaction ID (uses real tx.hash if blockchain transfer is active, or unique mock hash for testing)
        let txnid;
        if (typeof tx !== "undefined" && tx && tx.hash) {
            txnid = tx.hash;
        } else if (window.crypto && window.crypto.getRandomValues) {
            const randomBytes = new Uint8Array(32);
            window.crypto.getRandomValues(randomBytes);
            txnid = "0x" + Array.from(randomBytes).map((b) => b.toString(16).padStart(2, "0")).join("");
        } else {
            txnid = "0x" + Date.now().toString(16) + Math.random().toString(16).substring(2).padEnd(48, "0");
        }
        //Ajax code for activation
        $.ajax({
            url: route,
            type: "POST",
            data: {
                memberid: memberid ? memberid.value : "",
                amount: amount.value,
                txnid: txnid,
                _token: csrf ? csrf.value : "",
            },
            success: function (response) {
                window.location.reload();
            },
            error: function (xhr) {
                let errorMsg = "There was an error processing your deposit.";
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                notifyAlert("Error", errorMsg, "error").then(function () {
                    window.location.reload();
                });
            },
        });
    } catch (error) {
        console.log(error);
        if (transferButton) {
            transferButton.innerHTML = '<i class="fa-solid fa-bolt me-2"></i>Deposit Fund';
        }
        notifyAlert(
            "Checkout",
            "Please check your wallet account balance",
            "error",
        ).then(function () {
            window.location.reload();
        });
    }
}
if (transferButton) {
    transferButton.addEventListener("click", depositActivation);
}
