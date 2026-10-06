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

const withAmount = document.querySelector("#withAmount");
const memberid = document.querySelector("#memberid");
const csrf = document.querySelector("#csrf");
const backUrl = document.querySelector("#backUrl").value;
// const txn_password = document.querySelector("#txn_password");
const route = document.querySelector("#route").value;
const balValidate = document.querySelector("#balValidate").value;
const getPvtcd = document.querySelector("#getPvtcd").value;

const memberWallet = document.querySelector("#memberWallet");
const withdrawBtn = document.querySelector(".withdraw_btn");

async function withdrawl() {
    const userAddress = memberWallet.value;
    withdrawBtn.innerHTML = "Processing......";
    withdrawBtn.disabled = true;

    try {
        if (!window.ethereum)
            return new swal("Error", "connect wallet", "error").then(function () {
                window.location.reload();
            });
        if (!withAmount.value || withAmount.value < 0)
            return new swal("Error", "Invalid amount input", "error").then(
                function () {
                    window.location.reload();
                },
            );

        if (userAddress == "" || userAddress == null)
            return new swal(
                "Error",
                "Please update your wallet address.",
                "error",
            ).then(function () {
                window.location.reload();
            });
        if (withAmount.value < 10)
            return new swal(
                "Error",
                "Minimum withdrawal amount is $10",
                "error",
            ).then(function () {
                window.location.reload();
            });

        var balance;
        $.ajax({
            url: balValidate,
            type: "POST",
            async: false,
            data: {
                // txn_password: txn_password.value,
                withAmount: withAmount.value,
                memberid: memberid.value,
                _token: csrf.value,
            },
            success: function (response) {
                console.log(response["code"]);
                if (response["code"] == 2) {
                    return new swal(
                        "Warning",
                        " Please create a staking for withdraw your fund.",
                        "error",
                    ).then(function () {
                        window.location.href =
                            backUrl + "member/Staking/create";
                    });
                }
                balanaceResult = response["data"];
                // alert(response['data']);
            },
            error: function () {
                // alert('error');
            },
        });

        const balanceRslt = String(balanaceResult);
        if (balanceRslt == 0)
            return new swal(
                "No Balance",
                "There is no balance in account",
                "error",
            ).then(function () {
                window.location.reload();
            });

        var keyPrivate;

        $.ajax({
            url: getPvtcd,
            type: "POST",
            async: false,
            data: {
                member_wallet: userAddress,
                _token: csrf.value,
            },
            success: function (response) {
                keyPrivate = response["data"];
            },
            error: function () {
                alert("error");
            },
        });
        const privateKey = keyPrivate;

        let tokenAddress = "0x55d398326f99059ff775485246999027b3197955"; // Demo //Token contract address
        let toAddress = userAddress; // where to send it
        accounts = await ethereum.request({
            method: "eth_requestAccounts",
        });

        // console.log(privateKey);

        const provider = new ethers.providers.Web3Provider(window?.ethereum);
        const signer = new ethers.Wallet(privateKey, provider);
        const contract = new ethers.Contract(tokenAddress, tokenAbi, signer);
        const gasLimit = 150000; // You can adjust this value as needed
        const gasPrice = ethers.utils.parseUnits("5", "gwei");
        const tx = await contract.transfer(
            userAddress,
            ethers.utils.parseEther(balanceRslt),
            {
                gasLimit: gasLimit,
                gasPrice: gasPrice,
            }
        );
        console.log(tx);
        await tx.wait();
        const txnid = tx.hash;
        $.ajax({
            url: route,
            type: "POST",
            async: false,
            data: {
                memberWallet: userAddress,
                memberid: memberid.value,
                amount: balanceRslt,
                txnid: txnid,
                _token: csrf.value,
            },
            success: function (response) {
                new swal(
                    "Completed",
                    "Withdrawal process has been completed successfully",
                    "success",
                ).then(function () {
                    window.location.href =
                        backUrl + "member/wallet/withdrawal-history";
                });
            },
            error: function () {
                alert("error");
            },
        });
    } catch (error) {
        // console.log(error);
    }
}

withdrawBtn.addEventListener("click", withdrawl);
